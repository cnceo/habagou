<?php
// =======================================================================
// | 百捷PHP框架(BJPHP)
// | ---------------------------------------------------------------------
// | 许可协议Apache2 ( http://www.apache.org/licenses/LICENSE-2.0 )
// | 技术支持QQ群：276228406
// | 微信公众号  ：百捷网络
// | 官方网站    ：http://www.baijienet.com
// =======================================================================

return [
	//=== 缺省的能力提供者
	'vendor'	=>	[
		//=== http服务
		'http'		=>	'bjphp.vendor.http.Http',
		
		//=== 路由
		'router'	=>	'bjphp.vendor.router.Router',
		
		//=== 日志
		'log'		=>	'bjphp.vendor.log.Log',
		
		//=== 会话
		'session'	=>	'bjphp.vendor.session.Session',
		
		//=== 微信回调处理器
		'weixin'	=>	'bjphp.vendor.weixin.callback',

		//功能层代理服务
		'feature_proxy' =>'bjphp.vendor.featureproxy.Proxy'
	],
	
	//=== 系统环境
	'system'	=>	[
		//系统初始化，静态函数集合
		'init'		=>	[
			'bjphp.vendor.session.Session.system_init',
			'feature.Biz.Auth.hasShut',
		],
		//系统清理，静态函数集合
		'clean'		=>	[
			'bjphp.vendor.session.Session.system_clean'
		],
		'exception'=>[
			 999=>'feature.Biz.Auth.shut_handler',
             1024=>'feature.Biz.Auth.login_handler',//登录拦截处理
             1025=>'',//未授权的处理
             1026=>'',//访问的页面存在，但没有方法，可能是非法访问
			 1027=>'',//访问的网址有对应的文件，但没有对应的类，一般是编程不合规范
		]
	],
	
	//=== 验证，全部为静态方法！
	'auth'		=>	[
		//验证是否已登录
		'is_login'	=>	'vendor.auth.Auth.is_login',
		
		//验证是否有权限
		'has_right'	=>	'bjphp.vendor.secure.auth.has_right',
		
		//验证登录
		//'login'		=>	'bjphp.vendor.secure.auth.login',
		
		//超级管理员登录
		'super_login'	=>	'bjphp.vendor.secure.auth.super_login',
		
		//需要登录时的处理，如果有多外，则依次执行，只要有一个返回true，则跳过缺省的异常处理
		//'login_handler'	=>	['vendor.auth.Auth.login_handler']
	],

	//=== 框架级的配置[END] =====================================================================
	
	//=== 站点级的配置[BEGIN]====================================================================
	//跟当前站点有关的配置，可以修改，但必须提供。
	//这里的配置有可能被vendor用到！
	'site'		=>	[
		//站点ID，用于区别不同的站点。当多个站点共用同一个数据库配置时，此值必须提供
		'siteid'	=>	'mysite',
		
		//站点名称，有时需要一个站点名称或者产品名称，则可以用此处的值
		'sitename'	=>	'mysite',

		//站点类型，分为：pc pad mobile response(自适应)，缺省为PC
		'sitetype'	=>	'pc',
		
		//虚拟目录名，如果站点部署在虚拟目录下，则必须配置此项！非虚拟目录则请保留为空
		'vdname'	=>	'',
		
		//部署模式，可取值 debug:调试环境 test:测试环境 deploy:生产环境
		'mode'		=>	'debug',
		
		//超级管理员帐号和密码。如果配置为空，则等同于取消超级管理员功能
		'superadmin'	=>	[
			'name'		=>	'super',
			'pwd'		=>	'96e79218965eb72c92a549dd5a330112'
		],
	],
	//=== http重载
		'http'		=>	[
				'template'	=>	[
						'exception'	=>	INDEX_PATH . '/tpl/exception/exception.html',
						'404'		=>	CLASS_ROOT . '/bjphp/vendor/http/404.html',
				]
		],
	//=== 站点级的配置[END]======================================================================
];
