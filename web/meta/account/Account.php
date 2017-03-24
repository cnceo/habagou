<?php
namespace meta\account;
class Account{
    public static function get_meta()
    {
        static $meta_cfg;
        if( ! $meta_cfg )
        {
            $meta_cfg = [
                "TableName"	=>	"account",
                "Fields" => [
                    //!! 字段格式请参考vendor.db.model中的说明 !!
                    //-------------------------------------------------------------------------------------
                    //名称			类型			长度	NULL 	缺省值		注释
                    //-------------------------------------------------------------------------------------
                    ["id", 			"int", 			12, 	1, 		"auto", 	"ID"],
                    ["account", 	"string", 		12, 	1, 		'', 		"账号"],
                    ["name", 		"string", 		50, 	1, 		'', 		"姓名"],
                    ["phone",	    "string", 	    20, 	1, 	    '',	        "电话"],
                    ["pwd",			"string", 		32, 	1, 		'123456', 	"密码"],
                    ["sex", 		"tinyint", 	    1, 		1, 		0, 			"性别"],
                    ["headid", 	    "tinyint", 	    1, 	    1, 		0, 		    "头像"],
                    ["level", 		"tinyint", 	    1, 	    1, 		1, 		    "等级"],
                    ["recommendid",	"int",   	    11,	    1, 	    0, 	        "推荐id"],
                    ["frozen",	    "tinyint", 	    1, 	    1, 		0, 			"是否冻结"],
                    ["regtime", 	"int", 	        11,     null,   null, 	    "注册时间"],
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