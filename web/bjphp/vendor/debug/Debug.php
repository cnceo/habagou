<?php
// =======================================================================
// | �ٽ�PHP���(BJPHP)
// | ---------------------------------------------------------------------
// | ���Э��Apache2 ( http://www.apache.org/licenses/LICENSE-2.0 )
// | ����֧��QQȺ��276228406
// | ΢�Ź��ں�  ���ٽ�����
// | �ٷ���վ    ��http://www.baijienet.com
// =======================================================================

namespace bjphp\vendor\debug;

class Debug
{
	private $time_counter;

	public function timeStart()
	{
		$this->time_counter = explode(' ',microtime());
	}

	public function timeEnd()
	{
		 $t = explode(' ',microtime());
		 $time = $t[0]+$t[1]-($this->time_counter[0]+$this->time_counter[1]);
		 $time = round($time,3);
		 return $time;
	}
}
