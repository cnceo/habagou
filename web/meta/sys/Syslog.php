<?php
namespace meta\sys;
class Syslog{
    public static function get_meta()
    {
        static $meta_cfg;
        if( ! $meta_cfg )
        {
            $meta_cfg = [
                "TableName"	=>	"syslog",
                "Fields" => [
                    //!! 字段格式请参考vendor.db.model中的说明 !!
                    //-------------------------------------------------------------------------------------
                    //名称			类型			长度	NULL 	缺省值		注释
                    //-------------------------------------------------------------------------------------
                    ["id", 			"int", 			11, 	1, 		"auto", 	"ID"],
                    ["type", 	    "tinyint", 		1, 	    1, 		'', 		"日志类型"],
                    ["accid", 		"int", 		    11, 	1, 		'', 		"账户"],
                    ["acctype",	    "tinyint", 	    1, 	    1, 	    '',	        "账户类型(前后台)"],
                    ["clientip",	"string", 		50, 	1, 		'', 	    "客户端ip"],
                    ["content", 	"string", 	    0,      1, 		'', 		"内容"],
                    ["logtime",     "string",       50,     1,      '',         "日志事件"],


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