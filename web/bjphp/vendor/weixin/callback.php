<?php

class vendor_weixin_callback
{
	protected $config;
	
	public function __construct()
	{
		$config = require INDEX_PATH . '/config/weixinconfig.php';
	}
	
	public function Run()
	{
		if( ! is_null( http()->Request()->Param('echostr',null) ) )
			$this->Valid( http()->Request()->Param('echostr','') );
		else
		{
			//php.ini中要设置 always_populate_raw_post_data = -1
			$postStr = file_get_contents("php://input");//$GLOBALS["HTTP_RAW_POST_DATA"];
			if (!empty($postStr))
			{
				write_log('weixin post='.$postStr);
				
				libxml_disable_entity_loader(true);
				$xmlObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
				
				$msgtype = strtolower($xmlObj->MsgType);
				if( $msgtype == "text" )
				{
					$this->OnTextMessage($xmlObj);
				}
				else if( $msgtype == "event" )
				{
					$event = strtolower($xmlObj->Event);
					switch( $event )
					{
						case 'subscribe': $this->OnSubscribe($xmlObj);break;
						case 'unsubscribe': $this->OnCancelSubscribe($xmlObj);break;
						case 'scan'://扫描二维码事件
							//	Service('WeixinLog')->WriteLog('key='.$xmlobj->EventKey.' Ticket='.$xmlobj->Ticket);
							//echo 'key='.$xmlobj->EventKey;
							break;
						case 'click'://点击菜单事件
							//Service('WeixinLog')->WriteLog('click KEY='.$eventkey);
							//if( 'vvy_operguide'== $eventkey )
							break;
					}
				}
			}
			else
				echo "";
		}
	}
	public function Valid($str)
	{
		write_log('checkSignature -------------->');
		if($this->checkSignature()){
			echo $str;
		}
	}
	
	
	//--------------------------------------------------------------------------
	//检查签名
	private function checkSignature()
	{
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];
		
		$token = $this->config['token'];
		$tmpArr = array($token, $timestamp, $nonce);
		// use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	//=== 事件响应（子类重载以下函数以实现事件响应）[BEGIN] ============================
	
	//文本消息
	public function OnTextMessage($xmlObj){ }
	
	//会员关注公众号
	public function OnSubscribe($xmlObj){ }
	
	//会员取消关注公众号
	public function OnCancelSubscribe($xmlObj){ }
	
	//=== 事件响应（子类重载以下函数以实现事件响应）[END] ==============================
}

