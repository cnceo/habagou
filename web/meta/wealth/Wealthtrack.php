<?php
namespace meta\wealth;
class Wealthtrack{
    public static function get_meta()
    {
        static $meta_cfg;
        if( ! $meta_cfg )
        {
            $meta_cfg = [
                "TableName"	=>	"wealthtrack",
                "Fields" => [
                    //!! 字段格式请参考vendor.db.model中的说明 !!
                    //-------------------------------------------------------------------------------------
                    //名称			类型			长度	NULL 	缺省值		注释
                    //-------------------------------------------------------------------------------------
                    ["id", 			"int", 			11, 	1, 		"auto", 	"ID"],
                    ["accid", 	    "int", 		    11, 	1, 		'', 		"会员id"],
                    ["scale", 		"float", 		0, 	    1, 		'', 		"手续比例"],
                    ["value",	    "float", 	    11, 	1, 	    '',	        "变化值"],
                    ["beforevalue",	"float", 		0, 	    1, 		'', 	    "变化前"],
                    ["aftervalue", 	"float", 	    0, 		1, 		'', 		"变化后"],
                    ["remark",      "string",       128,    null,   null,       "说明"],
                    ["tracktime", 	"int", 	        11, 	1, 		'', 		"记录时间"],
                    ["type", 		"tinyint", 	    1, 	    1, 		1, 		    "财富类型"],

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