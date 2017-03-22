<?php
/**
 * http://www.baijienet.com
 * User: sint
 * Date: 2016/11/22
 * Time: 21:38
 */

//========= 接口规范定义！=========
//注：只做接口约束，不作类型约束！

//流程管理接口
class intf_bsl_app_mgr
{
	//根据流程名称获得流程定义的源码
	public function load_app($appname){}
	
	//根据流程名称获得流程id
	public function load_appid($appname){}
	
	//根据流程ID(版本ID)获得流程定义的源码
	public function load_app_byid($appid){}
	
}

//流程实例
class intf_bsl_app
{
	
}

//环节实例
class intf_bsl_proc
{
	
}

//WORK
class intf_bsl_work
{
	
}

//JOB
class intf_bsl_job
{
	
}

//角色管理器接口
class intf_bsl_role_mgr
{
	
}

//扩展函数调用接口
class intf_bsl_extfunc_caller
{
	//调用扩展函数
	//	task: 事务
	//	func: 函数名，字符串
	//	args：参数，数组
	public function call($task,$func,$args){}
}

//事务接口
class intf_bsl_task
{
	//设置变量的值
	public function set_var($name,$val){}
	
	//得到变量的值
	public function get_var($name){}
	
	//得到流程对象
	public function get_app(){}
	
	//得到运行时
	public function get_context(){}
	
	//得到父级事务
	public function get_parent(){}
	
	//得到父级事务的work
	public function get_parent_work(){}
	
}

//事务管理器接口
class intf_bsl_task_mgr
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
	public function create_task_ud($context,$app,$args){}
	
	//正常结束一个事务
	public function end_task($task){}
	
	//强行结束一个事务
	public function halt_task($task,$reason){}
	
	
}

//编译器接口
class intf_bsl_compiler
{
	//编译app
	//返回
	//	intf_bsl_app
	public function compile_app($source,$filename){}
	
	//对源码进行语法检查(完整编译)
	//适用于被IDE调用
	public function syntax_check($source,$filename){}
}

//解释器接口
class intf_bsl_interpreter
{
	//解释执行流程
	//参数
	//	context: 工作流运行时
	//	app: 流程实例
	//	in_args: 入参 [k=>v...]
	//返回值
	//	[k=>v...]
	public function run_app($context,$app,$in_args){}
	
	//响应流程事件(仅适用于UI流程)
	//参数
	//	context: 工作流运行时
	//	app: 流程实例
	//	in_args: 入参 [k=>v...]
	public function do_event($context,$app,$event,$in_args){}
	
	//用户审批(仅适用于biz流程)
	//参数
	//	context: 工作流运行时
	//	jobid: jobid
	//	in_args: 入参 [k=>v...]
	public function do_job($context,$jobid,$in_args){}
}

//UI管理器
class intf_bsl_ui_render
{
	//展示界面
	public function render($task,$page){}
}

//工作流运行时
class intf_bsl_workflow_context
{
	//-------------------- app -------------------
	//得到流程管理接口
	//返回
	//	intf_bsl_app_mgr
	public function get_app_mgr(){}
	
	//得到当前正在运行的流程
	//返回
	//	intf_bsl_app
	public function get_cur_app(){}
	
	//得到当前正在运行的环节
	//返回
	//	intf_bsl_proc
	public function get_cur_proc(){}
	
	
	//----------------- task -------------------
	//得到事务管理接口
	//返回
	//	intf_bsl_task_mgr
	public function get_task_mgr(){}
	
	//得到当前正在运行的task
	//返回
	// 	intf_bsl_task
	public function get_cur_task(){}
	
	//得到当前正在运行的WORK
	//返回
	//	intf_bsl_work
	public function get_cur_work(){}
	
	//得到当前正在运行的JOB
	//返回
	//	intf_bsl_job
	public function get_cur_job(){}
	
	
	//-------------------- ext function ------------------
	//得到扩展函数调用接口
	//返回
	//	intf_bsl_extfunc_caller
	public function get_ext_caller(){}
	
	
	//------------------- role ----------------------
	//得到角色管理器接口
	//返回
	//	intf_bsl_role_mgr
	public function get_role_mgr(){}
	
	
	//---------------- compiler -----------------------
	//得到编译器接口
	//返回
	//	intf_bsl_compiler
	public function get_compiler(){}
	
	
	//------------- interpreter ----------------------
	//得到解释器接口
	//返回
	//	intf_bsl_interpreter
	public function get_interpreter(){}
	
	//--------------- runtime ------------------------
	//得到事件名
	//仅适用于UI流程
	public function get_event(){}
	
	//得到流程入参
	//适用于所有流程
	public function get_in_args(){}
	
	//--------------------- ui ------------------------
	//得到UI管理器
	//返回
	//	intf_bsl_ui_render
	public function get_ui_render(){}
}