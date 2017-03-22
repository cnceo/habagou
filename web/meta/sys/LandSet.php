<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/14
 * Time: 11:00
 */

namespace meta\sys;

class LandSet{
    public static function get_meta()
    {
        static $meta_cfg;
        if( ! $meta_cfg )
        {
            $meta_cfg = [
                "TableName"	=>	"landset",
                "Fields" => [
                    //!! 字段格式请参考vendor.db.model中的说明 !!
                    //-------------------------------------------------------------------------------------
                    //名称			类型			长度	NULL 	缺省值		注释
                    //-------------------------------------------------------------------------------------
                    ["index", 			"int", 			11, 	1, 	    0, 	 "草地索引"],
                    ["type", 	    "tinyint", 		1, 	    1, 	    0, 	 "草地类型"],
                    ["deposit", 		"int", 		    11, 	1, 	    0, 	 "草地押金"],
                    ["capacity", 		"int", 		    11, 	1, 	    0, 	 "草地容量"],
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
