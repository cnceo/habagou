<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/21
 * Time: 10:40
 */
namespace feature\primary;
class Feeder{
    public $meta;
    public function __construct()
    {
        $this->meta = bjmeta('sys.Feeder');
    }
    public function loadNextFeeder($level){
        $level=$level+1;
        return $this->meta->load(['level'=>$level]);
    }

    public function loadFeedBylevel($level){
        return $this->meta->load(['level'=>$level]);
    }
}