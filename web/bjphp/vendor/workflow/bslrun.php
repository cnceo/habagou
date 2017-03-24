<?php
/**
 * http://www.baijienet.com
 * User: sint
 * Date: 2016/11/22
 * Time: 18:26
 */
bjload('vendor.workflow.bslc');

class app_status
{
	const S_NORMAL = 0;
	const S_HALT = 1;
	const S_RETURN = 2;
	const S_BREAK = 3;
	const S_CONTINUE = 4;
}

class expr_context
{
	public $task;
	public $line_index;
}
class vendor_workflow_bslrun
{
	public $appmgr;
	public $extfuncmgr;
	public $taskmgr;
	public $compiler;
	public $uirender;
	
	public $symt;
	public $cs;
	public $op_func = [];
	public $block_func = [];
	public $proc_run = [];
	
	public $status;
	public $appret;
	
	public function __construct($appm, $extm, $taskm,$uirender)
	{
		$this->symt = new _bsl_stack();
		$this->cs = new _bsl_stack();
		$this->status = app_status::S_NORMAL;
		
		$this->appmgr = $appm;
		$this->extfuncmgr = $extm;
		$this->taskmgr = $taskm;
		$this->uirender = $uirender;
		$this->compiler = bjcreate("vendor.workflow.bslc");
		$this->init();
	}
	
	//启动流程

	private function init()
	{
		$this->op_func[_bsl_type::OADD] = function ($f, $ec, $a, $b) {
			return $f($ec,$a) + $f($ec,$b);
		};
		$this->op_func[_bsl_type::OSUB] = function ($f, $ec,$a, $b) {
			return $f($ec,$a) - $f($ec,$b);
		};
		$this->op_func[_bsl_type::OMUL] = function ($f, $ec,$a, $b) {
			return $f($ec,$a) * $f($ec,$b);
		};
		$this->op_func[_bsl_type::ODIV] = function ($f, $ec,$a, $b) {
			return $f($ec,$a) / $f($ec,$b);
		};
		$this->op_func[_bsl_type::OMOD] = function ($f, $ec,$a, $b) {
			return $f($ec,$a) % $f($ec,$b);
		};
		$this->op_func[_bsl_type::OAND] = function ($f, $ec,$a, $b) {
			return $f($ec,$a) && $f($ec,$b);
		};
		$this->op_func[_bsl_type::OOR] = function ($f, $ec,$a, $b) {
			return $f($ec,$a) || $f($ec,$b);
		};
		$this->op_func[_bsl_type::ONOT] = function ($f, $ec,$a, $b) {
			return !$f($ec,$b);
		};
		$this->op_func[_bsl_type::OLONG] = function ($f, $ec,$a, $b) {
			return (int)$f($ec,$b);
		};
		$this->op_func[_bsl_type::OSTRING] = function ($f, $ec,$a, $b) {
			return (string)$f($ec,$b);
		};
		$this->op_func[_bsl_type::OFLOAT] = function ($f, $ec,$a, $b) {
			return (float)$f($ec,$b);
		};
		$this->op_func[_bsl_type::OLT] = function ($f, $ec,$a, $b) {
			return $f($ec,$a) < $f($ec,$b);
		};
		$this->op_func[_bsl_type::OLE] = function ($f, $ec,$a, $b) {
			return $f($ec,$a) <= $f($ec,$b);
		};
		$this->op_func[_bsl_type::OGT] = function ($f, $ec,$a, $b) {
			return $f($ec,$a) > $f($ec,$b);
		};
		$this->op_func[_bsl_type::OGE] = function ($f, $ec,$a, $b) {
			return $f($ec,$a) >= $f($ec,$b);
		};
		$this->op_func[_bsl_type::OEQ] = function ($f, $ec,$a, $b) {
			return $f($ec,$a) == $f($ec,$b);
		};
		$this->op_func[_bsl_type::ONE] = function ($f, $ec,$a, $b) {
			return $f($ec,$a) != $f($ec,$b);
		};
		$this->op_func[_bsl_type::OASS] = function ($f, $ec,$a, $b) {
			$value = $f($ec,$b);
			$task = $ec->task;
			
			if (is_array($a)) {
				$op = $a[1];
				if ($op == _bsl_type::SDOT) {
					$left = $f($ec,$a[0]);
					if (is_object($left)) {
						$left->{$a[2]} = $value;
					} else {
						$this->report_error($task,$task->get_app()->lines->lines[$a[0]->lindex]->row,"非法左值");
					}
				}
				if ($op == _bsl_type::SLSB) {
					$left = $f($ec,$a[0]);
					if (is_array($left)) $left[$a[2]] = $value;
				} else {
					//report error
				}
			} else {
				$var_name = $a->str;
				$count = $this->symt->length();
				for ($i = $count - 1; $i >= 0; $i--) {
					$sym = $this->symt->peek($i);
					/* @var $sym _bsl_sym_level */
					if ($sym->has($var_name)) {
						$sym->set($var_name, $value);
						return $value;
					}
				}
				$app = $task->get_app();
				/* @var $app _bslapp */
				
				$varcount = count($app->vars);
				for ($i = 0; $i < $varcount; $i++) {
					$var = $app->vars[$i];
					if ($var->name == $var_name) {
						$task->set_val($var_name, $value);
						return $value;
					}
				}
				bjerror("变量({$var_name})未定义");
			}
			return $value;
		};
		$this->op_func[_bsl_type::SLB] = function ($f, $ec,$a, $b) {
			
		};
		$this->op_func[_bsl_type::SLSB] = function ($f, $ec,$a, $b) {
			
		};
		$this->op_func[_bsl_type::SLCB] = function ($f, $ec,$a, $b) {
			
		};
		
		$this->block_func[_bsl_type::KIF] = function ($task,$if) {
			$ec = new expr_context();
			$ec->task = $task;
			$ec->line_index = $task->get_app()->lines->lines[$if->lindex]->row;
			$value = $this->block_func[_bsl_type::RUN_EXPR]($ec,$if->get_expr());
			
			$app = $task->get_app();
			
			if( $value )
			{
				$body = $if->get_body( $app );
				$this->block_func[_bsl_type::RUN_BODY]($task,$body);
			}
			else
			{
				$elseifs = $if->get_elseifs($app);
				$match = false;
				if( count($elseifs) )
				{
					foreach($elseifs as $el)
					{
						$value = $this->block_func[_bsl_type::RUN_EXPR]($ec,$el->get_expr());
						if( $value )
						{
							$match = true;
							$body = $el->get_body( $app );
							$this->block_func[_bsl_type::RUN_BODY]($task,$body);
							break;
						}
					}
				}
				if( !$match )
				{
					$else = $if->get_else($app);
					if( $else )
					{
						$body = $else->get_body( $app );
						$this->block_func[_bsl_type::RUN_BODY]($task,$body);
					}
				}
			}
		};
		$this->block_func[_bsl_type::KEACH] = function ($task,$each) {
			$ec = new expr_context();
			$ec->task = $task;
			$ec->line_index = $task->get_app()->lines->lines[$each->lindex]->row;
			$value = $this->block_func[_bsl_type::RUN_EXPR]($ec,$each->get_expr());
			
			$frame = new _bsl_frame();
			$frame->is_loop = true;
			$frame->is_each = true;
			$frame->each_obj = $value;
			$frame->each_index = 0;
			$this->cs->push( $frame );
			
			foreach($value as $k=>$v)
			{
				$frame->each_key = $k;
				$frame->each_value = $v;
				
				$body = $each->get_body( $task->get_app() );
				$this->block_func[_bsl_type::RUN_BODY]($task,$body);
				
				$frame->each_index++;
				
				if( $this->status == app_status::S_HALT || $this->status == app_status::S_RETURN )
				{
					break;
				}
					
				if( $this->status == app_status::S_BREAK ) {
					$this->status = app_status::S_NORMAL;
					break;
				}
				if( $this->status == app_status::S_CONTINUE) $this->status = app_status::S_NORMAL;
			}
			
			$this->cs->pop();
		};
		$this->block_func[_bsl_type::KWHILE] = function ($task,$while) {
			$ec = new expr_context();
			$ec->task = $task;
			$ec->line_index = $task->get_app()->lines->lines[$while->lindex]->row;
			
			
			while( true )
			{
				$value = $this->block_func[_bsl_type::RUN_EXPR]($ec,$while->get_expr());
				if( !$value ) break;
				
				$body = $while->get_body( $task->get_app() );
				$this->block_func[_bsl_type::RUN_BODY]($task,$body);
				
				if( $this->status == app_status::S_HALT || $this->status == app_status::S_RETURN )
					break ;
				if( $this->status == app_status::S_BREAK ) {
					$this->status = app_status::S_NORMAL;
					break;
				}
				if( $this->status == app_status::S_CONTINUE) $this->status = app_status::S_NORMAL;
			}
			
		};
		$this->block_func[_bsl_type::RUN_BODY] = function ($task,$body) {//for frameblock
			$sym = new _bsl_sym_level();
			
			$frame = new _bsl_frame();
			
			$frame->is_func = false;
			
			//init
			$this->cs->push($frame);
			$this->symt->push($sym);
			
			//exec body
			$app = $task->get_app();
			$ts = $body[0];
			$start = $body[1];
			$len = $body[2];
			for ($i = 0;$i<$len;) {
				$line = $app->lines[$i+$start];
				$node = $line->to_node();
				if( isset($this->block_func[$node->k]) )
				{
					$f = $this->block_func[$node->k];
					$f($task,$node);
					
					$next = $node->get_nextline($app);
					$i = $next - $start;
				}
				else
				{
					$ec = new expr_context();
					$ec->task = $task;
					$ec->line_index = $i + $start;
					$this->block_func[_bsl_type::RUN_EXPR]($ec,$node->expr);
					$i++;
				}
				if( $this->status != app_status::S_NORMAL) break;
			}
			
			//clean
			$this->symt->pop();
			$this->cs->pop();
		};
		$this->block_func[_bsl_type::KFUNCTION] = function ($task,$func, $in_args) {
			if ($func == null) return null;
			/* @var $func _bslfunction */
			$sym = new _bsl_sym_level();
			
			$args = $func->get_args();
			foreach ($args as $arg) {
				/* @var $arg _bslarg */
				$value = $this->block_func[_bsl_type::OP_BEGIN]($arg->expr);
				$sym->set($arg->name, $value);
			}
			
			$frame = new _bsl_frame();
			
			$frame->is_func = true;
			$frame->args = $in_args;
			
			$this->cs->push($frame);
			$this->symt->push($sym);
			
			$this->block_func[_bsl_type::RUN_BODY]($task,$func->get_body($task->get_app()));
			if( $this->status == app_status::S_RETURN ) $this->status = app_status::S_NORMAL;
			
			$retval = $frame->retval;
			$this->symt->pop();
			$this->cs->pop();
			
			return $retval;
		};
		$this->block_func[_bsl_type::RUN_EXPR] = function ($ec,$tree) {//for expr
			if (is_array($tree[0])) {
				$left = $tree[0];
				$op = $tree[1];
				$right = $tree[2];
				$f = $this->block_func[_bsl_type::RUN_EXPR];
				return $this->op_func[$op]($f, $ec,$left, $right);
			} else {
				$t = $tree->k;
				if ($t == _bsl_type::LITERAL) {
					return $tree->str;
				}
				if ($t == _bsl_type::IDENTITY) {
					$var_name = $tree->str;
					
					$count = $this->symt->length();
					for ($i = $count - 1; $i >= 0; $i--) {
						$sym = $this->symt->peek($i);
						/* @var $sym _bsl_sym_level */
						if ($sym->has($var_name)) {
							return $sym->get($var_name);
						}
					}
					$task = $ec->task;
					$app = $task->get_app();
					/* @var $app _bslapp */
					
					$varcount = count($app->vars);
					for ($i = 0; $i < $varcount; $i++) {
						$var = $app->vars[$i];
						if ($var->name == $var_name) {
							return $task->get_val($var_name);
						}
					}
					$this->report_error($task,$ec->line_index,"未定义的变量[{$var_name}]");
				}
				if ($t == _bsl_type::KNULL) return null;
				if ($t == _bsl_type::KTRUE) return true;
				if ($t == _bsl_type::KFALSE) return false;
				if ($t == _bsl_type::KTHIS) {
					$count = $this->cs->length();
					for($i=$count-1;$i>=0;$i--)
					{
						$frame = $this->cs->peek($i);
						/* @var $frame _bsl_frame */
						if( $frame->is_each )
						{
							return $frame->each_value;
						}
					}
					$this->report_error($ec->task,$ec->line_index,"非法使用@this");
				}
				if ($t == _bsl_type::KINDEX) {
					$count = $this->cs->length();
					for($i=$count-1;$i>=0;$i--)
					{
						$frame = $this->cs->peek($i);
						/* @var $frame _bsl_frame */
						if( $frame->is_each )
						{
							return $frame->each_index;
						}
					}
					$this->report_error($ec->task,$ec->line_index,"非法使用@index");
				}
				if ($t == _bsl_type::KFIRST) {
					$count = $this->cs->length();
					for($i=$count-1;$i>=0;$i--)
					{
						$frame = $this->cs->peek($i);
						/* @var $frame _bsl_frame */
						if( $frame->is_each )
						{
							return $frame->each_index == 0;
						}
					}
					$this->report_error($ec->task,$ec->line_index,"非法使用@first");
				}
				if ($t == _bsl_type::KLAST) {
					$count = $this->cs->length();
					for($i=$count-1;$i>=0;$i--)
					{
						$frame = $this->cs->peek($i);
						/* @var $frame _bsl_frame */
						if( $frame->is_each )
						{
							return $frame->each_index == count($frame->each_obj)-1;
						}
					}
					$this->report_error($ec->task,$ec->line_index,"非法使用@last");
				}
				if ($t == _bsl_type::KKEY) {
					$count = $this->cs->length();
					for($i=$count-1;$i>=0;$i--)
					{
						$frame = $this->cs->peek($i);
						/* @var $frame _bsl_frame */
						if( $frame->is_each )
						{
							return $frame->each_key;
						}
					}
					$this->report_error($ec->task,$ec->line_index,"非法使用@this");
				}
			}
			return null;
		};
		$this->block_func[_bsl_type::KDIM] = function ($task,$dim) {
			$count = $this->symt->length();
			$sym = $this->symt->peek($count-1);
			/* @var $sym _bsl_sym_level */
			if( !$sym->has($dim->name) )
			{
				$ec = new expr_context();
				$ec->task = $task;
				$ec->line_index = $task->get_app()->lines->lines[$dim->lindex]->row;
				$value = $this->block_func[_bsl_type::RUN_EXPR]($ec,$dim->expr);
				$sym->set($dim->name,$value);
			}
			
		};
		
		$this->block_func[_bsl_type::KBREAK] = function ($task,$dim)
		{
			$this->status = app_status::S_BREAK;
		};
		$this->block_func[_bsl_type::KCONTINUE] = function ($task,$dim)
		{
			$this->status = app_status::S_CONTINUE;
		};
		$this->block_func[_bsl_type::KRETURN] = function ($task,$return)
		{
			$ec = new expr_context();
			$ec->task = $task;
			$ec->line_index = $task->get_app()->lines->lines[$return->lindex]->row;
			$value = $this->block_func[_bsl_type::RUN_EXPR]($return->expr);
			$this->appret = $value;
			$this->status = app_status::S_RETURN;
		};
		
		$this->proc_run['start'] = function ($task,$proc) {
			//do nothing
		};
		$this->proc_run['end'] = function ($task,$proc) {
			//do nothing
		};
		$this->proc_run['call'] = function ($task,$proc) {
			
		};
		$this->proc_run['compute'] = function ($task,$proc) {
			$this->block_func[_bsl_type::KFUNCTION]($task,$proc->get_code(), []);
		};
		$this->proc_run['empty'] = function ($task,$proc) {
			//do nothing
		};
		$this->proc_run['decision'] = function ($task,$proc) {
			
		};
		$this->proc_run['notify'] = function ($task,$proc) {
			
		};
		$this->proc_run['view'] = function ($task,$proc) {
			
		};
	}
	
	//接口
	//由启动者触发！
	public function start_app($appname)
	{
		$id = $this->appmgr->load_appid($appname);
		return $this->start_app_byid($id,$appname);
	}
	
	public function start_app_byid($id,$appname)
	{
		$source = $this->appmgr->load_app_byid($id);
		//write_log("load app:".$id." source=".$source);
		$app = $this->compiler->compile_app($source, $appname);
		$app->id = $id;
		
		$args = [];
		if ($app->type == 'ui') $args = http()->Request()->Params();
		
		if ($app->type == 'biz') {
			$task = $this->taskmgr->create_task($this,null,null,$app, $args);
			return $this->run_task($task);
		} else {
			$task = $this->taskmgr->create_task_ud($this,$app, $args);
			return $this->run_app($task);
		}
	}
	
	//接口
	//由启动者触发！
	public function do_event($taskid, $appid, $procid, $event, $args)
	{
		//
	}
	
	//适用于biz
	private function run_task($task)
	{
		$app = $task->get_app();
		$this->block_func[_bsl_type::KFUNCTION]($task,$app->get_enter(), []);
		
		$proc = $app->get_start_proc();
		while (true) {
			$this->run_proc($task, $proc);
			if ($proc->type == 'end') {
				$this->block_func[_bsl_type::KFUNCTION]($task,$app->get_leave(), []);
				break;
			} else {
				$routes = $proc->get_routes($app);
				$match = null;
				foreach ($routes as $route) {
					$expr = $route->expr;
					if ($expr == null || $this->block_func[_bsl_type::RUN_EXPR]($expr)) {
						$match = $route;
						break;
					}
				}
				if ($match == null) {
					$this->report_error($task,$task->get_app()->lines->lines[$proc->lindex]->row,"环节{$proc->name}没有满足条件的路由，流程无法继续运行");
				}
				$proc = $this->bsl_app->find_proc_byid($match->end_proc);
				if ($proc == null)
					$this->report_error($task,$task->get_app()->lines->lines[$proc->lindex]->row,"环节{$proc->name}之路由指向不存在的环节({$match->end_proc})");
			}
		}
		return [app_status::S_NORMAL,null];
	}
	
	//适用于ui/data
	private function run_app($task)
	{
		$app = $task->get_app();
		$this->block_func[_bsl_type::KFUNCTION]($task,$app->get_enter(), []);
		
		$proc = $app->get_start_proc();
		while (true) {
			$this->run_proc($task, $proc);
			if ($proc->type == 'end') {
				$this->block_func[_bsl_type::KFUNCTION]($task,$app->get_leave(), []);
				//返回值
				if( $this->status == app_status::S_HALT ) return [app_status::S_HALT,null];
				
				return [$this->status,$this->appret];
			} else {
				if( $this->status == app_status::S_HALT ) return [app_status::S_HALT,null];
				
				$routes = $proc->get_routes($app);
				$match = null;
				foreach ($routes as $route) {
					$expr = $route->get_expr();
					if ($expr == null || $this->block_func[_bsl_type::RUN_EXPR]($task,$expr)) {
						$match = $route;
						break;
					}
				}
				if ($match == null) {
					$this->report_error($task,$task->get_app()->lines->lines[$proc->lindex]->row,"环节{$proc->name}没有满足条件的路由，流程无法继续运行");
				}
				$proc = $app->find_proc_byid($match->end_proc);
				if ($proc == null)
					$this->report_error($task,$task->get_app()->lines->lines[$proc->lindex]->row,"环节{$proc->name}之路由指向不存在的环节({$match->end_proc})");
			}
		}
		return null;
	}
	
	private function run_proc($task, $proc)
	{
		$this->block_func[_bsl_type::KFUNCTION]($task,$proc->get_enter(), []);
		$this->proc_run[$proc->type]($task, $proc);
		$this->block_func[_bsl_type::KFUNCTION]($task,$proc->get_leave(), []);
	}
	
	private function report_error($task,$line,$hint)
	{
		$app = $task->get_app();
		$appname = $app->name;
		$msg = "{$appname}({$app->id})[行{$line}]" . $hint;
		bjerror($msg);
	}
	
	
}


class _bsl_sym_level
{
	public $vars = [];
	
	public function has($name)
	{
		return isset($this->vars[$name]);
	}
	
	public function get($name)
	{
		return $this->vars[$name];
	}
	
	public function set($name, $val)
	{
		$this->vars[$name] = $val;
	}
}

class _bsl_frame
{
	public $vars = [];
	public $args = [];
	public $retval=null;
	public $is_func=false;
	public $is_loop=false;
	public $is_each=false;
	public $each_obj=null;
	public $each_index=0;
	public $each_key=null;
	public $each_value=null;
}

class _bsl_stack
{
	private $arr = [];
	private $size = 0;
	private $cap = 0;
	
	public function __construct($cap = 1000)
	{
		$this->cap = $cap;
	}
	
	public function length()
	{
		return $this->size;
	}
	
	public function pop()
	{
		if ($this->size == 0) bjerror("栈下溢");
		$this->size--;
		$v = $this->arr[$this->size];
		return $v;
	}
	
	public function push($v)
	{
		if ($this->size == $this->cap) bjerror("栈上溢");
		if ($this->size < count($this->arr)) $this->arr[$this->size] = $v;
		else $this->arr[] = $v;
		$this->size++;
	}
	
	public function peek($i)
	{
		return $this->arr[$i];
	}
}