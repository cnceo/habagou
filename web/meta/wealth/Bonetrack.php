<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/13
 * Time: 19:04
 */

namespace meta\wealth;

class Bonetrack{
    public static function get_meta()
    {
        static $meta_cfg;
        if( ! $meta_cfg )
        {
            $meta_cfg = [
                "TableName"	=>	"bonetrack",
                "Fields" => [
                    //!! 字段格式请参考vendor.db.model中的说明 !!
                    //-------------------------------------------------------------------------------------
                    //名称			类型			长度	NULL 	缺省值		注释
                    //-------------------------------------------------------------------------------------
                    ["id", 			"int", 			11, 	1, 	    "auto", 	  ""],
                    ["accid", 	    "int", 		    11, 	1, 	    '', 		  "会员id"],
                    ["num", 		"float", 		 0, 	1,     	'', 		  "骨头数"],
                    ["basic", 		"float", 		 0, 	1,     	'', 		  "基础生长利率"],
                    ["breeder", 		"float", 		 0, 	1,     	'', 	  "饲养员加速利率"],
                    ["level", 		"float", 		 0, 	1,     	'', 		  "等级加速利率"],
                    ["total", 		"float", 		 0, 	1,     	'', 		  "总共加速利率"],
                    ["get",	         "int", 	     1, 	1,    	'',	          "是否领取"],
                    ["landindex",	 "int", 		 4, 	1,   	'', 	      "草地索引"],
                    ["bonedate",    "int",         11,    1,       '',          "日期"],
                    ["gettime",      "int",        11,    null,    null,        ""],
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
