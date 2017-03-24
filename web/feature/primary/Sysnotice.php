<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/28
 * Time: 20:50
 */
namespace feature\primary;
class Sysnotice{
    public $meta;
    public function __construct()
    {
        $this->meta = bjmeta('sys.Sysnotice');
    }
    public function loadAll(){

        $data = bjcreate(
            "bjphp.vendor.db.Dao",
            "select * from `sysnotice` ORDER BY id DESC ")->all();
        return $data;
    }
}