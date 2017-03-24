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
//数据类型:
//	- 整数类：  tinyint int smallint bigint
//	- 字符串类：string text mediumtext longtext
//	- 日期类：  date time datetime
//	- 货币类：  decimal
//	- 浮点类：  float
//长度：
//	0 - 表示缺省长度
//	(10,2) - 这种格式表示长度为10，精度为2
//缺省值：
//	auto - 表示自增长字段
//	null - 表示不设缺省值
//	其它值 - 表示指定缺省值
//是否NULL：
//	null - 表示该字段可以为null
//	1 - 其它非null值（常用1表示）表示不允许为null
//	- 不允许为null，但缺省值是null时表示该字段为“必填”
//显示名：
//	- 名称后面用括号括起来的是显示名（或称为别名）
//验证：
//	- 数据类型后面括号括起来的是验证
//	- 有多个验证时，用逗号隔开
//	- 支持的验证有：
//		mobile - 手机号码
//		email  - 邮箱格式
//		digit  - 数字格式
//		idcard - 身份证格式
//		xss    - 防xss攻击过滤
//---------------------------------------------------------------------
//名称		类型		长度	NULL 	缺省值		注释
//---------------------------------------------------------------------
class Model
{
	private $meta;
	function __construct($_meta)
	{
		$this->meta = $_meta;
	}
	public function tableName()
	{
		return $this->meta['TableName'];
	}
	public function delFlag()
	{
		return $this->meta['DELFLAG'];
	}
	public function meta()
	{
		return $this->meta;
	}
	
	//解析字段名
	public function fieldName($field)
	{
		$str = $field[0];
		$pos = strpos($str,"(");
		if( $pos === false ) return $str;
		else return substr($str,0,$pos);
	}
	//解析字段显示名
	public function fieldDisplayName($field)
	{
		$str = $field[0];
		$pos = strpos($str,"(");
		if( $pos === false ) return $str;//用名称作显示名
		else
		{
			$pos2 = strpos($str,")");
			if( $pos2 === false ) return substr($str,$pos+1);
			else
				return substr($str,$pos+1,$pos2-$pos-1);
		}
	}
	//解析字段长度
	public function fieldLength($field)
	{
		$len = $field[2];
		if( is_string($len) )
		{
			$pos = strpos($len,",");
			if( $pos === false ) return (int)$len;
			return (int)substr($len,1,$pos-1);
		}
		else
		{
			if( (int)$len == 0 )
			{
				$datatype = $this->dataType($field);
				if( $datatype == "int" ) return 11;
				else if( $datatype == "datetime" ) return 8;
				else if( $datatype == "tinyint" ) return 1;
				else return 10;//??其它类型尽量不要用0
			}
			else return (int)$len;
		}
	}
	//解析字段精度
	public function fieldPrecision($field)
	{
		$len = $field[2];
		if( is_string($len) )
		{
			$pos = strpos($len,",");
			if( $pos === false ) return 0;
			return (int)substr($len,$pos+1,strlen($len)-$pos-1);
		}
		else return 0;
	}
	//字段是否允许null
	public function canNull($field)
	{
		return $field[3] === null ? true : false;
	}
	//字段缺省值
	public function defaultValue($field)
	{
		return $field[4];
	}
	//得到字段类型
	public function dataType($field)
	{
		$str = $field[1];
		$pos = strpos($str,"(");
		if( $pos === false ) return $str;
		else return substr($str,0,$pos);
	}
	//得到字段格式
	public function dataFormat($field)
	{
		$str = $field[1];
		$pos = strpos($str,"(");
		if( $pos === false ) return "";
		$pos2 = strpos($str,")");
		if( $pos2 === false ) return "";
		return substr($str,$pos+1,$pos2-$pos-1);
	}
	//=============== 验证 ============================================
	public function verify($input,$isadd=true)
	{
		$fields = $this->meta['Fields'];
		foreach($fields as $field)
		{
			$this->verifyField($input,$field,$isadd);
		}
	}
	//字段验证
	private function verifyField($input,$field,$isadd)
	{
		$input = bjobject($input);
		$name = $this->fieldName($field);
		$sType = $this->dataType($field);
		$disp = $this->fieldDisplayName($field);
		$dataformat = $this->dataFormat($field);
		
		//验证必填
		if( $isadd && !$this->canNull($field) && is_null($this->defaultValue($field)) && !property_exists($input,$name) )
		{
			bjerror($disp.'是必填项');
		}
		
		
		if( property_exists($input,$name) ) {
			//必填
			$this->verifyMustFill($input, $field, $name, $disp, $sType);
			
			//验证长度（暂时只支持字符串长度验证）
			if ($sType == "string") {
				$len = $this->fieldLength($field);
				if (strlen($input->{$name}) > $len)
					bjerror($disp . "长度超过了限制");
			}
			
			//特殊格式验证-------
			if ("" != $dataformat)
			{
				$verify_arr = explode(',',$dataformat);
				foreach($verify_arr as $vr)
				{
					if( ! empty($vr) ) {
						$input->{$name} = bjcreate(
							'bjphp.vendor.db.Verify',
							$input->{$name},
							$disp)->{$vr}()->Text();
					}
				}
			}
			else
			{
				//缺省验证
				if( $this->isIntType($sType) )//整数字段必须是整数
				{
					if( property_exists($input,$name) )
						bjcreate('bjphp.vendor.db.Verify',$input->{$name},$disp)->digit();
				}
			}
		}
	}
	//验证必填
	private function verifyMustFill($input,$field,$name,$disp,$type)
	{
		
		if( !$this->canNull($field) && is_null($this->defaultValue($field)) )
		{
			bjwriteLog('field= '.$name.' default='.json_encode($this->defaultValue($field)));
			if( !property_exists( $input, $name  ) )
				bjerror($disp.'是必填项');
			
			if( $this->isStringType($type) )
			{
				if( '' == $input->{ $name } )
					bjerror($disp.'是必填项');
			}
		}
	}
	//是否为字符型字段
	public function isStringType($sType)
	{
		return $sType == "string" || $sType == "mediumtext" || $sType == "longtext" ? true : false;
	}
	//判断是否为整数类
	public function isIntType($type)
	{
		return $type === "int" || $type === "tinyint" || $type === "bigint" ? true : false;
	}
	//判断是否为日期时间类
	public function isDateTimeType($type)
	{
		return $type === "date" || $type === "datetime" || $type === "time" ? true : false;
	}
	
	//================ CRUD ===============================
	//过滤表中不存在的字段
	private function input2Param($input,$isread=false)
	{
		$input = bjobject($input);
		$param = bjobject([]);
		foreach($this->meta['Fields'] as $f)
		{
			$fieldname = $this->fieldName( $f );
			//write_log('fieldname:'.$fieldname);
			if( property_exists($input,$fieldname) )
			{
				//write_log('has fieldname:'.$fieldname);
				if( $this->isIntType( $this->dataType($f) ) )
				{
					if( $this->defaultValue($f) === "auto" )
					{//filter autoincrease field
						if( $isread ) $param->{ $fieldname } = $input->{ $fieldname };
						//write_log('autoincre fieldname:'.$fieldname);
					}
					else
					{
						$param->{ $fieldname } = $input->{ $fieldname };
					}
				}
				else if( $this->isDateTimeType( $this->dataType($f) ) )
				{
					if( $input->{$fieldname} ) $param->{ $fieldname } = $input->{ $fieldname };
				}
				else
					$param->{ $fieldname } = $input->{ $fieldname };
			}
		}
		return $param;
	}
	//增加一个元对象
	public function save($input,$oConn=null)
	{
		//write_log('before save:'.json_encode($input));
		$param = $this->input2Param($input);
		
		$this->verify($param,true);
		bjstaticcall('bjphp.vendor.db.Dao.insert',$this->meta['TableName'],$param,$oConn);
		return $this;
	}
	//增加一个元对象
	public function add($input,$oConn=null)
	{
		return $this->save($input,$oConn);
	}
	//删除
	public function remove($cond=[],$oConn=null)
	{
		//$t = new ds($this->meta['TableName']);
		$param = $this->input2Param($cond,true);
		$delflag = "";
		if( isset($this->meta['DELFLAG']) ) $delflag = $this->meta['DELFLAG'];
		if( "" != $delflag )
			bjstaticcall('bjphp.vendor.db.Dao.Update',$this->meta['TableName'],[$delflag=>1],$param,$oConn);
		else
			bjstaticcall('bjphp.vendor.db.Dao.Remove',$this->meta['TableName'],$param,$oConn);
		return true;
	}
	
	//更新
	public function update($obj,$cond,$oConn=null)
	{
		$this->verify($obj,false);
		$param = $this->input2Param($obj);
		
		//write_log('updating '.$this->meta['TableName'].' param:'.json_encode($param));
		bjstaticcall('bjphp.vendor.db.Dao.update',$this->meta['TableName'],$param,$cond,$oConn);
		return true;
	}
	//读取
	public function load($cond=[],$fields=[],$oConn=null)
	{
		$cond = bjarray($cond);
		$delflag = "";
		if( isset($this->meta['DELFLAG']) ) $delflag = $this->meta['DELFLAG'];
		if( "" != $delflag && !isset($cond[$delflag]) ) $cond[$delflag] = 0;
		
		$sql = "select ";
		$count = count($fields);
		if( $count < 1 ) $sql .= "*";
		else
		{
			for($i=0;$i<$count;$i++)
			{
				if( $i ) $sql .= ",";
				$sql .= $fields[$i];//支持表达式！ "t.[".$fields[$i]."]";
			}
		}
		$sql .= " from {m1} t ";
		$args=['m1'=>$this->tableName()];
		$countCond = count($cond);
		if( $countCond > 0 )
		{
			$sql .= "where ";
			$first = true;
			foreach($cond as $k => $v)
			{
				$exists = false;
				foreach($this->meta['Fields'] as $f)
				{
					$fieldname = $this->fieldName( $f );
					if( isset($cond[$fieldname]) )
					{
						$exists = true;
						break;
					}
				}
				if( !$exists ) continue;
				
				if( $first ) $first = false;
				else $sql .= " and ";
				$sql .= "t.{".$k."=}";
				$args[$k] = $v;
			}
		}
		return bjcreate('bjphp.vendor.db.Dao',$sql,$args)->first();
	}
	//读取多条
	public function loadAll($cond=[],$fields=[],$oConn=null)
	{
		$cond = bjarray($cond);
		$delflag = "";
		if( isset($this->meta['DELFLAG']) ) $delflag = $this->meta['DELFLAG'];
		if( "" != $delflag && !isset($cond[$delflag]) ) $cond[$delflag] = 0;
		
		$sql = "select ";
		$count = count($fields);
		if( $count < 1 ) $sql .= "*";
		else
		{
			for($i=0;$i<$count;$i++)
			{
				if( $i ) $sql .= ",";
				$sql .= $fields[$i];//支持表达式！ "t.[".$fields[$i]."]";
			}
		}
		$sql .= " from {m1} t ";
		$args=['m1'=>$this->tableName()];
		$countCond = count($cond);
		if( $countCond > 0 )
		{
			$sql .= "where ";
			$first = true;
			foreach($cond as $k => $v)
			{
				$exists = false;
				foreach($this->meta['Fields'] as $f)
				{
					$fieldname = $this->fieldName( $f );
					if( isset($cond[$fieldname]) )
					{
						$exists = true;
						break;
					}
				}
				if( !$exists ) continue;
				
				if( $first ) $first = false;
				else $sql .= " and ";
				$sql .= "t.{".$k."=}";
				$args[$k] = $v;
			}
		}
		return bjcreate('bjphp.vendor.db.Dao',$sql,$args)->All();
	}
	//唯一ID生成器
	public static function genid($salt='')
	{
		return md5(uniqid("bjphp".mt_rand(), true)."id".$salt.rand());
	}
	
	//得到表单提交的对象
	public function getFormMeta()
	{
		$obj = bjobject([]);
		$fields = $this->meta['Fields'];
		bjwriteLog('fields='.json_encode($fields));
		$req = bjhttp()->request();
		foreach($fields as $field)
		{
			$field_name = $this->fieldName( $field );
			$val = $req->getParam($field_name,null);
			if( ! is_null($val) ) $obj->{ $field_name } = $val;
			//else write_log(' --- cannot found field:'.$field_name);
		}
		return $obj;
	}

	//构造一个缺省对象
	public function instance()
	{
		$obj = bjobject([]);
		$fields = $this->meta['Fields'];
		
		foreach($fields as $field)
		{
			$field_name = $this->fieldName( $field );
			$obj->{ $field_name } = $this->defaultValue( $field );
		}
		return $obj;
	}
}
