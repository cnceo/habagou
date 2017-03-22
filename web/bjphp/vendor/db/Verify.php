<?php
// =======================================================================
// | 百捷PHP框架(BJPHP)
// | ---------------------------------------------------------------------
// | 许可协议Apache2 ( http://www.apache.org/licenses/LICENSE-2.0 )
// | 技术支持QQ群：276228406
// | 微信公众号  ：百捷网络
// | 官方网站    ：http://www.baijienet.com
// =======================================================================
namespace bjphp\vendor\db;

class Verify
{
	public $_Text="";
	public $_Alias='';
	function __construct($str,$alias)
	{
		$this->_Text = $str;
		$this->_Alias = $alias;
	}
	public function mustFill()
	{
		if( $this->_Text == '' ) bjerror('['.$this->_Alias.']是必填项');
		return $this;
	}
	public function text()
	{
		return $this->_Text;
	}
	public function maxLength($len=0)
	{
		if( (int)$len > 0 )
		{
			if( strlen( (string)$this->_Text ) > (int)$len )
				bjerror('['.$this->_Alias.']超过了最大长度：'.$len);
		}
		return $this;
	}
	public function mobile()
	{
		if( !preg_match("/1[3458]{1}\d{9}$/",$this->_Text) )
			bjerror('['.$this->_Alias.']不是合法的手机号码格式');
		return $this;
	}
	public function email()
	{
		if( ! (preg_match(
			'/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/',
			($this->_Text) ) ) )
			bjerror('['.$this->_Alias.']不是合法的邮箱格式');
		return $this;
	}
	public function digit()
	{
		if( !is_numeric($this->_Text) )
			bjerror('['.$this->_Alias.']不是合法的数字格式');
		return $this;
	}
	public function idcard()
	{
		if( !$this->isCreditNo($this->_Text) )
			bjerror('['.$this->_Alias.']不是合法的身份证格式');
		return $this;
	}
	public function xss()
	{
		require_once('htmlpurifier/library/HTMLPurifier.auto.php');
		$config = \HTMLPurifier_Config::createDefault();
		$purifier = new \HTMLPurifier($config);
		$this->_Text = $purifier->purify($this->_Text);
		return $this;
	}
	
	private function isCreditNo($vStr)
	{
		$vCity = array(
			'11','12','13','14','15','21','22',
			'23','31','32','33','34','35','36',
			'37','41','42','43','44','45','46',
			'50','51','52','53','54','61','62',
			'63','64','65','71','81','82','91'
		);
		
		if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;
		
		if (!in_array(substr($vStr, 0, 2), $vCity)) return false;
		
		$vStr = preg_replace('/[xX]$/i', 'a', $vStr);
		$vLength = strlen($vStr);
		
		if ($vLength == 18)
		{
			$vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
		} else {
			$vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
		}
		
		if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
		if ($vLength == 18)
		{
			$vSum = 0;
			
			for ($i = 17 ; $i >= 0 ; $i--)
			{
				$vSubStr = substr($vStr, 17 - $i, 1);
				$vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
			}
			
			if($vSum % 11 != 1) return false;
		}
		
		return true;
	}
}




