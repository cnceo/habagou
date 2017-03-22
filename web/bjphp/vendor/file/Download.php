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

class Download
{
	protected $config;
	
	public function __construct()
	{
		$this->config = require INDEX_PATH . "/config/fileconfig.php";
	}
	
	public function run($path)
	{
		$file_path = $this->config['attpath'] . $path;
		
		if( ! file_exists($file_path) ) bjhttp()->e404('/att'.$path);
		else
		{
			if( isset($this->config['atturl']) )
			{
				$root_url = $this->config['atturl'];
				$path = $root_url . $path;
				
				$vdname = bjconfig('site')['vdname'];
				if( $vdname != '' ) $path = '/' . $vdname . $path;
				
				bjhttp()->e301( $path );
			}
			else
			{
				$ext = strtolower(pathinfo($file_path,PATHINFO_EXTENSION));
				$content_type = $this->getMime($ext,$file_path);
				header("Cache-control: max-age=86400");
				header("Content-type:{$content_type}"); //设置要下载的文件类型
				header("Content-Length:" . filesize($file_path)); //设置要下载文件的文件大小
				//header("Content-Disposition: attachment; filename=" . urldecode($newName)); //设置要下载文件的文件名
				readfile($file_path);
			}
		}
	}
	
	private function getMime($ext,$filename)
	{
		$mime = [
			//applications
			'ai'  => 'application/postscript',
			'eps'  => 'application/postscript',
			'exe'  => 'application/octet-stream',
			'doc'  => 'application/vnd.ms-word',
			'xls'  => 'application/vnd.ms-excel',
			'ppt'  => 'application/vnd.ms-powerpoint',
			'pps'  => 'application/vnd.ms-powerpoint',
			'pdf'  => 'application/pdf',
			'xml'  => 'application/xml',
			'odt'  => 'application/vnd.oasis.opendocument.text',
			'swf'  => 'application/x-shockwave-flash',
			// archives
			'gz'  => 'application/x-gzip',
			'tgz'  => 'application/x-gzip',
			'bz'  => 'application/x-bzip2',
			'bz2'  => 'application/x-bzip2',
			'tbz'  => 'application/x-bzip2',
			'zip'  => 'application/zip',
			'rar'  => 'application/x-rar',
			'tar'  => 'application/x-tar',
			'7z'  => 'application/x-7z-compressed',
			// texts
			'txt'  => 'text/plain',
			'php'  => 'text/x-php',
			'html' => 'text/html',
			'htm'  => 'text/html',
			'js'  => 'text/javascript',
			'css'  => 'text/css',
			'rtf'  => 'text/rtf',
			'rtfd' => 'text/rtfd',
			'py'  => 'text/x-python',
			'java' => 'text/x-java-source',
			'rb'  => 'text/x-ruby',
			'sh'  => 'text/x-shellscript',
			'pl'  => 'text/x-perl',
			'sql'  => 'text/x-sql',
			// images
			'bmp'  => 'image/x-ms-bmp',
			'jpg'  => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'gif'  => 'image/gif',
			'png'  => 'image/png',
			'tif'  => 'image/tiff',
			'tiff' => 'image/tiff',
			'tga'  => 'image/x-targa',
			'psd'  => 'image/vnd.adobe.photoshop',
			//audio
			'mp3'  => 'audio/mpeg',
			'mid'  => 'audio/midi',
			'ogg'  => 'audio/ogg',
			'mp4a' => 'audio/mp4',
			'wav'  => 'audio/wav',
			'wma'  => 'audio/x-ms-wma',
			// video
			'avi'  => 'video/x-msvideo',
			'dv'  => 'video/x-dv',
			'mp4'  => 'video/mp4',
			'mpeg' => 'video/mpeg',
			'mpg'  => 'video/mpeg',
			'mov'  => 'video/quicktime',
			'wm'  => 'video/x-ms-wmv',
			'flv'  => 'video/x-flv',
			'mkv'  => 'video/x-matroska'
		];
		if( isset($mime,$ext) ) return $mime[$ext];
		
		$info = finfo_open(FILEINFO_MIME);
		$mime_type = finfo_file($info, $filename);
		finfo_close($info);
		return $mime_type;
	}
}

