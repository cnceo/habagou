<?php
namespace meta\wealth;
class Cleanrecord{
    public static function get_meta()
    {
        static $meta_cfg;
        if( ! $meta_cfg )
        {
            $meta_cfg = [
                "TableName"	=>	"cleanrecord",
                "Fields" => [
                    //!! 字段格式请参考vendor.db.model中的说明 !!
                    //-------------------------------------------------------------------------------------
                    //名称			类型			长度	NULL 	缺省值		注释
                    //-------------------------------------------------------------------------------------
                    ["id", 			"int", 			11, 	1, 		"auto", 	"ID"],
                    ["accid", 	    "int", 		    11, 	1, 		'', 		"会员id"],
                    ["targetid",    "int", 		    11, 	1, 		'', 		"打扫目标id"],
                    ["num",         "float",        0,      1,      '',         "打扫数量"],
                    ["cleantime",	"int", 	        11, 	1, 	    '',	        "打扫时间"],


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