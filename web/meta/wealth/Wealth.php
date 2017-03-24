<?php
namespace meta\wealth;
class Wealth{
    public static function get_meta()
    {
        static $meta_cfg;
        if( ! $meta_cfg )
        {
            $meta_cfg = [
                "TableName" =>  "wealth",
                "Fields" => [
                    //!! 字段格式请参考vendor.db.model中的说明 !!
                    //-------------------------------------------------------------------------------------
                    //名称            类型          长度  NULL    缺省值     注释
                    //-------------------------------------------------------------------------------------
                    ["id",          "int",          11,     1,      "auto",     "ID"],
                    ["accid",       "int",          11,     1,      '',         "会员id"],
                    ["total",       "float",        0,      1,      '',         "财富总数"],
                    ["warehouse",   "float",        0,      1,      '',         "创库"],
                    ["ground",      "int",          11,     1,      '',         "地面财富"],
                    ["bone",        "float",        0,      1,      '',         "骨头"],
                    ["enddate",     "int",          11,     1,      0,          "骨头有效时间"],

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