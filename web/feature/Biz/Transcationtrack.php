<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 17:05
 */
namespace feature\Biz;
class Transcationtrack{

    public function add($transaction){
         bjfeature('primary.Transcationtrack')->add($transaction);
    }

}