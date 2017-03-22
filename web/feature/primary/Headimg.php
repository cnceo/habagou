<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/4
 * Time: 20:37
 */
namespace feature\primary;
class Headimg{

    public $meta;
    public function __construct()
    {
        $this->meta = bjmeta('sys.Headimg');
    }

    public function loadAll(){
        return $this->meta->loadAll();
    }
}