<?php
/**
 * http://www.baijienet.com
 * User: sint
 * Date: 2016/11/27
 * Time: 8:06
 */
class vendor_workflow_appmgr
{
	//根据流程名称获得流程定义的源码
	public function load_app($appname)
	{
		$app = meta('app.app')->Load(['name'=>$appname]);
		if( $app == null ) bjerror("流程{$appname}不存在");
		
		$ver = bjcreate(
			'vendor.db.dao',
			'select t.[verid],t.[bsl] from {m1} t where t.{appid=} order by t.[verid] desc',
			[
				'm1'		=>	meta('app.appver')->TableName(),
				'appid'		=>	$app->id
			]
		)->first();
		if( $ver == null ) bjerror("流程{$appname}不存在");
		return $ver->bsl;
	}
	
	//根据流程名称获得流程id
	public function load_appid($appname)
	{
		$app = meta('app.app')->Load(['name'=>$appname]);
		if( $app == null ) bjerror("流程{$appname}不存在");
		
		$ver = bjcreate(
			'vendor.db.dao',
			'select t.[verid] from {m1} t where t.{appid=} order by t.[verid] desc',
			[
				'm1'		=>	meta('app.appver')->TableName(),
				'appid'		=>	$app->id
			]
		)->first();
		if( $ver == null ) bjerror("流程{$appname}不存在");
		return $ver->verid;
	}
	
	//根据流程名称获得流程id
	public function load_verid($appid)
	{
		$ver = bjcreate(
			'vendor.db.dao',
			'select t.[verid] from {m1} t where t.{appid=} order by t.[verid] desc',
			[
				'm1'		=>	meta('app.appver')->TableName(),
				'appid'		=>	$appid
			]
		)->first();
		if( $ver == null ) bjerror("流程{$appid}不存在");
		return $ver->verid;
	}
	
	//根据流程ID(版本ID)获得流程定义的源码
	public function load_app_byid($appid)
	{
		return meta('app.appver')->Load(['verid'=>$appid],['bsl'])->bsl;
	}
}