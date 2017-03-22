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

/*
	用法例子：
		bjcreate(
			'bjphp.vendor.db.Dao',
			"select a.[name],a.[alias] from {m1} a where a.{id=} and a.{usertype>}",
			[
				"m1"		=>	"t_user",
				"id"		=>	3,
				"usertype"	=>	4
			]
		)->all();
		等价于执行：select name,alias from t_user where id=3 and usertype>4
*/
class Dao
{
	private $_db=null;
	private $statement = null;
	private $row=null;
	private $param=[];
	private static $config;
	
	public function __construct($sql,$args=[],$oConn=null)
	{
		if( $oConn != null ) $this->_db = $oConn;
		else $this->_db = bjstaticcall('bjphp.vendor.db.Connection.master');
		
		if( 'debug' == bjconfig('site')['mode'] && self::get_config()['log_sql'] )
		{
			bjwriteLog("准备执行SQL模板：".$sql);
			bjwriteLog(json_encode($args));
		}
		$str = $this->parse($sql,$args);
		$this->execute($str,$this->param,$this->_db);
	}
	public static function get_config()
	{
		if( ! self::$config ) {
			self::$config = require(INDEX_PATH . '/config/dbconfig.php');
		}
		return self::$config;
	}
	private function parse($sql,$args)
	{
		$args = bjarray($args);
		$sql = str_replace(['[',']'],'`',$sql);//mysql版本
		
		static $op_arr=["<=",">=","<>","=","<",">","!:","%",":","..","?"];
		
		$arr=[];
		$index=0;
		
		
		while(true)
		{
			$pos = strpos($sql,'{',$index);
			
			if( $pos === false )
			{
				$arr[] = substr($sql,$index);
				
				break;
			}
			else
			{
				$pos2 = strpos($sql,'}',$pos+1);
				if( $pos2 === false ) bjerror("错误的SQL模板语句");
				$arr[] = substr($sql,$index,$pos-$index);
				$index = $pos2+1;
				
				
				//解析表达式
				$pattern = substr($sql,$pos+1,$pos2-$pos-1);//表达式模板
				$alias="";//别名
				$op="";//操作符
				$field="";//字段
				
				foreach($op_arr as $p)
				{
					$sp = strpos($pattern,$p);
					if( $sp !== false )
					{
						$op = $p;
						$field = substr($pattern,0,$sp);
						
						if( $sp+strlen($p) < strlen($pattern) ) $alias = substr($pattern,$sp+strlen($p));
						else $alias = $field;
						
						break;
					}
				}
				if( $op == "" )
					$field = $pattern;
				if( $alias == '' ) $alias = $field;
				
				$arg_value = null;
				if( isset($args[$alias]) ) $arg_value = $args[$alias];
				
				switch($op)
				{
					case '>=':
					case '<=':
					case '<>':
					case '=':
					case '>':
					case '<':
					{
						$arr[] = '`'.$field.'`'.$op.'?';
						$this->param[] = $arg_value;
					}
						break;
					case '!:':
					{
						$v = $arg_value;
						$c = count($v);
						if( is_array($v) && $c > 0 )
						{
							$arr[] = '`'.$field.'` not in (';
							for($i=0;$i<$c;$i++)
							{
								if( $i ) $arr[] = ',';
								$arr[] = '?';
								$this->param[] = $v[$i];
							}
							$arr[] = ') ';
						}
						else
							$arr[] = '`'.$field."` <> '' ";
					}
						break;
					case ':':
					{
						$v = $arg_value;
						$c = count($v);
						if( is_array($v) && $c > 0 )
						{
							$arr[] = '`'.$field.'` in (';
							for($i=0;$i<$c;$i++)
							{
								if( $i ) $arr[] = ',';
								$arr[] = '?';
								$this->param[] = $v[$i];
							}
							$arr[] = ') ';
						}
						else
							$arr[] = '`'.$field."` ='' ";
					}
						break;
					case '%':
					{
						$arr[] = '`'.$field."` like '%".self::likefilter($arg_value)."%'";
					}
						break;
					case '..':
					{
						$arr[] = '`'.$field.'` between ?';
						$arr[] = ' and ';
						$arr[] = '?';
						$v = $arg_value;
						if( is_array($v) && count($v) == 2 )
						{
							$this->param[] = $v[0];
							$this->param[] = $v[1];
						}
						else
							bjerror("错误的SQL模板参数".$alias);
					}
						break;
					case '':
						if( $field == 'page' )
						{//分页
							$v =$arg_value;
							if( is_array($v) )
							{
								if( count($v) < 2 )
									$arr[] = "limit ".$v[0];
								else
								{
									$pageindex = (int)$v[0];
									$pagesize = (int)$v[1];
									if( $pageindex < 1 ) $pageindex = 1;
									$start = $pagesize * ($pageindex-1);
									$arr[] = "limit ".$start." , ".$pagesize;
								}
							}
							else
							{
								$arr[] = "limit ".$v;
							}
						}
						else
						{//元数据名
							$arr[] = $args[$field];
						}
						
						break;
					case '?':
						$arr[] = "?";//普通值
						$this->param[] = $args[$field];
						break;
				}
			}
		}
		return implode("",$arr);
	}
	private function execute($sql,$args,$oConn)
	{
		$count = count($args);
		if( 'debug' == bjconfig('site')['mode']  && self::get_config()['log_sql'] )
		{
			bjwriteLog('Execute SQL: '.$sql);
			for($i=0;$i<$count;$i++)
			{
				bjwriteLog(json_encode($args[$i]));
			}
		}
		
		$this->statement = $oConn->prepare($sql);
		
		
		for($i=0;$i<$count;$i++)
		{
			$this->statement->bindParam($i+1,$args[$i]);
		}
		
		
		$this->statement->execute();
		return $this;
	}
	
	public function next()
	{
		$this->row =  $this->statement->fetch(\PDO::FETCH_ASSOC);
		return $this->row ? true : false;
	}
	
	
	public function first()
	{
		if( $this->next() )
		{
			$obj = bjobject($this->row);
			$this->close();
			return $obj;
		}
		else return null;
	}
	public function close()
	{
		$this->statement->closeCursor();
		$this->statement = null;
	}
	
	public function all()
	{
		$ar = [];
		while($this->next())
		{
			if( count($this->row) > 0 ) $ar[] = bjobject($this->row);
			else $ar[] = null;
		}
		$this->close();
		return $ar;
	}
	
	public function __get($pn)
	{
		return $this->row[$pn];
	}
	
	public static function insert($t,$obj,$db=null)
	{
		$sql = "";
		$args=[];
		
		$sql = "insert into {_m1} (";
		$first = true;
		$obj = bjarray($obj);
		foreach($obj as $k => $v)
		{
			if( $first ) $first = false;
			else $sql .= ",";
			$sql .= "`".$k."`";
		}
		$sql .= ") values(";
		$first = true;
		foreach($obj as $k => $v)
		{
			if( $first ) $first = false;
			else $sql .= ",";
			$sql .= "{".$k."?}";
			$args[$k] = $v;
		}
		$sql .= ")";
		
		$args['_m1'] = $t;
		return new Dao($sql,$args,$db);
	}
	public static function update($t,$obj,$condObj,$db=null)
	{
		$sql = "";
		$args=[];
		
		$obj = bjarray($obj);
		$condObj = bjarray($condObj);
		$sql = "update {_m1} set ";
		$first = true;
		foreach($obj as $k => $v)
		{
			if( $first ) $first = false;
			else $sql .= ",";
			$sql .= "{".$k."=}";
			$args[$k] = $v;
		}
		$sql .= " where ";
		$first = true;
		foreach($condObj as $k => $v)
		{
			if( $first ) $first = false;
			else $sql .= " and ";
			$sql .= "{".$k."=".$k."2}";
			$args[$k."2"] = $v;
		}
		$args['_m1'] = $t;
		
		return new Dao($sql,$args,$db);
	}
	public static function remove($t,$condObj,$db=null)
	{
		$sql = "";
		$args=[];
		
		$condObj = bjarray($condObj);
		$sql = "delete from {_m1} ";
		if( is_array($condObj) && count($condObj) > 0 )
		{
			$first = true;
			$sql .= " where ";
			foreach($condObj as $k => $v)
			{
				if( $first ) $first = false;
				else $sql .= " and ";
				$sql .= "{".$k."=}";
				$args[$k] = $v;
			}
		}
		$args['_m1'] = $t;
		
		return new Dao($sql,$args,$db);
	}
	public static function loadOne($t,$objCond,$db=null)
	{
		$sql = "";
		$args=[];
		
		$objCond = bjarray($objCond);
		$sql = "select * from {_m1} ";
		if( is_array($objCond) && count($objCond) > 0 )
		{
			$first = true;
			$sql .= " where ";
			foreach($objCond as $k => $v)
			{
				if( $first ) $first = false;
				else $sql .= " and ";
				$sql .= "{".$k."=}";
				$args[$k] = $v;
			}
		}
		$args['_m1'] = $t;
		
		
		return (new Dao($sql,$args,$db))->first();
	}
	public static function loadAll($t,$objCond,$db=null)
	{
		$sql = "";
		$args=[];
		
		$objCond = bjarray($objCond);
		$sql = "select * from {_m1} ";
		if( is_array($objCond) && count($objCond) > 0 )
		{
			$first = true;
			$sql .= " where ";
			foreach($objCond as $k => $v)
			{
				if( $first ) $first = false;
				else $sql .= " and ";
				$sql .= "{".$k."=}";
				$args[$k] = $v;
			}
		}
		
		$args['_m1'] = $t;
		
		return (new Dao($sql,$args,$db))->all();
	}
	public static function selectCount($t,$objCond,$db=null)
	{
		$sql = "";
		$args=[];
		
		$objCond = bjarray($objCond);
		$sql = "select count(*) as c from {_m1} ";
		if( is_array($objCond) && count($objCond) > 0 )
		{
			$first = true;
			$sql .= " where ";
			foreach($objCond as $k => $v)
			{
				if( $first ) $first = false;
				else $sql .= " and ";
				$sql .= "{".$k."=}";
				$args[$k] = $v;
			}
		}
		
		$args['_m1'] = $t;
		
		return (int)( (new Dao($sql,$args,$db))->first()->c );
	}
	public static function likefilter($str)
	{
		$s = str_replace(["'",'"',';','--','(',')','[',']','{','}'],'',$str);
		$s = preg_replace('/char/i','',$s);
		
		return $s;
	}
	public static function link_condition($arr,$op="and")
	{
		$count = count($arr);
		$sql = " ";
		if( $count > 0 )
		{
			for($i=0;$i<$count;$i++)
			{
				if( $i > 0 ) $sql .= " ".$op." ";
				$sql .= "(".$arr[$i].")";
			}
		}
		else
		{
			$sql .= "1=1";
		}
		return $sql." ";
	}
}

