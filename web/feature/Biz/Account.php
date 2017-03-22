<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 19:30
 */
namespace feature\Biz;
class Account{
    private $id;
    private $account;
    private $phone;
    private $user;
    public function __construct($user)
    {
        $this->user=$user;
    }

    /**
     * @return mixed
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param mixed $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    public function add(){
        return bjfeature('primary.Account')->add($this->user);
    }

    public static function loadById($id){
        return bjfeature('primary.Account')->loadById($id);
    }

    public static function loadByPhone($phone){
        return bjfeature('primary.Account')->loadByPhone($phone);
    }

    public static function loadByAccount($account){
        return bjfeature('primary.Account')->loadByAccount($account);
    }

    public function getAccountInfo(){
        return bjfeature('primary.Account')->getAccountInfo($this->user);
    }

    public static function create($user){
        return bjstaticcall("feature.primary.Account.create",$user);
    }

    //用于超级打扫
    public function getCleanPartner(){
        return bjfeature('primary.Account')->getCleanPartner($this->user);
    }

    public function updateLevel($level){
        return bjfeature('primary.Account')->updateLevel($this->user,$level);
    }

    public function judgeFriend($toUser){
        return bjfeature('primary.Account')->judgeFriend($toUser);
    }

    public function myFriend(){
        return bjfeature('primary.Account')->myFriend($this->user);
    }

    public function updatePwd($pwd){
        bjfeature('primary.Account')->updatePwd($this->user,$pwd);
    }

    public function updateHeadimg($headid){
        bjfeature('primary.Account')->updateHeadimg($this->user,$headid);
    }

}