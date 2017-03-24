<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/28
 * Time: 22:36
 */
namespace feature\Biz;
class Feeder{

    public static function loadNextFeeder($level){
         return bjfeature('primary.Feeder')->loadNextFeeder($level);
    }

    public static function loadFeedBylevel($level){
        return bjfeature('primary.Feeder')->loadFeedBylevel($level);
    }
}