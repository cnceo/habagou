<?php
namespace meta\wealth;
class Land{
    public static function get_meta()
    {
        static $meta_cfg;
        if( ! $meta_cfg )
        {
            $meta_cfg = [
                "TableName"	=>	"land",
                "Fields" => [
                    //!! 字段格式请参考vendor.db.model中的说明 !!
                    //-------------------------------------------------------------------------------------
                    //名称			类型			长度	NULL 	缺省值		注释
                    //-------------------------------------------------------------------------------------
                    ["id", 			"int", 			11, 	1, 		"auto", 	"ID"],
                    ["accid", 	    "int", 		    11, 	1, 		'', 		"会员id"],
                    ["landindex",   "int", 		    11, 	1, 		'', 		"草地索引"],
                    ["landtype",    "tinyint",      1,      1,      1,         "草地类型"],
                    ["deposit",	    "int", 	        11, 	1, 	    '',	        "押金"],
                    ["wealth",	    "int", 		    11, 	1, 		'', 	    "当前财富"],
                    ["starttime", 	"int", 	        11,     1, 		'', 		"开地时间"],

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