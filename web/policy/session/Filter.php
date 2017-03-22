<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 11:48
 */
namespace policy\session;
class Filter{
    public  function get_session($path,&$input){
         $input->accid=bjsession()->get('accid');
        //bjsession()->set('account','123');
        //$input->accid=1;
    }
}