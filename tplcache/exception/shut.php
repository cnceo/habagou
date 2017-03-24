<?php
bjload("bjphp.vendor.ui.CachePage");
class exception_shut_cache extends \bjphp\vendor\ui\CachePage
{
	public function run($uicontext)
	{
		$this->_root =$uicontext;
		$this->_this =$this->_root;
		
		$this->do_html("<html>\r\n<head>\r\n    <meta charset=\"utf-8\" />\r\n    <title>维护</title>\r\n    <meta content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0\" name=\"viewport\">\r\n    <link rel=\"stylesheet\" type=\"text/css\" href=\"/res/css/hui_weufu.css\"/>\r\n</head>\r\n<body>\r\n<div id=\"login\">\r\n    <div class=\"logo\"></div>\r\n    <div class=\"tiShi\"></div>\r\n</div>\r\n\r\n</body>\r\n</html>");
	}
}
