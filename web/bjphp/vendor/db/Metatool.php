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

class Metatool
{
	//表结构转换
	public static function table2Meta($tablename,$oConn=null)
	{
		$meta = [];
		if( $oConn == null ) $oConn = bjstaticcall('bjphp.vendor.db.Connection.master');
		
		//表名
		$meta['TableName'] = $tablename;
		//字段
		$fields=[];
		$all = bjcreate('bjphp.vendor.db.Dao',
			"SELECT * FROM information_schema.columns "
			."WHERE {table_name=} AND {table_schema=}",
			
			[
				'table_name'=>$tablename,
				'table_schema'=> $oConn->get_inter_config()['db_db']
			],
			$oConn
		)->all();
		foreach($all as $a) $fields[] = self::parseField($a);
		
		$meta['Fields'] = $fields;
		//主键和索引
		$ret = self::readKeys($tablename,$oConn);
		$meta['PK'] = $ret['pk'];
		$meta['UK'] = $ret['uks'];
		return $meta;
	}
	
	//创建表结构
	public static function createTable($meta,$oConn=null)
	{
		if( $oConn == null ) $oConn = bjstaticcall('bjphp.vendor.db.Connection.master');
		
		$sql = "";
		$sql .= "create table `".$meta['TableName']."` (\n";
		
		$model = bjcreate('bjphp.vendor.db.Model',$meta);
		$fields = $meta['Fields'];
		$fieldcount = count($fields);
		
		for($i=0;$i<$fieldcount;$i++)
		{
			if( $i ) {$sql .= ",\n";}
			$sql .= self::field2ColumnStr($model,$fields[$i]);
		}
		
		if( isset($meta['PK']) && count($meta['PK']) > 0 )
		{
			$pk = $meta['PK'];
			$sql .= ",\n";
			$sql .= "PRIMARY KEY (";
			$j = count($pk);
			for($i=0;$i<$j;$i++)
			{
				if( $i > 0 ) $sql .= ',';
				$sql .= "`".$pk[$i]."`";
			}
			$sql .= ")\n";
		}
		
		if( isset($meta['UK']) && count($meta['UK']) > 0 )
		{
			$uk = $meta['UK'];
			foreach($uk as $k => $v)
			{
				$sql .= ",\n";
				$sql .= "UNIQUE INDEX `".$k."` (";
				$count = count($v);
				for($x=0;$x < $count;$x++)
				{
					if( $x > 0 ) $sql .= ",";
					$sql .= "`".$v[$x]."`";
				}
				$sql .= ")";
			}
		}
		
		$sql .= ")";
		$sql .= "\nCOLLATE='utf8_general_ci'\n";
		$sql .= "ENGINE=InnoDB;";
		
		bjcreate('bjphp.vendor.db.Dao',$sql,[],$oConn)->first();
	}
	
	//更新表结构
	public static function updateTable($meta,$remove=false,$oConn=null)
	{
		if( $oConn == null ) $oConn = bjstaticcall('bjphp.vendor.db.Connection.master');
		
		$meta_old = self::table2Meta($meta['TableName'],$oConn);
		
		$model = bjcreate('bjphp.vendor.db.Model',$meta);
		$model_old = bjcreate('bjphp.vendor.db.Model',$meta_old);
		
		//字段的更新=========================================================
		foreach ($meta['Fields'] as $field)
		{
			$exists = false;
			foreach($meta_old['Fields'] as $field_old)
			{
				if( strcasecmp($model->fieldName($field), $model_old->fieldName($field_old)) == 0 )
				{
					$exists = true;
					//更新字段
					if( self::isFieldChange($model,$field,$model_old,$field_old) )
					{
						//write_log($model->FieldName($field).' changed!');
						self::updateDataWhenChange($model_old,$field_old,$model,$field,$oConn);
						bjcreate('bjphp.vendor.db.Dao','alter table `'.$meta['TableName'].'` CHANGE COLUMN `'
							.$model->FieldName($field).'` '
							.self::field2ColumnStr($model,$field),
							[],$oConn
						)->first();
					}
					break;
				}
			}
			if( !$exists )
			{
				//新增字段
				bjcreate('bjphp.vendor.db.Dao','alter table `'.$meta['TableName'].'` add column '
					.self::field2ColumnStr($model,$field),[],$oConn
				)->First();
			}
		}
		if( $remove )
		{
			//删除字段（危险！！）===================================
			foreach($meta_old['Fields'] as $field_old)
			{
				$exists = false;
				foreach ($meta['Fields'] as $field)
				{
					if( strcasecmp($model->fieldName($field), $model_old->fieldName($field_old)) == 0 )
					{
						$exists = true;
						break;
					}
				}
				if( !$exists )
				{
					bjcreate('bjphp.vendor.db.Dao','alter table `'.$meta['TableName'].'` drop column `'
						.$meta_old->fieldName($field_old)."`",
						[],
						$oConn
					)->first();
				}
			}
		}
		
		//PK的更新===========================================================
		if(!isset($meta['PK']) || count($meta['PK']) < 1 )
		{
			if( isset($meta_old['PK']) && count($meta_old['PK']) > 0 )
			{
				//删除PK
				bjcreate('bjphp.vendor.db.Dao',"ALTER TABLE `".$meta['TableName']."` "
					."DROP PRIMARY KEY;",
					[],
					$oConn
				)->first();
			}
		}
		else
		{
			if(!isset($meta_old['PK']) || count($meta_old['PK']) < 1 )
			{
				//添加PK
				bjcreate('bjphp.vendor.db.Dao',"ALTER TABLE `".$meta['TableName']."` "
					."ADD PRIMARY KEY ("
					.self::fieldArraySql($meta['PK'])
					.");",
					[],
					$oConn
				)->First();
			}
			else
			{
				if( !self::isSameStringArray($meta['PK'],$meta_old['PK']) )
				{
					//修改PK
					bjcreate('bjphp.vendor.db.Dao',"ALTER TABLE `".$meta['TableName']."` "
						."DROP PRIMARY KEY,"
						."ADD PRIMARY KEY ("
						.self::fieldArraySql($meta['PK'])
						.");",
						[],
						$oConn
					)->first();
				}
			}
		}
		
		//UK的更新================================================
		//先删除不存在了的UK
		foreach($meta_old['UK'] as $k => $v)
		{
			$exists = false;
			foreach($meta['UK'] as $k2 => $v2)
			{
				if( $k2 == $k )
				{
					$exists = true;
					break;
				}
			}
			if( !$exists )
			{
				bjcreate('bjphp.vendor.db.Dao',"ALTER TABLE `".$meta['TableName']."` "
					."DROP INDEX `".$k."`;",
					[],
					$oConn
				)->first();
			}
		}
		//再添加或修改UK
		foreach($meta['UK'] as $k2 => $v2)
		{
			$exists = false;
			foreach($meta_old['UK'] as $k => $v)
			{
				if( $k == $k2 )
				{
					$exists = true;
					if( !self::isSameStringArray($v,$v2) )
					{
						//修改UK
						bjcreate('bjphp.vendor.db.Dao',"ALTER TABLE `".$meta['TableName']."` "
							."DROP INDEX `".$k."`,"
							."ADD UNIQUE INDEX `".$k2."` ("
							.self::fieldArraySql($v2)
							.");",
							[],
							$oConn
						)->First();
					}
					break;
				}
			}
			if( !$exists )
			{
				//添加UK
				bjcreate('bjphp.vendor.db.Dao',"ALTER TABLE `".$meta['TableName']."` "
					."ADD UNIQUE INDEX `".$k2."` ("
					.self::fieldArraySql($v2)
					.");",
					[],
					$oConn
				)->First();
			}
		}
	}
	
	
	
	//列出所有的表
	public static function listTables($oConn = null)
	{
		if( $oConn == null ) $oConn = bjstaticcall('bjphp.vendor.db.Connection.master');
		$all = bjcreate('bjphp.vendor.db.Dao','show tables',[],[],$oConn)->All();
		$ret = [];
		foreach($all as $v) $ret[] = current($v);
		return $ret;
	}
	
	//解析字段
	private static function parseField($field)
	{
		$field = bjarray($field);
		$f=[];
		$f[] = $field["COLUMN_NAME"];//名称
		$type = $field["DATA_TYPE"];
		if( $type === "varchar" || $type === "char" ) $type = "string";
		$f[] = $type;//类型
		$len="";
		if( $type === "decimal" ) $len = $field["NUMERIC_PRECISION"].",".$field["NUMERIC_SCALE"];
		else if( $type === "string") $len = (int)$field["CHARACTER_MAXIMUM_LENGTH"];
		else if( $type === "date" || $type === "datetime" || $type === "time" || $type === "timestamp" )
			$len = 0;
		else
			$len = (int)$field["NUMERIC_PRECISION"];
		$f[] = $len;//长度
		if( $field["IS_NULLABLE"] === "YES" ) $f[] = null;
		else $f[] = 1;//NULL
		
		if( $type === "int" && $field["EXTRA"] === "auto_increment" ) $f[] = "auto";//自增长
		else
			$f[] = $field["COLUMN_DEFAULT"];//缺省值
		$f[] = $field["COLUMN_COMMENT"];//注释
		return $f;
	}
	
	//判断两个字符串数组是否相同
	private static function isSameStringArray($a1,$a2)
	{
		$c1 = count($a1);
		$c2 = count($a2);
		if( $c1 != $c2 ) return false;
		
		for($i=0;$i<$c1;$i++)
		{
			$exists = false;
			for($j=0;$j<$c2;$j++)
			{
				if( $a1[$i] == $a2[$j] )
				{
					$exists = true;
					break;
				}
			}
			if( !$exists ) return false;
		}
		return true;
	}
	
	private static function readKeys($tablename,$oConn)
	{
		$a = bjcreate('bjphp.vendor.db.Dao',"show index from ".$tablename,[],[],$oConn)->All();
		
		$pk=[];
		$uks = [];
		$uk=[];
		$name="";
		$count = count($a);
		for($i=0;$i<$count;$i++)
		{
			$x = bjarray($a[$i]);
			if( $x['Key_name'] === "" ) continue;
			if( $x['Key_name'] === 'PRIMARY' )
			{
				$pk[] = $x['Column_name'];
			}
			else
			{
				if($name === "" )
				{
					$name = $x['Key_name'];
					$uk[] = $x['Column_name'];
				}
				else
				{
					if( $name != $x['Key_name'] )
					{
						$uks[$name] = $uk;
						$uk = [];
						$name = $x['Key_name'];
					}
					else
					{
						$uk[] = $x['Column_name'];
					}
				}
			}
		}
		if( $name != "" ) $uks[$name] = $uk;
		return ['pk'=>$pk,'uks'=>$uks];
	}
	
	private static function field2ColumnStr($model,$field)
	{
		$str='';
		
		$name = $model->fieldName($field);
		$sType = $model->dataType($field);
		if( $sType === "string" ) $sType = "varchar";
		
		//mysql对bit操作太麻烦，用int代替
		if( $sType === 'bit' ) $sType = 'int';
		
		//name
		$str .= '`'.$name.'` ';
		//datatype
		$str .= strtoupper( $sType );
		
		if( $sType === 'int' )
			$str .= '('.$model->fieldLength($field).')';
		else if( $sType === 'decimal' )
			$str .= '('.$model->fieldLength($field).','.$model->fieldPrecision($field).')';
		else if($sType == 'bit' )
			$str .= '(1)';
		else if( $sType === 'date' || $sType === 'time' || $sType === 'datetime' || $sType === 'timestamp' )
		{
			//do nothing
		}
		else if( strpos($sType,'text') !== false )
		{
			//do nothing
		}
		else if( strpos($sType,'varchar') !== false )
			$str .= '('.$model->fieldLength($field).')';
		else
		{
			$str .= '('.$model->fieldLength($field).')';
		}
		
		//null
		if( !$model->canNull($field) ) $str .= " not ";
		$str .= ' null ';
		
		$defaultvalue = $model->defaultValue($field);
		if( !$model->canNull($field) && ($defaultvalue == null || $defaultvalue == 'null') )
		{
			//no default
		}
		else
		{
			//default
			if( $sType === 'date' || $sType === 'time' || $sType === 'datetime' || $sType == 'timestamp' )
			{
				if( $defaultvalue === null || $defaultvalue === 'null' || $defaultvalue === '' )
					$str .= " DEFAULT  NULL ";
			}
			else if( $sType == 'bit' || $sType == 'int' || $sType == 'tinyint' || $sType == 'bigint' || $sType == 'smallint' || $sType == 'mediumint' )
			{
				if( $defaultvalue === "auto" ) $str .= ' AUTO_INCREMENT';
				/*else if( ( $defaultvalue == '0' || $defaultvalue == '1' ) && $sType == 'bit' )
				{
					if( $defaultvalue == '1' ) $str .= " DEFAULT b'1'";
					else $str .= " DEFAULT b'0'";
				}*/
				else $str .= " DEFAULT '". (int)$defaultvalue."'";
			}
			else if( $sType == 'mediumtext' || $sType == 'longtext' )
			{
				//no default
			}
			else
			{
				if( is_null($defaultvalue) ) $str .= " DEFAULT null";
				else $str .= " DEFAULT '".$defaultvalue."'";
			}
		}
		
		
		
		//comment
		if( '' != $field[5] )
		{
			$str .= ' COMMENT '."'".$field[5]."'";
		}
		
		return $str;
	}
	
	
	private static function fieldArraySql($arr_name)
	{
		$count = count($arr_name);
		$str = "";
		for($i=0;$i<$count;$i++)
		{
			if( $i > 0 ) $str .= ",";
			$str .= "`".$arr_name[$i]."`";
		}
		return $str;
	}
	
	private static function isFieldChange($m1,$f1,$m2,$f2)
	{
		//数据类型
		$t1 = $m1->dataType($f1);
		$t2 = $m2->dataType($f2);
		if( $t1 != $t2 )
		{
			bjwriteLog("数据类型不同：t1={$t1} t2={$t2}");
			return true;
		}
		
		//数据长度
		if( $t1 == 'string' )
		{
			if( $m1->fieldLength($f1) != $m2->fieldLength($f2) )
			{
				bjwriteLog("数据长度不同：f1=".$m1->fieldLength($f1)." f2=".$m2->fieldLength($f2));
				return true;
			}
		}
		if( $t1 === "decimal" )
		{
			if( $m1->fieldPrecision($f1) != $m2->fieldPrecision($f2) ) return true;
		}
		
		//是否null
		if( $m1->canNull($f1) != $m2->canNull($f2) )
		{
			bjwriteLog('can null不同');
			return true;
		}
		
		//缺省值
		if( $t1 != "mediumtext" && $t1 != "longtext" )
		{//这两种类型不能有缺省值
			if( $m1->defaultValue($f1) != $m2->defaultValue($f2) )
			{
				bjwriteLog("default value不同 d1:".$m1->defaultValue($f1)." d2:".$m2->defaultValue($f2));
				return true;
			}
		}
		
		//注释
		if( $f1[5] != $f2[5] )
		{
			bjwriteLog("注释不同:c1:{$f1[5]} c2:{$f2[5]}");
			return true;
		}
		
		return false;
	}
	
	//更改字段前转换数据
	private static function updateDataWhenChange($model_old,$fOld,$model_new,$fNew,$oConn=null)
	{
		//write_log('enter UpdateDataWhenChange:cannull:'.json_encode($model_old->CanNull($fOld))
		//	.' cannull_new:'.json_encode($model_new->CanNull($fNew)));
		//从null到非null的转换
		if( $model_old->canNull($fOld) && !$model_new->canNull($fNew) )
		{
			$sql = "update ".$model_new->tableName()." set `".$model_new->fieldName($fNew)."`=";
			$dt = $model_old->dataType($fOld);
			if( $model_new->isIntType( $model_new->dataType($fNew) ) ) $sql .= "0";
			else if( $dt == 'date' )
			{
				$sql .= "'".date('Y-m-d')."' where isnull(`".$model_new->fieldName($fNew)."`)";
			}
			else if( $dt == 'datetime' || $dt == 'timestamp')
				$sql .= "'".date('Y-m-d H:i:s')."' where isnull(`".$model_new->fieldName($fNew)."`)";
			else if( $dt == 'time')
				$sql .= "'".date('H:i:s')."' where isnull(`".$model_new->fieldName($fNew)."`)";
			else
				$sql .= "'' where isnull(`".$model_new->fieldName($fNew)."`)";
			bjcreate('bjphp.vendor.db.Dao',$sql,[],$oConn)->First();
		}
	}
}

