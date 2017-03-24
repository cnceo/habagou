<?php
namespace meta\sys;
class Prop{
    public static function get_meta()
    {
        static $meta_cfg;
        if( ! $meta_cfg )
        {
            $meta_cfg = [
                "TableName"	=>	"prop",
                "Fields" => [
                    //!! 字段格式请参考vendor.db.model中的说明 !!
                    //-------------------------------------------------------------------------------------
                    //名称			类型			长度	NULL 	缺省值		注释
                    //-------------------------------------------------------------------------------------
                    ["id", 			"int", 			11, 	1, 		"auto", 	"ID"],
                    ["name", 	    "string", 		50, 	1, 		'', 		"道具名称"],
                    ["fee", 	     "int", 		4, 	    1, 		0, 		    "道具费用"],
                    ["expiry", 	 "int", 		4, 	    1, 		0, 		    "有效期(单位为天)"],
                    ["accelerate",	 "float", 	    0, 	    1, 	    '',	        "道具加速比例(0.1)"],
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