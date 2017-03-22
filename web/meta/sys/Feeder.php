<?php
namespace meta\sys;
class Feeder{
    public static function get_meta()
    {
        static $meta_cfg;
        if( ! $meta_cfg )
        {
            $meta_cfg = [
                "TableName"	=>	"feeder",
                "Fields" => [
                    //!! 字段格式请参考vendor.db.model中的说明 !!
                    //-------------------------------------------------------------------------------------
                    //名称			类型			长度	NULL 	缺省值		注释
                    //-------------------------------------------------------------------------------------
                    ["level", 	    "int", 			11, 	1, 		'', 	    "饲养员等级"],
                    ["rate", 	    "float", 		0, 	    1, 		'', 		"加速比例"],
                    ["fee",         "int", 		11, 	1, 		'', 		"升级费用"],
                    ["chance",	     "int", 	    0, 	    1, 	    '',	        "成功实际概率"],
                    ["showchance",	 "int", 		0, 	    1, 		'', 	    "显示用户概率"],

                ],
                "PK"=>[],
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