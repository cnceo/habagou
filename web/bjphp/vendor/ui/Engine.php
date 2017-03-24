<?php
// =======================================================================
// | 百捷PHP框架(BJPHP)
// | ---------------------------------------------------------------------
// | 许可协议Apache2 ( http://www.apache.org/licenses/LICENSE-2.0 )
// | 技术支持QQ群：276228406
// | 微信公众号  ：百捷网络
// | 官方网站    ：http://www.baijienet.com
// =======================================================================

namespace bjphp\vendor\ui;

class te_token
{
	const TEXT = 1;// text
	const CSS_CODE = 2;// css
	const JS_CODE = 3;// js
	const IDENTITY = 4;// identity
	const STRING = 5;// "string"
	const NUM_INT = 6;// int
	const NUM_FLOAT=7;// float
	const DOT = 8;// .
	const COMMA = 9;// ,
	const LEFT = 10;// {{
	const RIGHT = 11;// }}
	const OUTPUT = 12;// =
	const OUTPUT_EL = 13;// ==
	const CALL = 14;// for parse phase "func("
	const FLOW = 15;// #
	const BLOCK = 16;// >
	const KEYWORD = 17;// if else end each eachr with unless
	const BRACKET_LEFT = 18;// (
	const BRACKET_RIGHT = 19;// )
	const OP_ADD=20;// +
	const OP_SUB=21;// -
	const OP_MUL=22;// *
	const OP_DIV=23;// /
	const OP_MOD=24;// %
	const OP_AND=25;// &&
	const OP_OR=26;// ||
	const OP_NOT=27;// !
	const OP_LT=28;// <
	const OP_LE=29;// <=
	const OP_GT=30;// >
	const OP_GE=31;// >=
	const OP_EQ=32;// ==
	const OP_NE=33;// !=

	
	public function __construct($type,$text)
	{
		$this->type = $type;
		$this->text = $text;
	}
	
	public function is_type($t)
	{
		return $this->type == $t;
	}

	public static function get_priority($op)
	{
		if( $op == te_token::DOT ) return 130;
		if( $op == te_token::CALL ) return 120;
		if( $op == te_token::BRACKET_LEFT ) return 110;
		if( $op == te_token::OP_MUL || $op == te_token::OP_DIV || $op == te_token::OP_MOD ) return 100;
		if( $op == te_token::OP_ADD || $op == te_token::OP_SUB ) return 90;
		if( $op == te_token::OP_LT || $op == te_token::OP_LE || $op == te_token::OP_GT || $op == te_token::OP_GE || $op == te_token::OP_EQ || $op == te_token::OP_NE ) return 80;
		if( $op == te_token::OP_NOT ) return 70;
		if( $op == te_token::OP_AND ) return 60;
		if( $op == te_token::OP_OR ) return 50;
		if( $op == te_token::COMMA ) return 40;
		return 0;
	}
	public static function is_oper($op)
	{
		return in_array($op,[te_token::OP_LT,te_token::OP_LE,te_token::OP_GT,te_token::OP_GE,te_token::OP_EQ,te_token::OP_NE,
			te_token::OP_NOT,te_token::OP_AND,te_token::OP_OR,te_token::DOT,te_token::COMMA,
			te_token::BRACKET_LEFT,te_token::CALL,
			te_token::OP_ADD,te_token::OP_SUB,te_token::OP_MUL,te_token::OP_DIV,te_token::OP_MOD]);
	}
	public static function op_str($op)
	{
		$arr=['+','-','*','/','%','&&','||','!','<','<=','>','>=','==','!='];
		return $arr[$op - te_token::OP_ADD];
	}
	
	public $type;
	public $text;
}

class Lex
{
	public function __construct()
	{
		$this->tokens = [];
	}
	public function execute($src)
	{
		$this->source = $src;
		$this->length = strlen($src);
		$this->pos = 0;
		$this->in_script = false;
		$this->first = false;
		
		while(true)
		{
			$token = $this->read();
			if( $token == null ) break;
			$this->add_token($token);
		}
	}
	
	public function get_tokens()
	{
		return $this->tokens;
	}
	
	private function add_token($token)
	{
		//write_log('add_token:'.$token->type.' text='.$token->text);
		$this->tokens[] = $token;
	}
	
	private function is_space($ch)
	{
		return strpos($this->space,$ch) !== false;
	}
	
	private function is_sep($ch)
	{
		return strpos($this->sep,$ch) !== false;
	}

	private function skip_space()
	{
		while( $this->pos < $this->length && $this->is_space(substr($this->source,$this->pos,1) ) )
			$this->pos ++;
	}

	private function is_text($str)
	{
		if( strpos($this->source,$str,$this->pos) === $this->pos ) return true;
		return false;
	}

	private function read()
	{
		$token = null;
		if( $this->pos < $this->length )
		{
			if( $this->in_script )
			{
				$this->skip_space();
				if( $this->first )
				{
					if( $this->is_text("=="))
					{
						$token = new te_token(te_token::OUTPUT_EL,"==");
						$this->pos += 2;
						$this->first = false;
						return $token;
					}
					else if( $this->is_text("="))
					{
						$token = new te_token(te_token::OUTPUT,"=");
						$this->pos ++;
						$this->first = false;
						return $token;
					}
					else if( $this->is_text("#"))
					{
						$token = new te_token(te_token::FLOW,"#");
						$this->pos ++;
						$this->first = false;
						return $token;
					}
					else if( $this->is_text(">"))
					{
						$token = new te_token(te_token::BLOCK,">");
						$this->pos ++;
						$this->first = false;
						return $token;
					}
					else
					{
						//缺省当作output
						$token = new te_token(te_token::OUTPUT,"=");
						//$this->pos += 2;
						$this->first = false;
						return $token;
					}
				}
				else
				{
					if( $this->is_text("}}"))
					{
						$token = new te_token(te_token::RIGHT,"}}");
						$this->pos += 2;
						$this->in_script = false;
					}
					else
					{
						$ch = substr($this->source,$this->pos,1);
						if( $ch == "." )
						{
							
							$token = new te_token(te_token::DOT,".");
							$this->pos ++;
							
						}
						else if( $ch == ",")
						{
							$token = new te_token(te_token::COMMA,",");
							$this->pos ++;
						}
						else if( $ch == "(")
						{
							$token = new te_token(te_token::BRACKET_LEFT,"(");
							$this->pos ++;
						}
						else if( $ch == ")")
						{
							$token = new te_token(te_token::BRACKET_RIGHT,")");
							$this->pos ++;
						}
						else if( $ch == '"')
						{
							$pos = $this->pos + 1;
							while( $pos < $this->length)
							{
								$ch2 = substr($this->source,$pos,1);
								if( $ch2 == '"')
								{
									if( $pos + 1 < $this->length && substr($this->source,$pos+1,1) == '"')
									{
										$pos += 2;
									}
									else break;
								}
								else $pos ++;
							}
							if( $pos < $this->length && substr($this->source,$pos,1) == '"')
							{
								$token = new te_token(te_token::STRING,str_replace('""','"',substr($this->source,$this->pos+1,$pos-$this->pos-1)) );
								$this->pos = $pos + 1;
							}
							else bjerror("模板中的字符串不匹配".$this->get_rc($this->pos));
							
						}
						else if( $ch ==  "'")
						{
							$pos = $this->pos + 1;
							while( $pos < $this->length)
							{
								$ch2 = substr($this->source,$pos,1);
								if( $ch2 ==  "'")
								{
									if( $pos + 1 < $this->length && substr($this->source,$pos+1,1) ==  "'")
									{
										$pos += 2;
									}
									else break;
								}
								else $pos ++;
							}
							if( $pos < $this->length && substr($this->source,$pos,1) == "'")
							{
								$token = new te_token(te_token::STRING,str_replace("''","'",substr($this->source,$this->pos+1,$pos-$this->pos-1)) );
								$this->pos = $pos + 1;
							}
							else bjerror("模板中的字符串不匹配".$this->get_rc($this->pos));
							
						}
						else if( $ch == "+")
						{
							$token = new te_token(te_token::OP_ADD,"+");
							$this->pos ++;
						}
						else if( $ch == "-")
						{
							$token = new te_token(te_token::OP_SUB,"-");
							$this->pos ++;
						}
						else if( $ch == "*")
						{
							$token = new te_token(te_token::OP_MUL,"*");
							$this->pos ++;
						}
						else if( $ch == "/")
						{
							$token = new te_token(te_token::OP_DIV,"/");
							$this->pos ++;
						}
						else if( $ch == "%")
						{
							$token = new te_token(te_token::OP_MOD,"%");
							$this->pos ++;
						}
						else if( $ch == "&")
						{
							if( $this->is_text("&&"))
							{
								$token = new te_token(te_token::OP_AND,"&&");
								$this->pos += 2;
							}
							else
							{
								bjerror("模板中的位运算暂时不支持".$this->get_rc($this->pos));
							}
						}
						else if( $ch == "|")
						{
							if( $this->is_text("||"))
							{
								$token = new te_token(te_token::OP_OR,"||");
								$this->pos += 2;
							}
							else
							{
								bjerror("模板中的位运算暂时不支持".$this->get_rc($this->pos));
							}
						}
						else if( $ch == "!")
						{
							if( $this->is_text("!="))
							{
								$token = new te_token(te_token::OP_NE,"!=");
								$this->pos += 2;
							}
							else
							{
								$token = new te_token(te_token::OP_NOT,"!");
								$this->pos ++;
							}
						}
						else if( $ch == ">")
						{
							if( $this->is_text(">="))
							{
								$token = new te_token(te_token::OP_GE,">=");
								$this->pos += 2;
							}
							else
							{
								$token = new te_token(te_token::OP_GT,">");
								$this->pos ++;
							}
						}
						else if( $ch == "<")
						{
							if( $this->is_text("<="))
							{
								$token = new te_token(te_token::OP_LE,"<=");
								$this->pos += 2;
							}
							else
							{
								$token = new te_token(te_token::OP_LT,"<");
								$this->pos ++;
							}
						}
						else if( $ch == "=")
						{
							if( $this->is_text("=="))
							{
								$token = new te_token(te_token::OP_EQ,"==");
								$this->pos += 2;
							}
							else
							{
								$token = new te_token(te_token::OP_EQ,"==");
								$this->pos ++;
							}
						}
						else
						{
							if( $this->is_sep($ch)) bjerror("错误的模板语法在[{$ch}]附近".$this->get_rc($this->pos));
							$start = $this->pos;
							$isnum = true;
							$dotcount = 0;
							while(true)
							{
								$c = substr($this->source,$this->pos,1);
								if( $this->is_sep($c)) break;
								if( $isnum && !is_numeric($c) ) $isnum = false;
								if( $c == '.' ) ++ $dotcount;
								$this->pos ++;
							}
							if( $this->pos > $start )
							{
								$id = substr($this->source,$start,$this->pos-$start);
								if( $this->is_keyword($id) )
									$token = new te_token(te_token::KEYWORD,$id);
								else
								{
									if( $isnum ) 
									{
										if( $dotcount == 0 )
											$token = new te_token(te_token::NUM_INT,(int)$id);
										else if( $dotcount == 1 )
											$token = new te_token(te_token::NUM_FLOAT,(float)$id);
										else bjerror("错误的模板浮点数".$this->get_rc($this->pos));
									}
									else $token = new te_token(te_token::IDENTITY,$id);
								}
							}
							else bjerror("错误的模板标识符".$this->get_rc($this->pos));
							
						}
					}
				}
			}
			else
			{
				
				if( $this->is_text("{{!"))
				{
					$start = strpos($this->source,"!}}",$this->pos+3);
					if( $start === false ) bjerror("错误的js代码块，缺省结束符".$this->get_rc($this->pos));
					$token = new te_token(te_token::JS_CODE,substr($this->source,$this->pos+3,$start-$this->pos-3));
					$this->pos = $start+3;
				}
				else if( $this->is_text("{{~"))
				{
					$start = strpos($this->source,"~}}",$this->pos+3);
					if( $start === false ) bjerror("错误的css代码块，缺省结束符".$this->get_rc($this->pos));
					$token = new te_token(te_token::CSS_CODE,substr($this->source,$this->pos+3,$start-$this->pos-3));
					$this->pos = $start+3;
				}
				else if( $this->is_text("{{"))
				{
					$token = new te_token(te_token::LEFT,"{{");
					$this->pos += 2;
					$this->in_script = true;
					$this->first = true;
				}
				else
				{
					$begin = strpos($this->source,"{{",$this->pos);
					if( $begin === false )
					{
						//write_log('adding text:'.substr($this->source,$this->pos).' pos='.$this->pos);
						$token = new te_token(te_token::TEXT,substr($this->source,$this->pos));
						$this->pos = $this->length;
					}
					else
					{
						//write_log('begin:'.$begin.' pos:'.$this->pos);
						if( $begin > $this->pos )
						{
							$token = new te_token(te_token::TEXT,substr($this->source,$this->pos,$begin-$this->pos));
							$this->pos = $begin;
						}
						else bjerror("错误的模板语法.".$this->get_rc($this->pos));//不应该会跑到这里
					}
				}
			}
		}
		return $token;
	}

	private function is_keyword($str)
	{
		return in_array($str,$this->keywords);
	}

	/*private function debug_this()
	{
		$types=['','text','css_code','js_code','id','string','parent','grand','dot','comma',
			'left','right','output',
		'output_el','call','flow','block','keyword',
			'bracket_left','bracket_right'];
		write_log('lex结果：');
		$str=[];
		foreach($this->tokens as $token)
		{
			$s=$types[$token->type];
			if( $token->type != te_token::TEXT && $token->type != te_token::STRING &&
			$token->type != te_token::JS_CODE && $token->type != te_token::CSS_CODE)
				$s .= '['.$token->text.']';
			$str[] = $s;
		}
		write_log(implode("\r\n",$str));
	}*/
	private function get_rc($pos)
	{
		$line=1;
		$p=0;
		for($i=0;$i<$pos;$i++)
		{
			if( $this->source[$i] == "\n" ) {
				$line++;
				$p = $i;
			}
		}
		$col = $pos - $p;
		
		return "[行:$line,列:$col]";
	}
	
	public $tokens;

	private $pos = 0;
	private $length = 0;
	private $source = '';
	private $in_script = false;
	private $first = false;
	private $space = " \t\r\n";
	private $sep = " \t\r\n#<>{}[]()=+-*&^%$!~/\\:;.,'\"|";
	private $keywords =["if","else","end","each","eachr","with","unless"];
	
}

class node_base 
{
	const TEXT=0;
	const BLOCK=1;
	const JS_CODE=2;
	const CSS_CODE=3;
	const FLOW=4;
	const OUTPUT=5;

	public function get_type()
	{
		return 0;
	}
	/*public function debug_me()
	{
		return "[error]";
	}*/
	//for debug
	public function printExpr($expr)
	{
		if( $expr )
		{
			echo "expr:".$expr->type."[";
			if( $expr->type == expritem::EI_EXPR)
			{
				if( $expr->left ) echo "left:".$expr->left->type;
				if( $expr->right ) echo "|right:".$expr->right->type;
			}
			
			echo "]";
			echo "<br>";
		}
	}
}

class node_block extends node_base
{
	public function __construct()
	{
		$this->name = '';
	}
	
	public function get_type()
	{
		return node_base::BLOCK;
	}
	
	public function set_name($name)
	{
		$this->name = $name;
	}
	
	public function get_name()
	{
		return $this->name;
	}
	/*public function debug_me()
	{
		return "[block]".$this->name;
	}*/
	private $name;
}

class node_csscode extends node_base
{
	public function __construct()
	{
		$this->code = '';
	}
	
	public function get_type()
	{
		return node_base::CSS_CODE;
	}
	
	public function get_code()
	{
		return $this->code;
	}
	
	public function set_code( $code )
	{
		$this->code = $code;
	}
	/*public function debug_me()
	{
		return "[css_code]";
	}*/
	private $code;
}

class node_jscode extends node_base
{
	public function __construct()
	{
		$this->code = '';
	}
	
	public function get_type()
	{
		return node_base::JS_CODE;
	}
	
	public function get_code()
	{
		return $this->code;
	}
	
	public function set_code( $code )
	{
		$this->code = $code;
	}
	/*public function debug_me()
	{
		return "[js_code]";
	}*/
	private $code;
}

class node_flow extends node_base
{
	public function __construct()
	{
	}
	
	public function get_type()
	{
		return node_base::FLOW;
	}
	
	public function set_name($name)
	{
		$this->name = $name;
	}
	public function get_name()
	{
		return $this->name;
	}
	
	public function get_expr()
	{
		return $this->expr;
	}
	
	public function set_expr($expr)
	{
		$this->expr = $expr;
	}
	
	public function is_flow($name)
	{
		return $this->name == $name;
	}
	/*public function debug_me()
	{
		return "#[".$this->name."]".($this->arglist != null ? $this->arglist->debug_me():'');
	}*/
	private $name;
	private $expr;
}

class node_text extends node_base
{
	public function __construct()
	{
	}
	
	public function get_type()
	{
		return node_base::TEXT;
	}
	
	public function get_text()
	{
		return $this->text;
	}
	
	public function set_text($text)
	{
		$this->text = $text;
	}
	/*public function debug_me()
	{
		return "[text]".substr($this->text,0,5)."...[/text]";
	}*/
	private $text;
}

class node_output extends node_base
{
	public function __construct()
	{
		$this->unesc = false;
	}
	
	public function get_type()
	{
		return node_base::OUTPUT;
	}
	
	public function get_expr()
	{
		return $this->expr;
	}
	public function set_expr($expr)
	{
		$this->expr = $expr;
	}
	public function get_unesc()
	{
		return $this->unesc;
	}
	public function set_unesc($b)
	{
		$this->unesc = $b;
	}
	/*public function debug_me()
	{
		$str = "\r\n[output".($this->unesc ? '_el':'')."]";
		if( $this->arglist != null ) $str .= $this->arglist->debug_me();
		$str .= "[/output]\r\n";
		return $str;
	}*/
	private $expr;
	private $unesc;
}

class expritem
{
	const EI_EMPTY=0;
	const EI_CONST = 1;
	const EI_IDEN = 2;
	const EI_EXPR=3;

	public function __construct($type,$text)
	{
		$this->type = $type;
		$this->text = $text;
	}
	
	public $type;
	public $text;
	public $left;
	public $right;
}

class expr_builder
{
	public function do_stack($S)
	{
		//for debug
		/*echo "do_stack-- <br>";
		foreach($S as $s)
		{
			echo "t:".$s->type." ".$s->text."|";
		}
		echo "<br>--source tokens end<br>";*/
		$R = [];
		$Y = [];
		$Y[] = new te_token(0,"");
		$count = count($S);
		for($i=0;$i<$count;$i++)
		{
			$t = $S[ $i ];
			$type = $t->type;
			if( in_array($type,[te_token::STRING,te_token::NUM_INT,te_token::NUM_FLOAT,te_token::IDENTITY]) )
			{
				$R[] = $t;
				if( $type == te_token::IDENTITY && $i < $count - 1 && $S[ $i + 1]->type == te_token::BRACKET_LEFT )
				{
					
					$Y[] = new te_token(te_token::BRACKET_LEFT,"(*");
					$i++;
					
				}
				
			}
			else if( $type == te_token::BRACKET_RIGHT )
			{
				$this->Y2R($Y,$R,te_token::BRACKET_LEFT);
			}
			else if( te_token::is_oper($type) )
			{
				if( $type == te_token::OP_NOT ) $R[] = new te_token(0,"");
				//echo " oper:".$type;
				while(true)
				{
					$back = $Y[ count($Y) - 1 ];
					//echo " checking:".$back->type." ".$back->text;
					if( $back->type == 0 || $back->type == te_token::BRACKET_LEFT  )
						break;
					if( te_token::get_priority($back->type) >= te_token::get_priority($type) )
					{
						//echo " add ";
						$R[] = $back;
						array_pop($Y);
					}
					else break;
				}

				if( $type == te_token::BRACKET_LEFT && $i < $count - 1 && $S[$i+1]->type == te_token::BRACKET_RIGHT )
				{
					$R[] = new te_token(0,"");
				}
				else
					$Y[] = $t;
			}
			else bjerror("不期望的语法单元:".substr($t->text,0,32));
		}

		while( true )
		{
			$token = array_pop($Y);
			if( $token->type == 0 ) break;
			$R[] = $token;
		}
		
		//for debug
		/*echo "after do_stack-- <br>";
		foreach($R as $s)
		{
			echo "t:".$s->type." ".$s->text."|";
		}
		echo "<br>--<br>";*/
		//exit;
		
		return $R;
	}

	public function Y2R(&$Y,&$R,$te)
	{
		while(true)
		{
			$token = array_pop($Y);
			if( $token->type == 0 || ($token->type == te_token::BRACKET_LEFT && $token->type != $te) )
			{
				//for debug
				/*foreach($R as $r)
				{
					echo "r:".$r->type." r.text:".$r->text."<br>";
				}
				echo "模块中表达式括号不匹配!"; exit;*/
				bjerror("模块中表达式括号不匹配 ".$token->type);
			}
			$R[] = $token;
			if( $token->type == te_token::BRACKET_LEFT ) {
				//if( $token->text == "(*") $R[] = $token;
				break;
			}
		}
	}

	public function do_tree(&$R)
	{
		/*echo "enter do_tree<br>";
		foreach($R as $r)
		{
			echo "token:".$r->type." ".$r->text."<br>";
		}
		echo "<br>"; //exit;
		*/
		$T=[];
		$count = count($R);
		for($i=0;$i<$count;$i++)
		{
			$token = $R[ $i ];
			$type = $token->type;
			if( in_array($type,[te_token::STRING,te_token::NUM_INT,te_token::NUM_FLOAT]) )
			{
				$T[] = new expritem(expritem::EI_CONST,$token->text);
			}
			else if( $type == te_token::IDENTITY )
			{
				$T[] = new expritem(expritem::EI_IDEN,$token->text);
			}
			else if( $type == 0 )
			{
				$T[] = new expritem(expritem::EI_EMPTY,"");
			}
			else
			{
				//echo ">>> oper :".$token->type." ".$token->text."<br>";
				$r = null;
				$l = null;
				if( count($T) > 0 ) $r = array_pop($T);
				else
					bjerror("缺少右操作数.");
				if( count($T) > 0 ) $l = array_pop($T);
				else {
					//exit;
					bjerror("缺少左操作数.");
				}
				if( $type != te_token::OP_NOT && $l->type == expritem::EI_EMPTY )
					bjerror("缺少左操作数");
				if( $r->type == expritem::EI_EMPTY )
					bjerror("缺少右操作数");

				$expr = new expritem(expritem::EI_EXPR,$type);
				$expr->left = $l;
				$expr->right = $r;
				$T[] = $expr;

			}
		}

		$count_T = count($T);
		if( $count_T > 1 )
		{
			$last = $T[ $count_T - 1 ];
			//echo "missing oper:".$last->type." ".$last->text."<br>"; exit;
			bjerror("缺少操作数");
		}
		if( $count_T == 1 ) return $T[ 0 ];
		else return null;
	}

	public function get_list($ei)
	{
		if( $ei->type == expritem::EI_EXPR && $ei->text == te_token::COMMA )
		{
			$arr = $this->get_list($ei->left);
			$arr[] = $ei->right;
			return $arr;
		}
		else return [$ei];
	}
}



class parser
{
	public function __construct()
	{
		$this->tokens = [];
	}
	
	public function execute($tokens)
	{
		//write_log('parser: tokens count='.count($tokens));
		
		$this->tokens = $tokens;
		$this->pos = 0;
		$this->length = count($tokens);
		
		$items = [];
		while(true)
		{
			$p = $this->scan_text();
			if( $p == null ) $p = $this->scan_flow();
			if( $p == null ) $p = $this->scan_jscode();
			if( $p == null ) $p = $this->scan_csscode();
			if( $p == null ) $p = $this->scan_block();
			if( $p == null ) $p = $this->scan_output();

			if( $p != null )
			{
				$items[] = $p;
			}
			else break;
		}
		
		return $items;
	}
	
	public function scan_block()
	{
		$token = $this->expect(te_token::LEFT);
		if( $token == null ) return null;
		
		$token = $this->expect(te_token::BLOCK);
		if( $token == null )
		{
			$this->back(1);
			return null;
		}
		$b = new node_block();
		$token = $this->need(te_token::IDENTITY);
		$b->set_name($token->text);
		$this->need(te_token::RIGHT);
		return $b;
		
	}
	
	public function scan_output()
	{
		$token = $this->expect(te_token::LEFT);
		if( $token == null ) return null;
		
		$token = $this->expect(te_token::OUTPUT,te_token::OUTPUT_EL);
		if( $token == null )
		{
			$this->back(1);
			return null;
		}
		
		$b = new node_output();
		if( $token->is_type(te_token::OUTPUT_EL)) $b->set_unesc(true);

		$b->set_expr( $this->scan_expr() );
		$this->need(te_token::RIGHT);
		return $b;
	}

	public function scan_flow()
	{
		$token = $this->expect(te_token::LEFT);
		if( $token == null ) return null;
		
		$token = $this->expect(te_token::FLOW);
		if( $token == null )
		{
			$this->back(1);
			return null;
		}
		
		$flow = new node_flow();
		$token = $this->need(te_token::KEYWORD);
		$flow->set_name($token->text);
		
		$need_expr = in_array($token->text,["if","each","eachr","with","unless"]);
		if( $need_expr )
		{
			$expr = $this->scan_expr();
			if( $expr->type == expritem::EI_EMPTY ) bjerror("模板的流程指令缺少表达式");
			$flow->set_expr( $expr );
		}
		$this->need(te_token::RIGHT);
		
		return $flow;
	}
	public function scan_text()
	{
		//write_log('scan_text 1');
		$token = $this->expect(te_token::TEXT);
		if( $token == null ) return null;
		//write_log('scan_text 2');
		$text = new node_text();
		$text->set_text( $token->text );
		return $text;
	}
	public function scan_jscode()
	{
		$token = $this->expect(te_token::JS_CODE);
		if( $token == null ) return null;
		
		$js = new node_jscode();
		$js->set_code( $token->text );
		return $js;
	}
	public function scan_csscode()
	{
		$token = $this->expect(te_token::CSS_CODE);
		if( $token == null ) return null;
		
		$css = new node_csscode();
		$css->set_code( $token->text );
		return $css;
	}
	
	private function scan_expr()
	{
		$tokens = [];
		while(true)
		{
			$token = $this->read();
			if( $token->type == te_token::RIGHT )
			{
				$this->back(1);
				break;
			}

			$tokens[] = $token;
		}
		$ep = new expr_builder();
		$stacks = $ep->do_stack($tokens);
		return $ep->do_tree($stacks);
	}
	
	private function read()
	{
		if( $this->pos < $this->length ) return $this->tokens[ $this->pos ++ ];
		return null;
	}
	
	private function back($count)
	{
		$this->pos -= $count;
	}
	
	private function need()
	{
		$args = func_get_args();
		$count = func_num_args();
		
		$token = null;
		for($i=0;$i<$count;$i++)
		{
			$token = $this->read();
			if( $token == null || ! $token->is_type($args[$i]) )
			{
				$near='';
				if( $this->pos-1 >= 0 ) $near = $this->tokens[ $this->pos - 1 ]->text;
				bjerror("在[{$near}]附近模板语法错误");
			}
		}
		return $token;
	}
	
	
	private function expect()
	{
		$args = func_get_args();
		$count = func_num_args();
		$token = $this->read();
		if( $token == null ) return null;
		for($i=0;$i<$count;$i++)
		{
			if( $token->is_type($args[$i]) ) return $token;
		}
		$this->back( 1 );
		return null;
	}
	
	
	
	private $pos;
	private $length;
	private $tokens;
}

class syn_base
{
	const SYN_TEXT = 1;
	const SYN_JS = 2;
	const SYN_CSS = 3;
	const SYN_BLOCK = 4;
	const SYN_OUTPUT = 5;
	const SYN_IF = 6;
	const SYN_ELSE = 7;
	const SYN_UNLESS = 8;
	const SYN_WITH = 9;
	const SYN_EACH = 10;
	const SYN_EACHR = 11;
}

class syn_text extends syn_base
{
	public function get_type(){ return syn_base::SYN_TEXT; }

	public $text;
}

class syn_js extends syn_base
{
	public function get_type(){ return syn_base::SYN_JS; }

	public $text;
}

class syn_css extends syn_base
{
	public function get_type(){ return syn_base::SYN_CSS; }

	public $text;
}

class syn_block extends syn_base
{
	public function get_type(){ return syn_base::SYN_BLOCK; }

	public $name;
}

class syn_output extends syn_base
{
	public function get_type(){ return syn_base::SYN_OUTPUT; }

	public $unesc;
	public $expr;
}

class syn_if extends syn_base
{
	public function get_type(){ return syn_base::SYN_IF; }

	public $expr;
	public $body=[];
	public $_else;
}

class syn_else extends syn_base
{
	public function get_type(){ return syn_base::SYN_ELSE; }

	public $body=[];
}

class syn_with extends syn_base
{
	public function get_type(){ return syn_base::SYN_WITH; }

	public $expr;
	public $body=[];
}

class syn_unless extends syn_if
{
	public function get_type(){ return syn_base::SYN_UNLESS; }
}

class syn_each extends syn_base
{
	public function get_type(){ return syn_base::SYN_EACH; }

	public $expr;
	public $body=[];
	public $_else;
}

class syn_eachr extends syn_each
{
	public function get_type(){ return syn_base::SYN_EACHR; }
}

class analyse
{
	public function execute($items)
	{
		$root = new syn_else();

		$nodes=[$root];
		$flags=[false];//is else
		
		foreach($items as $base)
		{
			$is_else = $flags[ count($flags)-1 ];
			$cur = $nodes[ count($nodes) - 1 ];

			$add = function($syn,$is_else,$cur){
				//echo "<br>-->adding:output ".($is_else ? "else":"")." cur:".$cur->get_type()."<br>";
				//var_dump($syn);
				if( $is_else )	$cur->_else->body[] = $syn;
				else $cur->body[] = $syn;
			};

			switch($base->get_type())
			{
				case node_base::TEXT:
				
					$syn = new syn_text();
					$syn->text = $base->get_text();
					$add($syn,$is_else,$cur);
				
				break;
				case node_base::BLOCK:
				
					$syn = new syn_block();
					$syn->name = $base->get_name();
					$add($syn,$is_else,$cur);
				
				break;
				case node_base::JS_CODE:
				
					$syn = new syn_js();
					$syn->text = $base->get_code();
					$add($syn,$is_else,$cur);
				
				break;
				case node_base::CSS_CODE:
				{
					$syn = new syn_css();
					$syn->text = $base->get_code();
					$add($syn,$is_else,$cur);
				}
				break;
				case node_base::OUTPUT:
				{
					$syn = new syn_output();
					$syn->expr = $base->get_expr();
					$syn->unesc = $base->get_unesc();
					$add($syn,$is_else,$cur);
					
					
				}
				break;
				case node_base::FLOW:
					
					
					if( in_array($base->get_name(),["if","unless","with","each","eachr"]))
					{
						$syn = null;
						if( $base->is_flow("if") ) $syn = new syn_if();
						else if( $base->is_flow("unless") ) $syn = new syn_unless();
						else if( $base->is_flow("with") ) $syn = new syn_with();
						else if( $base->is_flow("each") ) $syn = new syn_each();
						else if( $base->is_flow("eachr") ) $syn = new syn_eachr();
						$syn->expr = $base->get_expr();
						$add($syn,$is_else,$cur);

						$nodes[] = $syn;
						$flags[] = false;
					}
					else if( $base->is_flow("end"))
					{
						if( count($flags) < 1 || count($nodes) < 1 )
							bjerror("模板中有多余的结束指令");
						array_pop( $nodes );
						array_pop( $flags );
					}
					else if( $base->is_flow("else"))
					{
						array_pop( $flags );
						$flags[] = true;
					}
		
					break;
				
				
				default:
					bjerror("错误的模板语法:".$cur->get_type());
					break;
			}
			
		}
		//echo " body count:".count($root->body);
		return $root->body;
	}
}

class codes
{
	private $codes=[];

	public function add($codes)
	{
		if( is_array($codes) ) 
		{
			foreach($codes as $c) $this->codes[] = $c;
		}
		else $this->codes[] = $codes;
	}

	public function get_source()
	{
		return implode("",$this->codes);
	}
}

class compiler
{
	private $_varindex=0;
	private function get_tempvar()
	{
		++ $this->_varindex;
		return '$'."v" . $this->_varindex;
	}

	public function __construct()
	{
		$this->callback= [];
		$this->func=[];
		$this->context_stack=[];
		$this->index_stack=[];
		$this->each_stack=[];
		$this->key_stack=[];
	}

	public function execute($body,$codes)
	{
		$this->gen_body($body,$codes);
	}

	private function gen_syn($syn,$codes)
	{
		$type = $syn->get_type();
		switch($type)
		{
		case syn_base::SYN_TEXT: $this->gen_text($syn,$codes); break;
		case syn_base::SYN_JS: $this->gen_js($syn,$codes); break;
		case syn_base::SYN_CSS: $this->gen_css($syn,$codes); break;
		case syn_base::SYN_BLOCK: $this->gen_block($syn,$codes); break;
		case syn_base::SYN_OUTPUT: $this->gen_output($syn,$codes); break;
		case syn_base::SYN_IF: $this->gen_if($syn,$codes); break;
		case syn_base::SYN_ELSE: $this->gen_else($syn,$codes); break;
		case syn_base::SYN_UNLESS: $this->gen_unless($syn,$codes); break;
		case syn_base::SYN_WITH: $this->gen_with($syn,$codes); break;
		case syn_base::SYN_EACH: $this->gen_each($syn,$codes); break;
		case syn_base::SYN_EACHR: $this->gen_eachr($syn,$codes); break;
		}
	}

	private function gen_body($body,$codes)
	{
		foreach($body as $b)
		{
			$this->gen_syn($b,$codes);
		}
	}

	private function gen_text($syn,$codes)
	{
		$codes->add("\r\n\t\t$"."this->do_html(\"" . $this->code_text($syn->text) . "\");");
	}
	private function gen_js($syn,$codes)
	{
		$codes->add("\r\n\t\t$"."this->do_jscode(\"" . $this->code_text($syn->text) . "\");");
	}
	private function gen_css($syn,$codes)
	{
		$codes->add("\r\n\t\t$"."this->do_csscode(\"" . $this->code_text($syn->text) . "\");");
	}
	private function gen_block($syn,$codes)
	{
		$codes->add("\r\n\t\t$"."this->do_block(\"" . $this->code_text($syn->name) . "\");");
	}
	private function gen_output($syn,$codes)
	{
		$v = $this->gen_expr($syn->expr,$codes);
		$codes->add("\r\n\t\t$"."this->do_html(");
		if( !$syn->unesc ) $codes->add( '$'."this->encode(");
		$codes->add( $v  );
		if( !$syn->unesc ) $codes->add(")");
		$codes->add( ");");
	}
	private function gen_expr($expr,$codes)//返回临时变量名
	{
		$v = $this->get_tempvar();
		$codes->add("\r\n\t\t".$v."=( ".$this->gen_expr_code($expr)." );");
		//echo "<br>after gen expr!"; exit;
		return $v;
	}
	
	private function gen_expr_code($expr)
	{
		//echo "gen expr code:".$expr->type;
		if( $expr != null &&  $expr->type != expritem::EI_EMPTY ) 
		{
			if( $expr->type == expritem::EI_CONST )
			{
				$val = $expr->text;
				if( is_string( $val ) )
				{
					$val = $this->code_text( $val );
					return "\"".$val."\"";
				}
				return (string)$val;
			}
			else if( $expr->type == expritem::EI_IDEN )
			{
				return "$"."this->get_prop($"."this->_this,\"".$this->code_text($expr->text)."\")";
			}
			else if( $expr->type == expritem::EI_EXPR )
			{
				//echo " ".$expr->text;
				$op = $expr->text;
				if( $op == te_token::OP_NOT )
				{
					return "! (".$this->gen_expr_code($expr->right).")";
				}
				else if( in_array($op,[te_token::OP_AND,te_token::OP_OR,te_token::OP_ADD,te_token::OP_SUB
					,te_token::OP_MUL,te_token::OP_DIV,te_token::OP_MOD
					,te_token::OP_GT,te_token::OP_GE,te_token::OP_LT
					,te_token::OP_LE,te_token::OP_NE,te_token::OP_EQ]) )
				{
					//php中 字符串相加用 "."，所以要特殊处理下
					if( $op == te_token::OP_ADD )
					{
						return '$'."this->_funcs[\"add\"](".
							$this->gen_expr_code($expr->left).
							",".
							$this->gen_expr_code($expr->right).
							")";
					}
					else return '('.$this->gen_expr_code($expr->left).') '.te_token::op_str($op).' ('.$this->gen_expr_code($expr->right).')';
				}
				else if( $op == te_token::BRACKET_LEFT )
				{
					$str = "$"."this->_funcs[\"".$expr->left->text."\"](";
					if( !$expr->right == null || $expr->right->type == expritem::EI_EMPTY )
						$str .= $this->gen_expr_code($expr->right);
					$str .= ")";
					return $str;
				}
				else if( $op == te_token::COMMA )
				{
					return $this->gen_expr_code($expr->left).','.$this->gen_expr_code($expr->right);
				}
				else if( $op == te_token::DOT )
				{
					$lstr = $this->gen_expr_code($expr->left);
					return "$"."this->get_prop(".$lstr.",\"".$this->code_text($expr->right->text)."\")";
				}
			}
		}
	
		return "null";
	}

	private function gen_if($syn,$codes)
	{
		//echo "gen if!!"; exit;
		$v = $this->gen_expr($syn->expr,$codes);
		$codes->add("\r\n\t\tif( ".'$'."this->is_true(" . $v .") ){");
		$this->gen_body($syn->body,$codes);
		$codes->add("\r\n\t\t}");
		if( $syn->_else ) $this->gen_else($syn->_else,$codes );
	}
	private function gen_unless($syn,$codes)
	{
		$v = $this->gen_expr($syn->expr,$codes);
		$codes->add("\r\n\t\tif( !".'$'."this->is_true(" . $v .") ){");
		$this->gen_body($syn->body,$codes);
		$codes->add("\r\n\t\t}");
		if( $syn->_else ) $this->gen_else($syn->_else,$codes );
	}
	private function gen_else($syn,$codes)
	{
		//echo  " gen else "; exit;
		$codes->add("\r\n\t\telse{");
		$this->gen_body($syn->body,$codes);
		$codes->add("\r\n\t\t}");
	}
	private function gen_with($syn,$codes)
	{
		$v = $this->gen_expr($syn->expr,$codes);

		$codes->add("\r\n\t\t".'$'."this->_this_stack[] = ".'$'."this->_this;");
		$codes->add("\r\n\t\t".'$'."this->_this=".$v.";");

		$this->gen_body($syn->body,$codes);

		$codes->add("\r\n\t\t$"."this->_this=array_pop($"."this->_this_stack);");
	}
	private function gen_each($syn,$codes)
	{
		$this->_gen_each($syn,false,$codes);
	}
	private function gen_eachr($syn,$codes)
	{
		$this->_gen_each($syn,true,$codes);
	}
	private function _gen_each($syn,$rev,$codes)
	{
		$v = $this->gen_expr($syn->expr,$codes);
		if( $rev )
		{
			$codes->add("\r\n\t\t".$v."=array_reverse(".$v.")");
		}

		$codes->add("\r\n\t\tif( $"."this->can_each(".$v.") ){");

		$tmp_k = $this->get_tempvar();
		$tmp_v = $this->get_tempvar();
		$tmp_eachobj = $this->get_tempvar();

		$codes->add("\r\n\t\t$"."this->_index_stack[] = $"."this->_index;");
		$codes->add("\r\n\t\t$"."this->_index=0;");

		$codes->add("\r\n\t\t$"."this->_key_stack[] = $"."this->_key;");
		$codes->add("\r\n\t\t$"."this->_key=null;");

		$codes->add("\r\n\t\t".$tmp_eachobj."=".'$'."this->_eachobj;");
		$codes->add("\r\n\t\tforeach(".$v." as ".$tmp_k."=>".$tmp_v."){");
		$codes->add("\r\n\t\t\t".'$'."this->_this_stack[] = ".'$'."this->_this;");
		$codes->add("\r\n\t\t\t".'$'."this->_this=".$tmp_v.";");
		$codes->add("\r\n\t\t\t$"."this->_key=".$tmp_k.";");
		

		$this->gen_body($syn->body,$codes);

		$codes->add("\r\n\t\t\t$"."this->_this=array_pop($"."this->_this_stack);");
		$codes->add("\r\n\t\t\t$"."this->_index++;");

		$codes->add("\r\n\t\t\t}");

		$codes->add("\r\n\t\t$"."this->_index=array_pop($"."this->_index_stack);");
		$codes->add("\r\n\t\t$"."this->_key=array_pop($"."this->_key_stack);");
		$codes->add("\r\n\t\t$"."this->_eachobj=".$tmp_eachobj.";");

		$codes->add("\r\n\t\t}");
		if( $syn->_else )
		{
			$this->gen_else($syn->_else,$codes);
		}
	}
	private function code_text($text)
	{
		$s = str_replace("\\","\\\\",$text);
		$s = str_replace("\"","\\\"",$s);
		$s = str_replace("\r","\\r",$s);
		$s = str_replace("\n","\\n",$s);
		$s = str_replace("\t","\\t",$s);
		
		$pos = strpos($s,'$');
		if( $pos !== false )
		{
			$arr=[];
			while($pos !== false)
			{
				$arr[] = substr($s,0,$pos);
				$arr[] = "\".'$'.\"";
				$s = substr($s,$pos+1);
				$pos = strpos($s,'$');
			}
			$arr[] = $s;
			$s = implode("",$arr);
		}
		return $s;
	}
}

class Engine
{
	public function compile($source,$cls)
	{
		$lex = new Lex();
		$lex->execute($source);
		$tokens = $lex->get_tokens();
		
		/*foreach($tokens as $t)
		{
			echo "token:".$t->type." ".$t->text."<br>";
		}
		exit();*/
		
		$parser = new parser();
		$items = $parser->execute($tokens);
		
		/*foreach($items as $item)
		{
			echo "item:".$item->get_type();
			if( $item->get_type() == node_base::OUTPUT ) $item->printExpr($item->get_expr());
			if( $item->get_type() == node_base::FLOW) echo " ".$item->get_name();
			echo "<br>";
			
		}
		exit;*/
		
		$analyse = new analyse();
		$body = $analyse->execute($items);
		
		//var_dump($body); exit;

		$compiler = new compiler();
		$codes = new codes();
		
		$compiler->execute($body,$codes);

		$compile_code = $codes->get_source();

		$str = "<"."?"."php\r\n";
		$str .= "bjload(\"bjphp.vendor.ui.CachePage\");\r\n";
		$str .= "class $cls extends \\bjphp\\vendor\\ui\\CachePage\r\n{\r\n";
		$str .= "\tpublic function run($"."uicontext)\r\n";
		$str .= "\t{\r\n";
		$str .= "\t\t$"."this->_root =$"."uicontext;\r\n";
		$str .= "\t\t$"."this->_this =$"."this->_root;\r\n";
		$str .= "\t\t".$compile_code."\r\n";
		$str .= "\t}\r\n";
		$str .= "}\r\n";
		return $str;
	}
}


