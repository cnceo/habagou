<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/11
 * Time: 11:56
 */
namespace feature\Biz;
class Auth{

    public static function hasShut(){
        $shutstation=bjstaticcall('feature.Biz.Sysparameter.loadByName','shutstation');
       /*if( (int)$shutstation->value==1 ) {
			//echo bjhttp()->request()->getOriginUrl();
			//why not ?? if( bjhttp()->request()->getOriginUrl() != '/sys/sys/shut' )
				bjerror('关站',999);
		}*/
		
    }


    public static function login_handler(){
        bjhttp()->response()->redirect("/user/user/login");
    }

    public static function shut_handler(){
		//bu neng yong redirect ??
        //bjhttp()->response()->redirect("/sys/sys/shut");
		bjview('exception.shut')->display();
		
    }

}