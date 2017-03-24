<?php
namespace meta\sys;
class Sysmessage{
    public static function get_meta()
    {
        static $meta_cfg;
        if( ! $meta_cfg )
        {
            $meta_cfg = [
                "TableName"	=>	"sysmessage",
                "Fields" => [
                    //!! 字段格式请参考vendor.db.model中的说明 !!
                    //-------------------------------------------------------------------------------------
                    //名称			类型			长度	NULL 	缺省值		注释
                    //-------------------------------------------------------------------------------------
                    ["id", 			"int", 			11, 	1, 		"auto", 	"ID"],
                    ["accid", 	    "int", 		    11, 	1, 		'', 		"目标id"],
                    ["title", 		"string", 		128, 	1, 		'', 		"标题"],
                    ["content",	    "string", 	    0, 	    null, 	'',	        "内容"],
                    ["sendtime",	"int", 		    11, 	1, 		'', 	    "发送时间"],

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