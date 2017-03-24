<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 17:14
 */
namespace feature\primary;

class Account{
	public $meta;
    public function __construct()
    {
        $this->meta = bjmeta('account.Account');
    }
	//添加功能
    public function add($user){
        //强制转换为对象
        $user = bjobject($user);
        //做添加功能
        $this->meta->add($user);
    }


    //查询账号
    public function loadByAccount($account){
        $condition =  bjcond("t.{account=}","account",$account);
        $data = bjcreate(
            "bjphp.vendor.db.Dao",
            "select * from {m1} t".$condition->where(),
            $condition->fill([
                "m1" => $this->meta->TableName()
            ])
        )->first();
        return $data;
    }
    //查询手机号
    public function loadByPhone($phone){
        $condition =  bjcond("t.{phone=}","phone",$phone);
        $data = bjcreate(
            "bjphp.vendor.db.Dao",
            "select * from {m1} t".$condition->where(),
            $condition->fill([
                "m1" => $this->meta->TableName()
            ])
        )->first();
        return $data;
    }

    //查询手机号
    public function loadById($id){
        $condition =  bjcond("t.{id=}","id",$id);
        $data = bjcreate(
            "bjphp.vendor.db.Dao",
            "select * from {m1} t".$condition->where(),
            $condition->fill([
                "m1" => $this->meta->TableName()
            ])
        )->first();
        return $data;
    }

    //获取账户信息--->account headid
    public function getAccountInfo($user){
        $condition =  bjcond("t.{id=}","id",$user->getAccid());
        $data = bjcreate(
            "bjphp.vendor.db.Dao",
            "select t.*,h.image from {m1} t JOIN {m2} h ON t.headid=h.id ".$condition->where(),
            $condition->fill([
                "m1" => $this->meta->TableName(),"m2"=>bjmeta('sys.Headimg')->TableName()
            ])
        )->first();
        return $data;
    }

    //获得可以打扫的好友列表
    public function getCleanPartner($user){
        $condition =  bjcond("t.{recommendid=}","recommendid",$user->getAccid());
        $data = bjcreate(
            "bjphp.vendor.db.Dao",
            "select * from {m1} t".$condition->where(),
            $condition->fill([
                "m1" => $this->meta->TableName()
            ])
        )->all();
        return $data;
    }


    public static function create($user){
          bjmeta('account.Account')->add($user);
          return bjstaticcall('bjphp.vendor.db.Connection.master')->lastID();
    }

    public function updateLevel($user,$level){
        bjmeta('account.Account')->update(['level'=>$level],['id'=>$user->getAccid()]);
    }

    public function judgeFriend($toUser){
        return $this->meta->load(['id'=>$toUser->getAccid()],['account','recommendid']);
    }


    public function myFriend($fromUser){
        $condition =  bjcond("t.{recommendid=}","recommendid",$fromUser->getAccid());
        $datas = bjcreate(
                    "bjphp.vendor.db.Dao",
                    "select t.id,t.account,t.name,t.phone,t2.bone,t3.image from {m1} t left join {m2} t2 ON t.id=t2.accid LEFT JOIN {m3} t3 ON t.headid=t3.id" .
                    $condition->where() ,
                    $condition->fill([ "m1" => $this->meta->TableName(),
                        "m2" => bjmeta('wealth.Wealth')->TableName(),
                        "m3" => bjmeta('sys.Headimg')->TableName()])
                )->all();
         return $datas;
    }

    public function updatePwd($fromUser,$pwd){
          $this->meta->update(['pwd'=>md5($pwd)],['id'=>$fromUser->getAccid()]);
    }

    public function updateHeadimg($fromUser,$headid){
        $this->meta->update(['headid'=>$headid],['id'=>$fromUser->getAccid()]);
    }

    public function loadHeadimg($id){
        $condition =  bjcond("t.{id=}","id",$id);
        $data = bjcreate(
            "bjphp.vendor.db.Dao",
            "select t3.image from {m1} t  LEFT JOIN {m3} t3 ON t.headid=t3.id" .
            $condition->where() ,
            $condition->fill([ "m1" => $this->meta->TableName(),
                "m3" => bjmeta('sys.Headimg')->TableName()])
        )->first();
        return $data;
    }

}
