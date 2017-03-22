<?php
/**
 * http://www.baijienet.com
 * User: sint
 * Date: 2016/11/27
 * Time: 8:20
 */
class vendor_workflow_uirender
{
	public $context=[];
	public $global=[];
	public $view;
	
	public function __construct()
	{
		$this->view = bjcreate('vendor.ui.view');
	}
	
	//展示界面
	public function render($task,$page)
	{
		$app = $task->get_app();
		foreach($app->vars as $var)
		{
			$this->view->context[$var->name] = $task->get_var($var->name);
		}
		$source = $this->get_source($page);
		$this->view->block_content($source,$this->view->context,'');
		$this->view->render();
	}
	
	public function set_value($name,$value)
	{
		$this->view->context[$name] = $value;
	}
	
	private function get_source($page)
	{
		$f = bjcreate('feature.app.page');
		$obj = $f->Load(['path'=>$page],['id']);
		if( $obj == null ) bjerror("页面{$page}未找到");
		$pageid = $obj->id;
		
		$v = bjcreate(
			'vendor.db.dao',
			"select t.[content] from {m1} t where t.{pageid=} and t.{isdeleted=} order by t.[verid] desc",
			[
				'm1'		=>	meta('app.pagever')->TableName(),
				'pageid'	=>	$pageid,
				'isdeleted'	=>	0
			]
		)->first();
		if( $v == null ) bjerror("页面{$page}未找到");
		return $v->content;
	}
}