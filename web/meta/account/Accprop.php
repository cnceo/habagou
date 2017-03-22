<?php
namespace meta\account;
class Accprop{
    public static function get_meta()
    {
        static $meta_cfg;
        if( ! $meta_cfg )
        {
            $meta_cfg = [
                "TableName" =>  "accprop",
                "Fields" => [
                    //!! 字段格式请参考vendor.db.model中的说明 !!
                    //-------------------------------------------------------------------------------------
                    //名称            类型          长度  NULL    缺省值     注释
                    //-------------------------------------------------------------------------------------
                    ["accid",           "int",      11,    1,      '',      "会员id"],
                    ["proid",           "int",      11,    1,      '',      "道具id"],
                    ["level",           "int",      11,    1,      0,        "等级"],
                    ["scale",            "float",   0,     1,      0,       "加速比例（0.1）"],
                    ["buytime",          "int",     11,    1,       '',     "购买时间"],
                    ["effecttime",      "int",      11,    1,      0,       "生效时间"],
                    ["overtime",        "int",      11,    1,       0,       "过期时间"],
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
