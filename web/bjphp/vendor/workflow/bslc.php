<?php

/**
 * http://www.baijienet.com
 * User: sint
 * Date: 2016/11/14
 * Time: 16:57
 */
class _bsl_type
{
	const DUMMY = 0;
	const IDENTITY = 1;
	const LITERAL = 2;
	
	const KEY_BEGIN = 10;
	const KNULL = 11;
	const KTRUE = 12;
	const KFALSE = 13;
	const KFUNCTION = 14;
	const KEND = 15;
	const KIF = 16;
	const KELSE = 17;
	const KELSEIF = 18;
	const KWHILE = 19;
	const KEACH = 20;
	const KKEY = 21;
	const KINDEX = 22;
	const KFIRST = 23;
	const KLAST = 24;
	const KBREAK = 25;
	const KCONTINUE = 26;
	const KRETURN = 27;
	const KDIM = 28;
	const KAPP = 29;
	const KPROC = 30;
	const KVAR = 31;
	const KROUTE = 32;
	const KTHIS = 33;
	const KEY_END = 34;
	
	const SEP_BEGIN = 50;
	const SLB = 51;
	const SRB = 52;
	const SLSB = 53;
	const SRSB = 54;
	const SLCB = 55;
	const SRCB = 56;
	const SCOMMA = 57;
	const SDOT = 58;
	const SCOLON = 59;
	const SCALL = 60;
	const SEP_END = 61;
	
	const OP_BEGIN = 90;
	const OADD = 91;
	const OSUB = 92;
	const OMUL = 93;
	const ODIV = 94;
	const OMOD = 95;
	const OAND = 96;
	const OOR = 97;
	const ONOT = 98;
	const OLONG = 99;
	const OFLOAT = 100;
	const OSTRING = 101;
	const OASS = 102;
	const OLT = 103;
	const OLE = 104;
	const OGT = 105;
	const OGE = 106;
	const OEQ = 107;
	const ONE = 108;
	const OP_END = 109;
	
	
	const RUN_EXPR = 10001;
	const RUN_BODY = 10002;
	
	public static function text($t)
	{
		static $text;
		if (!$text) {
			$text = [
				self::IDENTITY => '关键字',
				self::LITERAL => '常量',
				self::KNULL => '常量',
				self::KTRUE => '布尔常量',
				self::KFALSE => '布尔常量',
				self::KFUNCTION => '函数',
				self::KEND => 'end',
				self::KIF => 'if',
				self::KELSE => 'else',
				self::KELSEIF => 'elseif',
				self::KWHILE => 'while',
				self::KEACH => 'each',
				self::KKEY => '@key',
				self::KINDEX => '@index',
				self::KFIRST => '@first',
				self::KLAST => '@last',
				self::KBREAK => 'break',
				self::KCONTINUE => 'continue',
				self::KRETURN => 'return',
				self::KDIM => 'dim',
				self::KAPP => 'app',
				self::KPROC => 'proc',
				self::KVAR => 'var',
				self::KROUTE => 'route',
				self::KTHIS => '@this',
			];
		}
		if (isset($text[$t])) return $text[$t];
		if ($t > self::SEP_BEGIN && $t < self::SEP_END) return "分割符";
		if ($t > self::OP_BEGIN && $t < self::OP_END) return "操作符";
		return "token:" . (string)$t;
	}
}

class _bsl_line
{
	//base
	public $row = 0;
	public $pos = 0;
	public $len = 0;
	public $index;
	
	//fast
	public $ts = 0;
	public $k = _bsl_type::DUMMY;
	public $nextpos = 0;
	public $empty = false;
	
	//lex & parse
	public $tree;
	
	//syntax
	private $node;
	
	public function __construct($row, $pos, $len)
	{
		$this->row = $row;
		$this->pos = $pos;
		$this->nextpos = $pos;
		$this->len = $len;
		$this->node = null;
	}
	
	public function to_node($app)
	{
		/* @var $app _bslapp */
		$compiler = $app->compiler;
		/* @var $compiler vendor_workflow_bslc */
		if (!$this->empty && !$this->node) {
			$this->peek($app);
			$tokens = $compiler->lex_line($app, $this);
			$count = count($tokens);
			if ($count < 1) {
				$this->empty = true;
				return null;
			}
			$token = $tokens[0];
			if ($token->k == _bsl_type::KDIM) {
				$this->node = new _bsldim();
				$this->node->k = $token->k;
			
				
				$token = $this->need($app, $tokens, 1, _bsl_type::IDENTITY);
				$this->node->name = $token->str;
				
				if ($count > 2 && $tokens[2]->k == _bsl_type::OASS) {
					$arr = array_slice($tokens, 3, $count - 3);
					
					$R = $compiler->S2R($app, $this, $arr);
					$this->node->expr = $compiler->tree($app, $R);
				}
			} else {
				if (isset($compiler->k_expr[$token->k])) {
					$this->node = $compiler->k_expr[$token->k]();
					$this->node->k = $token->k;
					
					array_shift($tokens);
					$R = $compiler->S2R($app, $this, $tokens);
					$this->node->expr = $compiler->tree($app, $R);
				} else if (in_array($token->k, [_bsl_type::KBREAK, _bsl_type::KCONTINUE, _bsl_type::KEND, _bsl_type::KELSE])) {
					$this->node = new _bslnode();
					$this->node->k = $token->k;
					
				} else {
					$this->node = new _bslstatement();
					$this->node->k = _bsl_type::RUN_EXPR;
					
					$R = $compiler->S2R($app, $this, $tokens);
					$this->node->expr = $compiler->tree($app, $R);
				}
			}
			$this->node->lindex = $this->index;
			$this->node->ts = $this->ts;
		}
		
		return $this->node;
	}
	
	public function peek($app)
	{
		/* @var $app _bslapp */
		$compiler = $app->compiler;
		$src = $app->source;
		/* @var $compiler vendor_workflow_bslc */
		$arr = $compiler->peek_key;
		//write_log("line {$this->index}:pos={$this->pos} len={$this->len} ".substr($src,$this->pos,$this->len));
		for ($i = 0; $i < $this->len; $i++) {
			$ch = $src[$this->pos + $i];
			//write_log("ch=".(string)$ch." t="."\t"."]");
			if ($ch == "\t") {
				$this->ts++;
				$this->nextpos++;
			} else break;
		}
		foreach ($arr as $a) {
			$str = $compiler->keys[$a - _bsl_type::KEY_BEGIN - 1];
			$sl = strlen($str);
			if (substr($src, $this->pos + $i, $sl) == $str) {
				$this->k = $a;
				$this->nextpos += $sl;
				return;
			}
			$str = $compiler->keys_cn[$a - _bsl_type::KEY_BEGIN - 1];
			$sl = strlen($str);
			if (substr($src, $this->pos + $i, $sl) == $str) {
				$this->k = $a;
				$this->nextpos += $sl;
				return;
			}
		}
		$pos = $this->skip_space($src);
		$this->empty = ($pos == $this->pos + $this->len);
		//write_log("line {$this->index}: ts=".$this->ts." k=".$this->k);
	}
	
	public function skip_space($src)
	{
		for ($i = $this->pos; $i < $this->pos + $this->len; $i++) {
			$ch = $src[$i];
			if ($ch == "\t" || $ch == "\r" || $ch == "\n") continue;
			if ($ch == ' ') {
				if ($i + 1 < $this->pos + $this->len && $src[$i + 1] == '_') {
					if ($i + 2 < $this->pos + $this->len && $src[$i + 2] == "\r" && $i + 3 < $this->pos + $this->len && $src[$i + 3] == "\n") {
						$i += 3;
						continue;
					} else break;
				} else {
					continue;
				}
			}
			if ($ch == '/' && $i + 1 < $this->pos + $this->len && $src[$i + 1] == '/') {
				$i = $this->pos + $this->len;
				break;
			}
			break;
		}
		return $i;
	}
	
	public function need($app, $tokens, $index, $t)
	{
		/* @var $app _bslapp */
		$compiler = $app->compiler;
		/* @var $compiler vendor_workflow_bslc */
		$hint = "期望" . _bsl_type::text($t);
		if ($index >= count($tokens)) $compiler->report_error($app, $hint);
		$token = $tokens[$index];
		if ($token->k != $t) $compiler->report_error($app, $hint);
		return $token;
	}
	
	public function is_empty()
	{
		return $this->empty;
	}
	
	/*public function match($app, $arr)
	{
		/ * @var $context _bsl_nodecontext * /
		$count = count($this->tokens);
		$count_arr = count($arr);
		$ret = [];
		for ($i = 0; $i < $count && $i < $count_arr; $i++) {
			$token = $this->tokens[$i];
			$t = $arr[$i];
			if ($token[0] != $t && !($t == _bsl_type::LITERAL && (
						$token[0] == _bsl_type::KTRUE ||
						$token[0] == _bsl_type::KNULL ||
						$token[0] == _bsl_type::KFALSE))
			) {
				$hint = "期望" . _bsl_type::text($t);
				bjerror("{$hint} [第{$this->row}行]{$context->app_file}");
			}
			if ($t == _bsl_type::LITERAL) {
				if ($token[0] == _bsl_type::KTRUE) $ret[] = true;
				else if ($token[0] == _bsl_type::KFALSE) $ret[] = false;
				else if ($token[0] == _bsl_type::KNULL) $ret[] = null;
				else
					$ret[] = $token[2];
			} else $ret[] = null;
		}
		return $ret;
	}*/
}

class _bsl_lines
{
	public $lines = [];
	
	public function parse0($app)
	{
		/* @var $app _bslapp */
		
		$src = $app->source;
		//write_log("lex lines source=".$src);
		$start = 0;
		$len = strlen($src);
		$index = 0;
	
		$find_start = 0;
		while (true) {
			$pos = strpos($src, "\r\n", $find_start);
			if ($pos === false) {
				$this->lines[] = new _bsl_line($index, $start, $len - $start);
				break;
			} else {
				
				if ($pos - $start >= 2 && $src[$pos - 1] == '_' && $src[$pos - 2] == ' ') {
					$find_start = $pos + 2;
					$index++;
					continue;
				}
				if( $pos > $start )
					$this->lines[] = new _bsl_line($index, $start, $pos - $start);
				
				$start = $pos + 2;
				$find_start = $start;
				
			
				$index++;
			}
		}
		$count = count($this->lines);
		for($i=0;$i<$count;$i++){
			$line = $this->lines[$i];
			$line->index = $i;
			//write_log("peeking{$i}");
			$line->peek($app);
		}
		//write_log("after peek line...");
	}
	
	public function find_end($app, $start, $ts)
	{
		/* @var $app _bslapp */
		$context = $app->compiler;
		/* @var $compiler vendor_workflow_bslc */
		$count = count($this->lines);
		for ($index = $start + 1; $index < $count; $index++) {
			$line = $this->lines[$index];
			/* @var $line _bsl_line */
			if ($line->is_empty()) continue;
			if ($line->ts > $ts) continue;
			if ($line->k != _bsl_type::KEND)
				$compiler->report_error($app, "不期望的语法单元，此处应为end");
			return $index;
		}
		$compiler->report_error($app, "缺少对应的end");
		return 0;
	}
}

class _bsl_token
{
	public $k;
	public $lline;
	public $str;
	
	public function __construct($k=_bsl_type::DUMMY, $l = 0, $s = null)
	{
		$this->k = $k;
		$this->lline = $l;
		$this->str = $s;
	}
}

class _bslnode
{
	public $k = _bsl_type::DUMMY;
	public $lindex=0;
	public $ts=0;
	
	public function get_nextline($app)
	{
		return $this->lindex+1;
	}
}

class _bslapp extends _bslnode
{
	//source
	public $source;
	public $lines;
	
	//compile context
	public $row;
	public $start;
	public $end;
	public $pos;//during lex
	public $dline;
	
	//compiler
	public $compiler;
	
	//application info
	public $name;
	public $type;
	public $id;
	
	public $vars = [];
	public $procs = [];
	public $routes = [];
	public $funcs = [];
	
	public $endindex=0;
	
	private $enter;
	private $leave;
	private $ppd=0;
	
	public function __construct()
	{
		$this->k = _bsl_type::KAPP;
	}
	
	public function parse0()
	{
		$compiler = $this->compiler;
		/* @var $compiler vendor_workflow_bslc */
		$lines = $this->lines;
		/* @var $lines _bsl_lines */
		$index = 0;
		$count = count($this->lines->lines);
		for (; $index < $count; $index++) {
			$line = $this->lines->lines[$index];
			/* @var $line _bsl_line */
			if (!$line->is_empty()) break;
		}
		$line = $this->lines->lines[$index];
		if ($line->k != _bsl_type::KAPP) {
			write_log("index=".$index." count=".$count." ".json_encode($line));
			$compiler->report_error($this, "期望app关键字");
		}
		$this->lindex = $index;
		write_log("lex app line");
		//app type,name
		$tokens = $compiler->lex_line($this, $line);
		$this->type = $line->need($this, $tokens, 0, _bsl_type::LITERAL)->str;
		$line->need($this, $tokens, 1, _bsl_type::SCOMMA);
		$this->name = $line->need($this, $tokens, 2, _bsl_type::LITERAL)->str;
		write_log("find end..");
		$this->endindex = $lines->find_end($this, $index, 0) ;
		write_log("after find end=".$this->endindex);
		$index = $this->endindex+1;
		
		while ($index < $count) {
			write_log("app parse0: index={$index}");
			for (; $index < $count; $index++) {
				$line = $this->lines->lines[$index];
				/* @var $line _bsl_line */
				if (!$line->is_empty()) break;
			}
			if( $index >= $count ) break;
			
			$line = $this->lines->lines[$index];
			write_log("line:{$line->k}");
			if ($line->k == _bsl_type::KVAR) {
				$var = new _bslvar();
				$this->vars[] = $var;
				
				//var "type",isarray,"name"[,defaultvalue]
				$tokens = $compiler->lex_line($this, $line);
				$var->type = $line->need($this, $tokens, 0, _bsl_type::LITERAL)->str;
				$line->need($this, $tokens, 1, _bsl_type::SCOMMA);
				$token = $line->need($this, $tokens, 2, _bsl_type::LITERAL);
				if ($token->k == _bsl_type::KFALSE) $var->is_array = false;
				else $var->is_array = true;
				$line->need($this, $tokens, 3, _bsl_type::SCOMMA);
				$var->name = $line->need($this, $tokens, 4, _bsl_type::LITERAL)->str;
				if (count($tokens) > 5 && $tokens[5]->k == _bsl_type::SCOMMA)
					$var->default = $line->need($this, $tokens, 6, _bsl_type::LITERAL)->str;
				
				$var->lindex = $index;
				$index ++;
				
			} else if ($line->k == _bsl_type::KPROC) {
				$proc = new _bslproc();
				$this->procs[] = $proc;
				
				//proc "type","name","id"
				$tokens = $compiler->lex_line($this, $line);
				$proc->type = $line->need($this, $tokens, 0, _bsl_type::LITERAL)->str;
				$line->need($this, $tokens, 1, _bsl_type::SCOMMA);
				$proc->name = $line->need($this, $tokens, 2, _bsl_type::LITERAL)->str;
				$line->need($this, $tokens, 3, _bsl_type::SCOMMA);
				$proc->id = $line->need($this, $tokens, 4, _bsl_type::LITERAL)->str;
				
				$proc->lindex = $index;
				$proc->endindex = $lines->find_end($this, $index, 0) ;
				
				$index = $proc->endindex+1;
				
			} else if ($line->k == _bsl_type::KROUTE) {
				$route = new _bslroute();
				$this->routes[] = $route;
				
				//route "start","end",order
				$tokens = $compiler->lex_line($this, $line);
				$route->start_proc = $line->need($this, $tokens, 0, _bsl_type::LITERAL)->str;
				$line->need($this, $tokens, 1, _bsl_type::SCOMMA);
				$route->end_proc = $line->need($this, $tokens, 2, _bsl_type::LITERAL)->str;
				$line->need($this, $tokens, 3, _bsl_type::SCOMMA);
				$route->order = $line->need($this, $tokens, 4, _bsl_type::LITERAL)->str;
				
				$route->lindex = $index;
				$route->endindex = $lines->find_end($this, $index, 0) ;
				
				$index = $route->endindex+1;
				
			} else if ($line->k == _bsl_type::KFUNCTION) {
				$func = new _bslfunction();
				$this->funcs[] = $func;
				
				//function func1()
				$tokens = $compiler->lex_line($this, $line);
				$func->name = $line->need($this, $tokens, 0, _bsl_type::IDENTITY)->str;
				
				$count_func_tokens = count($tokens);
				if ($count_func_tokens > 1) {
					$line->need($this, $tokens, 1, _bsl_type::SLB);
					$line->need($this, $tokens, $count_func_tokens - 1, _bsl_type::SRB);
					
					$arr = array_slice($tokens, 2, $count_func_tokens - 3);
					
					$R = $compiler->S2R($this, $line, $arr);
					$tree = $compiler->tree($this, $R);
					$args = $compiler->tree2array($tree);
					$func->set_args($args);
				}
				
				$func->lindex = $index;
				$func->endindex = $lines->find_end($this, $index, 0);
				
				$index = $func->endindex+1;
			} else $compiler->report_error($this, "不期望的语法单元");
		}
		write_log("after app parse0.");
	}
	
	public function get_enter()
	{
		if( !$this->ppd ) $this->parse_p();
		return $this->enter;
	}
	
	public function get_leave()
	{
		if( !$this->ppd ) $this->parse_p();
		return $this->leave;
	}
	
	private function parse_p()
	{
		$this->ppd = 1;
		for($i=$this->lindex+1;$i<$this->endindex;$i++)
		{
			$line = $this->lines->lines[$i];
			/* @var $line _bsl_line */
			if( $line->ts == 1 )
			{
				write_log("parse_p to node:line=".json_encode($line));
				$node = $line->to_node($this);
				/* @var $node _bslnode */
				if( $node->k == _bsl_type::KFUNCTION )
				{
					if( in_array( $node->name,['enter','进入'] ) )
						$this->enter = $line->to_node($this);
					else if( in_array( $node->name,['leave','离开'] ) )
						$this->leave = $line->to_node($this);
				}
			}
		}
	}
	
	public function get_start_proc()
	{
		foreach($this->procs as $proc)
		{
			if( $proc->type == 'start' ) return $proc;
		}
		return null;
	}
	
	//=== as compile context
	public function t4($line)
	{
		if ($line->nextpos + 3 < $line->pos+$line->len ) return substr($this->source, $line->nextpos, 4);
		return "";
	}
	
	public function tt($line)
	{
		if ($line->nextpos + 1 < $line->pos+$line->len) return substr($this->source, $line->nextpos, 2);
		return "";
	}
	
	public function t($line)
	{
		return $this->source[$line->nextpos];
	}
	
	public function next($i = 1)
	{
		$this->pos += $i;
	}
	
	public function find_proc_byid($id)
	{
		foreach($this->procs as $proc)
		{
			if( $proc->id == $id ) return $proc;
		}
		return null;
	}
}

class _bslvar extends _bslnode
{
	public $type;
	public $is_array;
	public $name;
	public $default;
	
	public function __construct()
	{
		$this->k = _bsl_type::KVAR;
	}
}

class _bslproc extends _bslnode
{
	public $type;
	public $name;
	public $id;
	public $endindex=0;
	
	private $enter;
	private $leave;
	private $role;
	private $ccrole;
	private $page;
	private $code;
	private $endexpr;
	private $ppd=0;
	private $rr0=0;
	private $routes;
	
	public function __construct()
	{
		$this->k = _bsl_type::KPROC;
	}
	
	private function parse_p($app)
	{
		/* @var $app _bslapp */
		$this->ppd = 1;
		for($i=$this->lindex+1;$i<$this->endindex;$i++)
		{
			$line = $app->lines[$i];
			/* @var $line _bsl_line */
			if( $line->ts == 1 )
			{
				$node = $line->to_node($app);
				/* @var $node _bslnode */
				if( $node->k == _bsl_type::KFUNCTION )
				{
					if( in_array( $node->name,['enter','进入'] ) )
						$this->enter = $line->to_node($app);
					else if( in_array( $node->name,['leave','离开'] ) )
						$this->leave = $line->to_node($app);
					else if( in_array( $node->name,['role','角色'] ) )
						$this->role = $line->to_node($app);
					else if( in_array( $node->name,['ccrole','抄送角色'] ) )
						$this->ccrole = $line->to_node($app);
					else if( in_array( $node->name,['page','页面'] ) )
						$this->page = $line->to_node($app);
					else if( in_array( $node->name,['code','代码'] ) )
						$this->code = $line->to_node($app);
					else if( in_array( $node->name,['endexpr','结束表达式'] ) )
						$this->endexpr = $line->to_node($app);
					
				}
			}
		}
	}
	
	public function get_enter($app)
	{
		if( !$this->ppd ) $this->parse_p($app);
		return $this->enter;
	}
	
	public function get_leave($app)
	{
		if( !$this->ppd ) $this->parse_p($app);
		return $this->leave;
	}
	public function get_role($app)
	{
		if( !$this->ppd ) $this->parse_p($app);
		return $this->role;
	}
	
	public function get_ccrole($app)
	{
		if( !$this->ppd ) $this->parse_p($app);
		return $this->ccrole;
	}
	public function get_endexpr($app)
	{
		if( !$this->ppd ) $this->parse_p($app);
		return $this->endexpr;
	}
	public function get_page($app)
	{
		if( !$this->ppd ) $this->parse_p($app);
		return $this->page;
	}
	public function get_code($app)
	{
		if( !$this->ppd ) $this->parse_p($app);
		return $this->code;
	}
	public function get_routes($app)
	{
		if( !$this->rr0 ){
			$this->rr0 = 1;
			$this->routes = [];
			/* @var $app _bslapp */
			foreach($app->routes as $r){
				/* @var $r _bslroute */
				if( $r->start_proc == $this->id ) $this->routes[] = $r;
			}
			ksort($this->routes);
		}
		return $this->routes;
	}
}

class _bslroute extends _bslnode
{
	public $start_proc;
	public $end_proc;
	public $order;
	private $expr;
	public $endindex=0;
	private $pp0=0;
	
	public function __construct()
	{
		$this->k = _bsl_type::KROUTE;
	}
	
	public function get_expr($app)
	{
		
		/* @var $compiler vendor_workflow_bslc */
		/* @var $app _bslapp */
		if( !$this->pp0 )
		{
			$this->pp0=1;
			$index = $this->lindex+1;
			if( $index == $this->endindex ){ $this->expr = null; }
			else{
				$line = $app->lines[ $index ];
				/* @var $line _bsl_line */
				$this->expr = $line->to_node($app);
			}
		}
		return $this->expr;
	}
}

class _bslfunction extends _bslnode
{
	public $name;
	private $body;
	private $args = [];
	public $endindex=0;
	public $ts=0;
	private $pp0=0;
	public function __construct()
	{
		$this->k = _bsl_type::KFUNCTION;
	}
	
	public function get_args()
	{
		return $this->args;
	}
	
	public function set_args($args)
	{
		$this->args = $args;
	}
	
	public function get_body($app)
	{
		/* @var $app _bslapp */
		if( !$this->pp0 )
		{
			$this->pp0 = 1;
			$this->body = [$this->ts+1,$this->lindex+1,$this->endindex-$this->lindex-1];
		}
		return $this->body;
	}
}

class _bslif extends _bslnode
{
	public $expr;
	private $body;
	private $elseifs = [];
	private $else;
	
	private $pp0=0;
	
	private function parse0($app)
	{
		/* @var $context _bsl_compiler_context */
		/* @var $compiler vendor_workflow_bslc */
		/* @var $app _bslapp */
		if( $this->pp0 ) return;
		$this->pp0 = 1;
		$count = count($app->lines->lines);
		for($i=$this->lindex+1;$i<$count;$i++)
		{
			$line = $app->lines->lines[$i];
			$context->row = $i;
			/* @var $line _bsl_line */
			if( $line->ts < $this->ts )
				$compiler->report_error($context,"if语句未正确匹配");
			
			if( $line->ts == $this->ts )
			{
				if( $line->k == _bsl_type::KEND ){
					$this->body=[$this->ts+1,$this->lindex+1,$i-$this->lindex-1];
					break;
				}
				else if( $line->k == _bsl_type::KELSE ){
					if( !$this->body && count($this->elseifs) < 1 )
						$this->body=[$this->ts+1,$this->lindex+1,$i-$this->lindex-1];
					$this->else = $line->to_node($app);
				}
				else if( $line->k == _bsl_type::KELSEIF ){
					if( $this->else )
						$compiler->report_error($context,"elseif不应该出现在else之后");
						
					if( !$this->body && count($this->elseifs) < 1 )
						$this->body=[$this->ts+1,$this->lindex+1,$i-$this->lindex-1];
					$this->elseifs[] = $line->to_node($app);
				}
			}
		}
		if( !$this->body ){
			$context->row = $this->lindex;
			$compiler->report_error($context,"if语句未正常结束");
		}
	}
	public function get_body($app)
	{
		$this->parse0($app);
		return $this->body;
	}
	public function get_elseifs($app)
	{
		$this->parse0($app);
		return $this->elseifs;
	}
	public function get_else($app)
	{
		$this->parse0($app);
		return $this->else;
	}
	public function get_nextline($app)
	{
		if( $this->else )
		{
			$body = $this->else->get_body($app);
			return $body[1]+$body[2]+1;
		}
		else if( count($this->elseifs) ){
			$elseif = $this->elseifs[count($this->elseifs)-1];
			$body = $elseif->get_body($app);
			return $body[1]+$body[2]+1;
		}
		else
		{
			$body = $this->get_body($app);
			return $body[1]+$body[2]+1;
		}
	}
}

class _bslwhile extends _bslnode
{
	public $expr;
	private $body;
	private $pp0=0;
	
	private function parse0($app)
	{
		/* @var $app _bslapp */
		
		$compiler = $app->compiler;
		/* @var $compiler vendor_workflow_bslc */
		
		if( $this->pp0 ) return;
		$this->pp0 = 1;
		$count = count($app->lines->lines);
		for($i=$this->lindex+1;$i<$count;$i++) {
			$line = $app->lines->lines[$i];
			$app->row = $i;
			
			/* @var $line _bsl_line */
			if( $line->ts < $this->ts )
				$compiler->report_error($app,"while语句未正确匹配");
			
			if( $line->ts == $this->ts ) {
				if ($line->k == _bsl_type::KEND) {
					$this->body = [$this->ts + 1, $this->lindex + 1, $i - $this->lindex - 1];
					break;
				}
				else
					$compiler->report_error($app,"while语句未正确匹配");
			}
		}
	}
	
	public function get_body($app)
	{
		$this->parse0($app);
		return $this->body;
	}
	
	public function get_nextline($app)
	{
		$body = $this->get_body($app);
		return $body[1]+$body[2]+1;
	}
}

class _bsleach extends _bslnode
{
	public $expr;
	private $body;
	private $pp0=0;
	
	private function parse0($app)
	{
		/* @var $app _bslapp */
		$compiler = $app->compiler;
		/* @var $context _bsl_compiler_context */
		/* @var $compiler vendor_workflow_bslc */
		
		if( $this->pp0 ) return;
		$this->pp0 = 1;
		$count = count($app->lines->lines);
		for($i=$this->lindex+1;$i<$count;$i++) {
			$line = $app->lines->lines[$i];
			$context->row = $i;
			
			/* @var $line _bsl_line */
			if( $line->ts < $this->ts )
				$compiler->report_error($app,"each语句未正确匹配");
			
			if( $line->ts == $this->ts ) {
				if ($line->k == _bsl_type::KEND) {
					$this->body = [$this->ts + 1, $this->lindex + 1, $i - $this->lindex - 1];
					break;
				}
				else
					$compiler->report_error($app,"each语句未正确匹配");
			}
		}
	}
	
	public function get_body($app)
	{
		$this->parse0($app);
		return $this->body;
	}
	public function get_nextline($app)
	{
		$body = $this->get_body($app);
		return $body[1]+$body[2]+1;
	}
}

class _bslelseif extends _bslnode
{
	public $expr;
	private $body;
	private $pp0=0;
	
	private function parse0($app)
	{
		/* @var $app _bslapp */
		$compiler = $app->compiler;
		/* @var $context _bsl_compiler_context */
		/* @var $compiler vendor_workflow_bslc */
		
		if( $this->pp0 ) return;
		$this->pp0 = 1;
		$count = count($app->lines->lines);
		for($i=$this->lindex+1;$i<$count;$i++) {
			$line = $app->lines->lines[$i];
			$context->row = $i;
			
			/* @var $line _bsl_line */
			if( $line->ts < $this->ts )
				$compiler->report_error($app,"elseif语句未正确匹配");
			
			if( $line->ts == $this->ts ) {
				if ($line->k == _bsl_type::KEND || $line->k == _bsl_type::KELSEIF || $line->k == _bsl_type::KELSE) {
					$this->body = [$this->ts + 1, $this->lindex + 1, $i - $this->lindex - 1];
					break;
				}
				else
					$compiler->report_error($app,"elseif语句未正确匹配");
			}
		}
	}
	
	public function get_body($app)
	{
		$this->parse0($app);
		return $this->body;
	}
}

class _bslelse extends _bslnode
{
	private $body;
	private $pp0=0;
	
	private function parse0($app)
	{
		/* @var $app _bslapp */
		$compiler = $app->compiler;
		/* @var $context _bsl_compiler_context */
		/* @var $compiler vendor_workflow_bslc */
		
		if( $this->pp0 ) return;
		$this->pp0 = 1;
		$count = count($app->lines->lines);
		for($i=$this->lindex+1;$i<$count;$i++) {
			$line = $app->lines->lines[$i];
			$context->row = $i;
			
			/* @var $line _bsl_line */
			if( $line->ts < $this->ts )
				$compiler->report_error($app,"else语句未正确匹配");
			
			if( $line->ts == $this->ts ) {
				if ($line->k == _bsl_type::KEND) {
					$this->body = [$this->ts + 1, $this->lindex + 1, $i - $this->lindex - 1];
					break;
				}
				else
					$compiler->report_error($app,"else语句未正确匹配");
			}
		}
	}
	
	public function get_body($app)
	{
		$this->parse0($app);
		return $this->body;
	}
}

class _bsldim extends _bslnode
{
	public $name;
	public $expr;
	
}

class _bslarg extends _bslnode
{
	public $name;
	public $expr;
}


class _bslstatement extends _bslnode
{
	public $expr;
	
}

class _bslreturn extends _bslnode
{
	public $expr;
}


class vendor_workflow_bslc
{
	public $keys;
	public $keys_cn;
	public $idsep;
	public $peek_key;
	public $k_expr;
	private $sten;
	private $tten;
	private $matchs;
	private $matchs2;
	private $matchs4;
	
	public function __construct()
	{
		$this->init();
	}
	
	public function init()
	{
		$this->idsep = " \t\r\n+-*/%&|~!#$.,:;'\"{}[]()=\\?";
		$this->sten = [
			['(', _bsl_type::SLB], [')', _bsl_type::SRB], ['[', _bsl_type::SLSB],
			[']', _bsl_type::SRSB], ['{', _bsl_type::SLCB], ['}', _bsl_type::SRCB],
			['.', _bsl_type::SDOT], [',', _bsl_type::SCOMMA], [':', _bsl_type::SCOLON],
			['+', _bsl_type::OADD], ['-', _bsl_type::OSUB], ['*', _bsl_type::OMUL],
			['/', _bsl_type::ODIV], ['%', _bsl_type::OMOD], ['!', _bsl_type::ONOT],
			['<', _bsl_type::OLT], ['>', _bsl_type::OGT], ['=', _bsl_type::OASS],
		];
		$this->tten = [
			['!=', _bsl_type::ONE], ['==', _bsl_type::OEQ], ['<=', _bsl_type::OLE],
			['>=', _bsl_type::OGE], ['||', _bsl_type::OOR], ['&&', _bsl_type::OAND],
			['::', _bsl_type::SCALL],
		];
		
		$this->keys = [
			"null", "true", "false",
			"function", "end", "if", "else", "elseif", "while", "each",
			"@key", "@index", "@first", "@last",
			"break", "continue", "return", "dim",
			"app", "proc", "var", "route",
			"@this"
		];
		$this->keys_cn = [
			"无", "真", "假",
			"函数", "结束", "如果", "否则", "否则如果", "当", "遍历",
			"@键", "@下标", "@首位", "@末位",
			"跳出", "继续", "返回", "定义",
			"流程", "环节", "变量", "路由",
			"@这个"
		];
		
		$this->peek_key = [
			_bsl_type::KAPP, _bsl_type::KEACH, _bsl_type::KELSEIF, _bsl_type::KELSE, _bsl_type::KEND,
			_bsl_type::KFUNCTION, _bsl_type::KIF, _bsl_type::KPROC, _bsl_type::KROUTE,
			_bsl_type::KVAR, _bsl_type::KWHILE
		];
		$this->k_expr = [
			_bsl_type::KIF => function () {
				return new _bslif();
			},
			_bsl_type::KELSEIF => function () {
				return new _bslelseif();
			},
			_bsl_type::KWHILE => function () {
				return new _bslwhile();
			},
			_bsl_type::KEACH => function () {
				return new _bsleach();
			},
			_bsl_type::KRETURN => function () {
				return new _bslreturn();
			},
		];
		
		$this->matchs4 = [
			function ($app,$line, $ch4) {
				/* @var $app _bslapp */
				/* @var $line _bsl_line */
				if ($ch4 == " _\r\n") {
					$line->nextpos += 4;
					return new _bsl_token(_bsl_type::DUMMY, $line->row);
				}
				return null;
			}
		];
		$this->matchs2 = [
			function ($app,$line, $ch2) {
				/* @var $app _bslapp */
				/* @var $line _bsl_line */
				if ($ch2 == "//") {
					$line->nextpos = $line->pos+$line->len;
					return new _bsl_token(_bsl_type::DUMMY, $line->row);
				}
				foreach ($this->tten as $t) {
					if ($ch2 == $t[0]) {
						$line->nextpos += 2;
						return new _bsl_token($t[1], $line->row);
					}
				}
				
				return null;
			}
		];
		$this->matchs = [
			function ($app,$line, $ch) {//tab
				/* @var $app _bslapp */
				/* @var $line _bsl_line */
				if ($ch == "\t") {
					$line->nextpos++;
					return new _bsl_token(_bsl_type::DUMMY, $line->row);
				}
				return null;
			},
			function ($app,$line, $ch) {// ' ' '\r'
				/* @var $app _bslapp */
				/* @var $line _bsl_line */
				if ($ch == ' ' || $ch == "\r" || $ch == "\n" ) {
					$line->nextpos++;
					return new _bsl_token(_bsl_type::DUMMY, $line->row);
				}
				return null;
			},
			function ($app,$line, $ch) {// enter
				/* @var $app _bslapp */
				/* @var $line _bsl_line */
				if ($ch == "\n") {
					$line->nextpos++;
					return new _bsl_token(_bsl_type::DUMMY, $line->row);
				}
				return null;
			},
			function ($app,$line, $ch) {// sten
				/* @var $app _bslapp */
				/* @var $line _bsl_line */
				foreach ($this->sten as $t) {
					if ($ch == $t[0]) {
						$line->nextpos++;
						return new _bsl_token($t[1], $line->row);
					}
				}
				return null;
			},
			function ($app,$line, $ch) {// "
				/* @var $app _bslapp */
				/* @var $line _bsl_line */
				if ($ch == '"') {
					$j = $line->nextpos + 1;
					
					while ($j < $line->pos+$line->len ) {
						$ch2 = $app->source[$j];
						if ($ch2 == '"') {
							if ($j + 1 < $line->pos+$line->len && $app->source[$j + 1] == '"') {
								$j++;
							} else break;
						} else if ($ch2 == '_') {
							if ($app->source[$j - 1] == ' ' && $j + 1 < $line->pos+$line->len && $app->source[$j + 1] == '\r') {
								
								$j += 2;
							}
						}
						$j++;
					}
					if ($app->source[$j] != '"')
						bjerror("字符串未正常结尾");
					$str = substr($app->source, $line->nextpos + 1, $j - $line->nextpos - 1);
					$str = str_replace(" _\r\n", "", $str);
					$str = str_replace("\\n", "\n", $str);
					$str = str_replace("\\r", "\r", $str);
					$str = str_replace("\\t", "\t", $str);
					$line->nextpos = $j + 1;
					return new _bsl_token(_bsl_type::LITERAL, $line->row, $str);
				}
				return null;
			},
			function ($app,$line, $ch) {// number
				/* @var $app _bslapp */
				/* @var $line _bsl_line */
				if ($ch >= '0' && $ch <= '9') {
					$j = $line->nextpos + 1;
					
					while ($j < $line->pos+$line->len) {
						$ch2 = $app->source[$j];
						if (!(($ch2 >= '0' && $ch2 <= '9') || $ch2 == '.')) break;
						$j++;
					}
					$line->nextpos = $j;
					$str = substr($app->source, $line->nextpos, $j - $line->nextpos);
					if (strpos($str, ".") !== false) $val = (float)$str;
					else $val = (int)$str;
					return new _bsl_token(_bsl_type::LITERAL, $line->row, $val);
				}
				return null;
			},
			function ($app,$line, $ch) {// id
				/* @var $app _bslapp */
				/* @var $line _bsl_line */
				$j = $line->nextpos;
				while ($j < $line->pos+$line->len) {
					$ch2 = $app->source[$j];
					if (strpos($this->idsep, $ch2) !== false) break;
					$j++;
				}
				$str = substr($app->source, $line->nextpos, $j - $line->nextpos);
				$line->nextpos = $j;
				
				for ($k = 0; $k < _bsl_type::KEY_END - _bsl_type::KEY_BEGIN - 1; $k++) {
					if ($str == $this->keys[$k] || $str == $this->keys_cn[$k]) {
						$kx =_bsl_type::KEY_BEGIN + 1 + $k;
						if( $kx == _bsl_type::KNULL )
							return new _bsl_token(_bsl_type::LITERAL,$line->row,null);
						else if( $kx == _bsl_type::KTRUE )
							return new _bsl_token(_bsl_type::LITERAL,$line->row,true);
						else if( $kx == _bsl_type::KFALSE )
							return new _bsl_token(_bsl_type::LITERAL,$line->row,false);
						else
							return new _bsl_token(_bsl_type::KEY_BEGIN + 1 + $k, $line->row, $str);
					}
				}
				return new _bsl_token(_bsl_type::IDENTITY, $line->row, $str);
			},
		];
	}
	
	public function compile_app($source, $filename)
	{
		$app = new _bslapp();
		$app->source = $source;
		$app->name = $filename;
		$app->compiler = $this;
		
		$app->lines = new _bsl_lines();
		$app->lines->parse0($app);
		
		$app->parse0();
		
		return $app;
	}
	
	public function lex_line($app, $line)
	{
		/* @var $app _bslapp */
		/* @var $line _bsl_line */
		
		$val = null;
		$arr = [];
		while ($line->nextpos < $line->pos+$line->len) {
			//write_log("nextpos:".$line->nextpos);
			$line->skip_space($app->source);
			$val = null;
			foreach ($this->matchs4 as $m) {
				$val = $m($app,$line, $app->t4($line));
				if ($val != null) break;
			}
			if ($val == null) {
				foreach ($this->matchs2 as $m) {
					$val = $m($app,$line, $app->tt($line));
					if ($val != null) break;
				}
				if ($val == null) {
					foreach ($this->matchs as $m) {
						$val = $m($app,$line, $app->t($line));
						if ($val != null) break;
					}
				}
			}
			
			if ($val == null) {
				//write_log("(row:{$line->row}) val==null pos=".$line->nextpos);
				if ($line->nextpos < $line->pos+$line->len)
					$this->report_error($app, "词法错误");
				break;
			} else {
				//write_log("find (row:{$line->row}) val.k=".$val->k." pos=".$line->nextpos);
				if ($val->k != _bsl_type::DUMMY) $arr[] = $val;
			}
		}
		return $arr;
	}
	
	public function report_error($context, $hint)
	{
		/* @var $context _bslapp */
		$line = $context->row + 1;
		bjerror("{$context->name} [行{$line}]" . $hint);
	}
	
	public function is_key($t)
	{
		return $t > _bsl_type::KEY_BEGIN && $t < _bsl_type::KEY_END;
	}
	
	public function is_sep($t)
	{
		return $t > _bsl_type::SEP_BEGIN && $t < _bsl_type::SEP_END;
	}
	
	public function S2R($context, $line, $S)
	{
		/* @var $context _bslapp */
		/* @var $line _bsl_line */
		$R = [];
		$Y = [ new _bsl_token() ];
		$count = count($S);
		for ($i = 0; $i < $count; $i++) {
			$t = $S[$i];
			/* @var $t _bsl_token */
			if ($t->k == _bsl_type::IDENTITY || $t->k == _bsl_type::LITERAL) {
				$R[] = $t;
			} else if ($t->k == _bsl_type::SRB) {
				$this->Y2R($context, $line, $Y, $R, $t, _bsl_type::SLB);
			} else if ($t->k == _bsl_type::SRSB) {
				$this->Y2R($context, $line, $Y, $R, $t, _bsl_type::SLSB);
			} else if ($t->k == _bsl_type::SRCB) {
				$this->Y2R($context, $line, $Y, $R, $t, _bsl_type::SLCB);
			} else if ($this->_is_expr_op($t->k)) {
				if (in_array($t->k, [_bsl_type::ONOT, _bsl_type::OSTRING, _bsl_type::OFLOAT, _bsl_type::OLONG,_bsl_type::SCALL])) {
					$R[] = [_bsl_type::DUMMY];
				} else if ($this->is_left($t->k)) {
					if ($i > 0) {
						$back = $S[$i - 1];
						if ($this->is_left($back[0]) || $this->is_op($back[0]) || $back[0] == _bsl_type::SCOMMA) {
							$R[] = [_bsl_type::DUMMY];
						}
					}
					else $R[] = [_bsl_type::DUMMY];
				}
				
				while (true) {
					$yt = $Y[count($Y) - 1];
					if ($yt->k == _bsl_type::DUMMY || $this->is_left($yt->k)) {
						break;
					}
					if ($this->get_priority($yt->k) >= $this->get_priority($t->k)) {
						$R[] = $yt;
						array_pop($Y);
					} else break;
				}
				
				$Y[] = $t;
				
				if ($this->is_left($t->k)) {
					if ($i < $count - 1) {
						$rt = $S[$i + 1];
						if ($this->is_right($rt->k)) {
							$R[] = new _bsl_token();
						}
					}
				}
			} else $this->report_error($context,"不该出现的语法单元{$t->k}");
		}
		
		while (count($Y) > 0) {
			$tmp = array_pop($Y);
			if ($tmp->k == _bsl_type::DUMMY) break;
			$R[] = $tmp;
		}
		return $R;
	}
	
	private function Y2R($context, $line, &$Y, &$R, $to, $ty)
	{
		/* @var $context _bslapp */
		/* @var $line _bsl_line */
		while (true) {
			$tx = array_pop($Y);
			if ($tx->k == _bsl_type::DUMMY || ($this->is_left($tx->k) && $tx->k != $ty)) {
				$this->report_error($context,"不匹配的括号，缺少'" . _bsl_type::text($ty) . "'");
			}
			$R[] = $tx;
			if ($tx->k == $ty) break;
		}
	}
	
	public function is_left($t)
	{
		return $t == _bsl_type::SLB || $t == _bsl_type::SLCB || $t == _bsl_type::SLSB;
	}
	
	public function _is_expr_op($t)
	{
		return $this->is_op($t) || in_array($t,[_bsl_type::SCOMMA,_bsl_type::SCOLON,_bsl_type::SDOT,_bsl_type::SLB,
		_bsl_type::SLCB,_bsl_type::SLSB,_bsl_type::SCALL]);
	}
	
	public function is_op($t)
	{
		return $t > _bsl_type::OP_BEGIN && $t < _bsl_type::OP_END;
	}
	
	public function get_priority($t)
	{
		if ($t == _bsl_type::SCALL) return 1020;
		if ($t == _bsl_type::SDOT) return 1010;
		if ($t == _bsl_type::SLB || $t == _bsl_type::SLCB || $t == _bsl_type::SLSB) return 1000;
		if ($t == _bsl_type::OLONG || $t == _bsl_type::OSTRING) return 980;
		if ($t == _bsl_type::OMUL || $t == _bsl_type::ODIV || $t == _bsl_type::OMOD) return 970;
		if ($t == _bsl_type::OADD || $t == _bsl_type::OSUB) return 960;
		if ($t >= _bsl_type::OLT && $t <= _bsl_type::ONE) return 950;
		if ($t == _bsl_type::ONOT) return 940;
		if ($t == _bsl_type::OAND || $t == _bsl_type::OOR) return 930;
		if ($t == _bsl_type::OASS) return 930;
		if ($t == _bsl_type::SCOMMA) return 920;
		if ($t == _bsl_type::DUMMY) return 0;
		else return 10;
	}
	
	public function is_right($t)
	{
		return $t == _bsl_type::SRB || $t == _bsl_type::SRCB || $t == _bsl_type::SRSB;
	}
	
	public function tree($context, $R)
	{
		/* @var $context _bslapp */
		/* @var $line _bsl_line */
		$TM = [];
		$count = count($R);
		for ($i = 0; $i < $count; $i++) {
			$t = $R[$i];
			if ($t->k == _bsl_type::IDENTITY || $t->k == _bsl_type::LITERAL || $t->k == _bsl_type::DUMMY)
				$TM[] = $t;
			else {
				$right = null;
				if (count($TM) > 0) $right = array_pop($TM);
				else $this->report_error($context,"缺少右操作数");
				$left = null;
				if (count($TM) > 0) $left = array_pop($TM);
				else $this->report_error($context,"缺少左操作数");
				
				if (!$this->is_single($t->k) && !$this->is_left($t->k)) {
					if (!is_array($left) && $left->k == _bsl_type::DUMMY)
						$this->report_error($context,"缺少左操作数");
					if (!is_array($right) && $right->k == _bsl_type::DUMMY)
						$this->report_error($context,"缺少右操作数");
					
				}
				if ($t->k == _bsl_type::ONOT || $t->k == _bsl_type::OLONG || $t->k == _bsl_type::OSTRING || $t->k == _bsl_type::OFLOAT) {
					if (!is_array($right) && $right->k == _bsl_type::DUMMY)
						$this->report_error($context,"缺少右操作数");
				}
				$TM[] = [$left, $t, $right];
			}
		}
		if (count($TM) > 1) {
			$this->report_error($context,"缺少运算数");
		}
		if (count($TM) == 1) return $TM[0];
		else return null;
	}
	
	public function is_single($t)
	{
		return in_array($t, [_bsl_type::ONOT, _bsl_type::OLONG, _bsl_type::OSTRING, _bsl_type::OFLOAT]);
	}
	
	public function tree2array($tree)
	{
		if (is_array($tree)) {
			if ($tree[1] == _bsl_type::SCOMMA) {
				$a1 = $this->tree2array($tree[0]);
				$a2 = $this->tree2array($tree[2]);
				return array_merge($a1, $a2);
			} else return [$tree];
		} else return [$tree];
	}
}

