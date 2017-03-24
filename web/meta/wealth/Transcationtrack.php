<?php
namespace meta\wealth;
class Transcationtrack{
    public static function get_meta()
    {
        static $meta_cfg;
        if( ! $meta_cfg )
        {
            $meta_cfg = [
                "TableName"	=>	"transcationtrack",
                "Fields" => [
                    //!! 字段格式请参考vendor.db.model中的说明 !!
                    //-------------------------------------------------------------------------------------
                    //名称			类型			长度	NULL 	缺省值		注释
                    //-------------------------------------------------------------------------------------
                    ["id", 			"int", 			11, 	1, 		"auto", 	"ID"],
                    ["fromid", 	    "int", 		    11, 	1, 		'', 		"发起人id"],
                    ["toid", 		"int", 		    11, 	1, 		'', 		"接收人id"],
                    ["num",	        "int", 	        11, 	1, 	    '',	        "交易金额"],
                    ["starttime",	"int", 		    11, 	1, 		'', 	    "开始时间"],
                    ["endtime", 	"int", 	        11,     1, 		'', 		"结束时间"],
                    ["rate",        "float",        0,      1,      '',         "交易比例"],
                    ["fee",         "int",          11,     1,      '',         "手续费额"],

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