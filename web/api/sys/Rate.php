<?php

namespace api\sys;

class Rate
{
	public static function Config()
	{
		static $config;
		if( ! $config )
		{
			$config = [
				"ModuleName"	=>	"利率列表",
				"Groups"	=>	["利率"],
				"Functions"	=>	[
					//[分组，权限]　权限取值范围：*:public -:login "":验证

					"rates"		=>	["利率","*"]
				],
				"Policy"=>[
					[
							"Groups"	=>	[],
							"Functions"=>[],
							"Enter"=>["policy.session.Filter.get_session"],//前置操作
							"Leave"=>[]//后置操作
					]
				],
			];
		}
		return $config;
	}
	
	public static function rates(){
		$rates=bjfeature('Biz.Rate')->loadSevenday();
		var_dump($rates);
	}

}