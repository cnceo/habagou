<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/20
 * Time: 17:46
 */
namespace feature\primary;
class Wealthtrack{
    public $meta;
    public function __construct()
    {
        $this->meta = bjmeta('wealth.Wealthtrack');
    }

    public function add($wealthtrack){
        $this->meta->add($wealthtrack);
    }
}