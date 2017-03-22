<?php
// =======================================================================
// | 百捷PHP框架(BJPHP)
// | ---------------------------------------------------------------------
// | 许可协议Apache2 ( http://www.apache.org/licenses/LICENSE-2.0 )
// | 技术支持QQ群：276228406
// | 微信公众号  ：百捷网络
// | 官方网站    ：http://www.baijienet.com
// =======================================================================

//系统路径定义
define( 'INDEX_PATH', dirname(__FILE__) );	//index所在目录
define( 'CLASS_ROOT', INDEX_PATH . '/web');	//类文件的根目录


//框架缺省实现（可通过配置来重载）
require CLASS_ROOT . '/bjphp/core/core.php';

bjhttp()->run();

