<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/21
 * Time: 21:40
 */
namespace feature\primary;
class Landset{
    public $meta;
    public function __construct()
    {
        $this->meta = bjmeta('sys.LandSet');
    }

    public function getLandset($landSet){
         return $this->meta->load(['index'=>(int)$landSet->getIndex()]);
    }

}