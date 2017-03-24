<?php
namespace meta\wealth;
class Transaction{
    public static function get_meta()
    {
        static $meta_cfg;
        if( ! $meta_cfg )
        {
            $meta_cfg = [
                "TableName"	=>	"transaction",
                "Fields" => [
                    //!! 字段格式请参考vendor.db.model中的说明 !!
                    //-------------------------------------------------------------------------------------
                    //名称			类型			长度	NULL 	缺省值		注释
                    //-------------------------------------------------------------------------------------
                    ["id", 			"int", 			11, 	1, 		"auto", 	"ID"],
                    ["launchid", 	"int", 		    11, 	1, 		 '', 		"发起人id"],
                    ["receiveid",  "int", 		11, 	1, 		 '', 		"接收人id"],
                    ["num",	        "int", 	        11, 	1, 	      '',	        "交易财务值"],
                    ["fee",	        "float", 	    0, 	    1, 	      0,	        "交易手续费比例"],
                    ["status",	    "tinyint", 	    1, 	    1, 		 0, 	    "交易状态"],
                    ["launchtime",  "int", 	        11,     1, 		'', 		"交易发生时间"],
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