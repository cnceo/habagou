<?php
// =======================================================================
// | 百捷PHP框架(BJPHP)
// | ---------------------------------------------------------------------
// | 许可协议Apache2 ( http://www.apache.org/licenses/LICENSE-2.0 )
// | 技术支持QQ群：276228406
// | 微信公众号  ：百捷网络
// | 官方网站    ：http://www.baijienet.com
// =======================================================================

namespace bjphp\vendor\file;

class Upload
{
	//
	public function run()
	{
		try
		{
			$path = '';
			
			$api = bjhttp()->request()->Param('biz','');
			if( $api != '' )
			{//业务功能附件上传
				$ret = $this->saveFile( bjhttp()->request()->Param('fieldname','') );
				
				bjhttp()->request()->AddParam($ret);
				
				bjapi($api,bjobject( bjhttp()->request()->Params() ) );
				
				$path = $ret['_path'];
			}
			else
			{//富文本编辑器的上传
				$path = $this->saveFile(bjhttp()->request()->Param('fieldname',''))['_path'];
			}
			echo json_encode(['status'=>0,'url'=>$path]);
		}
		catch (\BjException $ex)
		{
			$code = $ex->getCode();
			if( !$code ) $code = 1;
			$ret=['status'=>$code,'message'=>$ex->getMessage()];
			echo json_encode($ret);
		}
		catch(\Exception $e)
		{
			$code = $e->getCode();
			if( !$code ) $code = 1;
			$ret=['status'=>$code,'message'=>$e->getMessage()];
			echo json_encode($ret);
		}
	}
	
	public function saveFile($fieldname)
	{
		$maxsize=1024*1024*2;
		if(is_uploaded_file($_FILES[$fieldname]["tmp_name"]))
		{
			$upfile=$_FILES[$fieldname];//用一个数组类型的字符串存放上传文件的信息
			
			$name=$upfile["name"];//便于以后转移文件时命名
			$type=$upfile["type"];//上传文件的类型
			$size=$upfile["size"];//上传文件的大小
			$tmp_name=$upfile["tmp_name"];//用户上传文件的临时名称
			$error=$upfile["error"];//上传过程中的错误信息
			$ext = '';
			
			$before = null;//bjconfig('file')['before'];
			if( bjconfig('file') ) $before = bjconfig('file')['before'];
			if( $before ) bjstaticcall($before,$name,$type,$size);
			
			
			//对文件类型进行判断，判断是否要转移文件,如果符合要求则设置$ok=1即可以转移
			$ok = 0;
			switch($type){
				case "image/jpg": $ok=1;	$ext = '.jpg'; break;
				case "image/jpeg": $ok=1;	$ext = '.jpg'; break;
				case "image/gif" : $ok=1;	$ext = '.gif'; break;
				case "image/png" : $ok=1;	$ext = '.png'; break;
				case "image/bmp" : $ok=1;	$ext = '.bmp';break;
				case "application/zip": $ok = 1; $ext = '.zip';break;
				case "application/x-zip-compressed": $ok = 1; $ext = '.zip';break;
				case "application/vnd.ms-excel":$ok=1;$ext='.xls';break;
				case "application/vnd.ms-powerpoint":$ok=1;$ext='.ppt';break;
				case "application/msword":$ok=1;$ext='.doc';break;
				case "application/pdf":$ok=1;$ext='.pdf';break;
				case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet": $ok=1;$ext=".xls"; break;
				//case "application/octet-stream": $ok = 1; $ext = '.rar'; break;//
				default:$ok=0;	break;
			}
			//如果文件符合要求并且上传过程中没有错误
			if($ok && (int)$size <= $maxsize && $error == '0')
			{
				//调用move_uploaded_file（）函数，进行文件转移
				$now = time();
				$rond = rand(1000,1000000);
				$rond = md5(''.$rond.time());
				
				$newpath = $this->getAttPath();
				//if( substr($newpath,0,1) != '/' ) $newpath = root_path().'/'.$newpath;
				
				$newpath .= "/".date('Y',$now)."/".date('m',$now)."/".date('d',$now)."/".date('h',$now);
				if( !file_exists($newpath) )
				{
					$res=mkdir(iconv("UTF-8", "GBK", $newpath),0777,true);
					if ($res)
					{
						//echo "目录 $path 创建成功";
						chmod(iconv("UTF-8", "GBK", $newpath),0777);
					}
				}
				
				
				$newname = $newpath."/".$rond.$ext;
				
				move_uploaded_file($tmp_name,$newname);
				
				$webname = '/att/'
					.date('Y',$now).'/'.date('m',$now).'/'.date('d',$now)
					.'/'.date('h',$now).'/'.$rond.$ext;
				
				$after = null;
				if( bjconfig('file') ) $after = bjconfig('file')['after'];
				if( $after ) bjstaticcall($after,$name,$type,$size,$webname);
				
				return ['_path'=>$webname,'_newname'=>$newname,'_name'=>$name,'_ext'=>$ext,'_size'=>$size];
			}
		}
		bjerror("非法上传类型".$_FILES[$fieldname]["type"]);
		return [];
	}
	
	private function getAttPath()
	{
		$cfg = require (INDEX_PATH . '/config/fileconfig.php');
		return $cfg['attpath'];
	}
}
