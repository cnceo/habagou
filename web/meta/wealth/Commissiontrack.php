<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/13
 * Time: 19:06
 */

namespace meta\wealth;

class Commissiontrack{
    public static function get_meta()
    {
        static $meta_cfg;
        if( ! $meta_cfg )
        {
            $meta_cfg = [
                "TableName"	=>	"commissiontrack",
                "Fields" => [
                    //!! 字段格式请参考vendor.db.model中的说明 !!
                    //-------------------------------------------------------------------------------------
                    //名称			类型			长度	NULL 	缺省值		注释
                    //-------------------------------------------------------------------------------------
                    ["id", 			"int", 			11, 	1, 	"auto", 	             ""],
                    ["accid", 	                          "int", 		             11, 	1, 	'', 		"会员id"],
                    ["subid", 		             "int", 		             11, 	1, 	'', 		"下属id"],
                    ["rate",	                          "float", 	                          0, 	1, 	'',	             "佣金比例"],
                    ["num",		             "float", 		             0, 	1, 	'', 	             "佣金值"],
                    ["commissiontime",            "int",                              11,        1,          '',                      "打扫时间"],
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
