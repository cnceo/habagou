<?php
/**
 * http://www.baijienet.com
 * User: sint
 * Date: 2016/11/27
 * Time: 8:12
 */
class vendor_workflow_extfuncmgr
{
	private $funcs=[];
	
	public function __construct()
	{
		$this->register("HALT",function($task,$args){
			exit;
		});
		$this->register('RETURN',function($task,$args){
			$context = $task->get_context();
			$context->status = app_status::S_RETURN;
			if( count($args) ) $context->appret = $args[0];
			else $context->appret = null;
		});
		$this->register('ASSIGN',function($task,$args){
			$context = $task->get_context();
			$count = count($args);
			if( $count < 1 ) bjerror("缺少ASSIGN参数");
			$name = $args[0];
			$value = null;
			if( $count > 1 ) $value = $args[1];
			$context->uirender->set_value($name,$value);
		});
	}
	
	//调用扩展函数
	//	task: 事务
	//	func: 函数名，字符串
	//	args：参数，数组
	public function call($task,$func,$args)
	{
		if( !isset($this->funcs[$func]) ) bjerror("扩展函数{$func}未定义");
		$f = $this->funcs[$func];
		return $f($task,$args);
	}
	
	public function register($funcname,$func)
	{
		$this->funcs[$funcname] = $func;
	}
}