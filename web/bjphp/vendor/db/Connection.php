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

class Connection
{
	private static $dbinstance	=	null;
	private static $config		=	null;
	private $pdo			=	null;
	private $transnum		=	0;
	private $inter_config		=	null;

	public static function master()
	{
		return self::instance(null);
	}
	public static function instance($key)
	{
		if( is_array(self::$dbinstance) && isset( self::$dbinstance[$key] ) )
		{
			return self::$dbinstance[$key];
		}
		
		$cfg = self::get_config();
		
		if( count($cfg) < 1 ) bjerror('db config error');
		
		if( $key == null || $key == '' ) $key = key($cfg);
		if( is_array(self::$dbinstance) && isset( self::$dbinstance[$key] ) )
		{
			return self::$dbinstance[$key];
		}
		if( !isset($cfg[$key]) ) bjerror('db config error'.$key);
		
		$db_obj = new Connection( $cfg[$key] );
		$db_obj->pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
		$db_obj->query('SET CHARACTER SET UTF8');
		
		
		if( self::$dbinstance == null ) self::$dbinstance = [];
		self::$dbinstance[ $key ] = $db_obj;
		
		return $db_obj;
	}
	public static function free()
	{
		if( self::$dbinstance != null ) self::$dbinstance = null;
	}
	public function query($str)
	{
		return $this->pdo->query($str);
	}
	public function prepare($str)
	{
		return $this->pdo->prepare($str);
	}
	
	public function __construct($db_config,$options=null)
	{
		try
		{
			$this->inter_config = $db_config;
			$str =
				'mysql:host='.$db_config['db_server'].';'.
				(isset($db_config['db_port']) ? 'port='.
					$db_config['db_port'].';':'').
				' dbname='.$db_config['db_db'];
			$this->pdo = new \PDO($str,$db_config['db_user'],$db_config['db_password'],$options);
			$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
		catch (\PDOException $e)
		{
			bjerror($e->getMessage());
		}
	}
	public function get_inter_config()
	{
		return $this->inter_config;
	}
	public static function get_config()
	{
		if( ! self::$config ) {
			self::$config = require(INDEX_PATH . '/config/dbconfig.php');
		}
		return self::$config;
	}
	public function beginTrans()
	{
		$this->transnum ++;
		if( $this->transnum == 1 )
		{
			$this->pdo->query('SET autocommit=0');
			$this->pdo->beginTransaction ();
		}
	}
	public function commit()
	{
		if( $this->transnum > 0 )
		{
			$this->transnum --;
			if( $this->transnum == 0 )
				$this->pdo->commit ();
		}
	}
	public function rollback()
	{
		if( $this->transnum > 0 )
		{
			$this->transnum = 0;
			$this->pdo->rollBack ();
		}
	}
	public function lastID()
	{
		return $this->pdo->lastInsertId();
	}
}

