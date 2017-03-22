<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/21
 * Time: 10:40
 */
namespace feature\primary;
class Prop{
    public $meta;
    public function __construct()
    {
        $this->meta = bjmeta('sys.Prop');
    }

    public function getSuperClean(){
        $SuperClean = $this->meta->load( ["name"=>"superclean"]);
        return $SuperClean;
    }

    public function loadByName($name){
        $prop = $this->meta->load( ["name"=>$name]);
        return $prop;
    }
}
