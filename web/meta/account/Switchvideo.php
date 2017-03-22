<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/3
 * Time: 18:26
 */
namespace meta\account;
class Switchvideo{
    public static function get_meta()
    {
        static $meta_cfg;
        if( ! $meta_cfg )
        {
            $meta_cfg = [
                "TableName"	=>	"switchvideo",
                "Fields" => [
                    //!! 字段格式请参考vendor.db.model中的说明 !!
                    //-------------------------------------------------------------------------------------
                    //名称			类型			长度	NULL 	缺省值		注释
                    //-------------------------------------------------------------------------------------
                    ["accid", 		"int", 		    12, 	1, 		"", 	    "会员ID"],
                    ["status", 	"tinyint", 	1, 	    1, 		0, 		    "音效开关"]
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