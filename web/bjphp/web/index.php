<?php

bjload('vendor.ui.page');
class web_index extends vendor_ui_page
{
	public function display()
	{
		if( http()->Request()->Param('code','') != '' )
		{
			$this->Assign('is_weixin',1);
			//微信自动登录
			write_log("有微信code,现在准备自动登录");
			//bjapi('crmorg.user.WxLogin',[]);
		}
		else $this->Assign('is_weixin',0);
		
		//后台缺省跳转地址，可根据项目需要跳到其它页面
		$this->redirect(fixurl('/myconsole'));
	}
}






