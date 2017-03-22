<?php

return [
	//第一个数据库实例或名为master的实例就是缺省数据库！
	'master'	=>	[
		'db_type'		=>	'mysql',
		'db_server'		=>	'localhost',
		'db_db'			=>	'habagou',
		'db_user'		=>	'root',
		'db_password'		=>	'',
		'db_port'		=>	3306,
	],
	//如果有其它数据库，则在下面按例子配置
	/*'instance1'	=>	[
		'db_type'		=>	'mysql',
		'db_server'		=>	'localhost',
		'db_db'			=>	'db2',
		'db_user'		=>	'root',
		'db_password'		=>	'',
		'db_port'		=>	3306,
	],*/
	//是否记录SQL
	'log_sql'	=>	true,
];

