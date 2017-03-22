<?php

namespace meta\user;

class User
{
	public static function get_meta()
	{
		static $meta_cfg;
		if( ! $meta_cfg )
		{
			$meta_cfg = [
				"TableName"	=>	"meta_usermgr_user",
				"Fields" => [
					//!! 字段格式请参考vendor.db.model中的说明 !!
					//-------------------------------------------------------------------------------------
					//名称			类型			长度	NULL 	缺省值		注释
					//-------------------------------------------------------------------------------------
					["id", 			"int", 			12, 	1, 		"auto", 	"ID"],
					["name", 		"string", 		128, 	1, 		'', 		"姓名"],
					["pwd", 		"string", 		128, 	1, 		'', 		"密码"],
					["regtime",	"datetime", 	0, 		null, 	null, 	"注册时间"],
					["sex",			"int", 			1, 		1, 		0, 			"性别"],
					["age", 		"int", 			2, 		1, 		0, 			"年龄"],
				],
				
				"PK"=>["id"],
				"UK"=>[],
				"DELFLAG"=>""
			];
		}
		static $object;
		if (!$object) {
			$object = bjcreate('bjphp.vendor.db.Model',$meta_cfg);
		}
		return $object;
	}
}