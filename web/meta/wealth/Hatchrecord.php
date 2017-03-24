<?php
namespace meta\wealth;
class Hatchrecord{
    public static function get_meta()
    {
        static $meta_cfg;
        if( ! $meta_cfg )
        {
            $meta_cfg = [
                "TableName" =>  "hatchrecord",
                "Fields" => [
                    //!! 字段格式请参考vendor.db.model中的说明 !!
                    //-------------------------------------------------------------------------------------
                    //名称            类型          长度  NULL    缺省值     注释
                    //-------------------------------------------------------------------------------------
                    ["id",          "int",          11,     1,      "auto",     "ID"],
                    ["accid",       "int",          11,     1,      '',         "会员id"],
                    ["num",         "int",          11,     1,      '',         "数量"],
                    ["landindex",   "int",          11,     1,      '',         "草地编号"],
                    ["type",        "int",          4,      1,      1,         "草地类型"],
                    ["hatchtime",   "int",          11,     1,      1,         "操作时间"],

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