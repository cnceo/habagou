<?php
/* *
 * 功能：创蓝发送信息DEMO
 * 版本：1.3
 * 日期：2014-07-16
 * 说明：
 * 以下代码只是为了方便客户测试而提供的样例代码，客户可以根据自己网站的需要，按照技术文档自行编写,并非一定要使用该代码。
 * 该代码仅供学习和研究创蓝接口使用，只是提供一个参考。
 */
require_once 'ChuanglanSmsHelper/ChuanglanSmsApi.php';
$clapi  = new ChuanglanSmsApi();
$result = $clapi->sendSMS('18617143143', '【哈巴狗科技】恭喜您注册成功，您的账号为您的注册电话，初始密码为123456    请尽快登入网站修改密码，感谢您的参与 ');
$result = $clapi->execResult($result);
//创蓝接口参数
$statusStr = array(
	'0' =>'发送成功',
    '101'=>'无此用户',
    '102'=>'密码错',
    '103'=>'提交过快',
    '104'=>'系统忙',
    '105'=>'敏感短信',
    '106'=>'消息长度错',
    '107'=>'错误的手机号码',
    '108'=>'手机号码个数错',
    '109'=>'无发送额度',
    '110'=>'不在发送时间内',
    '111'=>'超出该账户当月发送额度限制',
    '112'=>'无此产品',
    '113'=>'extno格式错',
    '115'=>'自动审核驳回',
    '116'=>'签名不合法，未带签名',
    '117'=>'IP地址认证错',
    '118'=>'用户没有相应的发送权限',
    '119'=>'用户已过期',
    '120'=>'内容不是白名单',
    '121'=>'必填参数。是否需要状态报告，取值true或false',
    '122'=>'5分钟内相同账号提交相同消息内容过多',
    '123'=>'发送类型错误(账号发送短信接口权限)',
    '124'=>'白模板匹配错误',
    '125'=>'驳回模板匹配错误'
);
if(isset($result[1])){
	echo $statusStr[$result[1]];
}else{
	echo "未连接上服务器";
}