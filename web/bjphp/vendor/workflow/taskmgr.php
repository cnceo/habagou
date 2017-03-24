<?php
/**
 * http://www.baijienet.com
 * User: sint
 * Date: 2016/11/27
 * Time: 8:14
 */
class vendor_workflow_taskmgr
{
	//加载一个事务
	//返回
	//	intf_bsl_task 事务实例
	public function load_task($context,$taskid){}
	
	//创建一个事务(biz)
	//	context:运行时
	//	parent: 父级事务
	//	parent_work:父级事务的work
	//	app: app对象
	//	args: 传入参数，(k=>v)数组
	//返回
	//	intf_bsl_task 事务实例
	public function create_task($context,$parent,$parent_work,$app,$args){}
	
	//创建一个事务(ui/data)
	//	context:运行时
	//	parent: 父级事务
	//	pproc:父级事务的环节
	//	app: app对象
	//	args: 传入参数，(k=>v)数组
	//返回
	//	intf_bsl_task 事务实例
	public function create_task_ud($context,$app,$args)
	{
		return new _udtask($context,$app,$args);
	}
	
	//正常结束一个事务
	public function end_task($task){}
	
	//强行结束一个事务
	public function halt_task($task,$reason){}
}

class _udtask
{
	private $context;
	private $app;
	private $args;
	private $vars=[];
	
	public function __construct($context,$app,$args)
	{
		$this->context = $context;
		$this->app = $app;
		$this->args = $args;
		$this->init();
	}
	
	private function init()
	{
		foreach($this->app->vars as $var)
		{
			if( $var->default ) $this->vars[$var->name] = $var->default;
		}
	}
	
	//设置变量的值
	public function set_var($name,$val)
	{
		$this->vars[$name] = $val;
	}
	
	//得到变量的值
	public function get_var($name)
	{
		if( isset($this->vars[$name])) return $this->vars[$name];
		else return null;
	}
	
	//得到流程对象
	public function get_app()
	{
		return $this->app;
	}
	
	//得到运行时
	public function get_context()
	{
		return $this->context;
	}
	
	//得到父级事务
	public function get_parent()
	{
		return null;//??
	}
	
	//得到父级事务的work
	public function get_parent_work()
	{
		return null;//??
	}
}