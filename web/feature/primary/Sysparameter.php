<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 17:12
 */
namespace feature\primary;
class Sysparameter{
    public $meta;
    public function __construct()
    {
        $this->meta = bjmeta('sys.Sysparameter');
    }

    public function loadByName($name){
        return $this->meta->load(['name'=>$name]);
    }
}