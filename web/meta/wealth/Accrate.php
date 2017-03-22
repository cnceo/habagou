<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/13
 * Time: 19:03
 */

namespace meta\wealth;

class Accrate{
    public static function get_meta()
    {
        static $meta_cfg;
        if( ! $meta_cfg )
        {
            $meta_cfg = [
                "TableName"	=>	"accrate",
                "Fields" => [
                    //!! 字段格式请参考vendor.db.model中的说明 !!
                    //-------------------------------------------------------------------------------------
                    //名称			类型			长度	NULL 	缺省值		注释
                    //-------------------------------------------------------------------------------------
                    ["id", 			"int", 			11, 	1, 	"auto", 	             ""],
                    ["accid", 	                          "int", 		             11, 	1, 	'', 		"会员id"],
                    ["rate", 		             "float", 		             0, 	1, 	'', 		"利率"],
                    ["speed",	                          "float", 	                          0, 	1, 	'',	             "加速比例"],
                    ["ratedate",		"int", 		             11, 	1, 	'', 	             "利率日期"],
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
