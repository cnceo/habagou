<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/21
 * Time: 15:05
 */
namespace feature\primary;
class Cleanrecord{
    public $meta;
    public function __construct()
    {
        $this->meta = bjmeta('wealth.Cleanrecord');
    }

    /*
     * 查询用户当天是否被打扫
     */
    public function getTouserCleanrecord($toUser){
        $condition = bjcond("t.{targetid=}","targetid",$toUser->getAccid());
        $condition = $condition->_and(bjcond("date(from_unixtime(t.cleantime))=date(now())",'',''));
        $data = bjcreate(
            "bjphp.vendor.db.Dao",
            "select * from {m1} t".$condition->where(),
            $condition->fill([
                "m1" => $this->meta->TableName()
            ])
        )->first();
        return $data;
    }

    public function add($cleanrecord){
        $this->meta->add($cleanrecord);
    }


    public function loadcleanrecord($user,$input){
        $m = $this->meta;
        $condition = bjcond("t.{accid=}","accid",$user->getAccid());

        //记录总数
        $count = (int)bjcreate(
            "bjphp.vendor.db.Dao",
            "select count(*) as c from {m1} t " . $condition->where(),
            $condition->fill([ "m1" => $m->TableName() ])
        )->first()->c;
        //===查询数据
        $pageindex = 0;//当前页
        $pagesize = 0;//页容量
        $pages = 0;//总数页
        $datas=[];

        if( property_exists($input,"pageindex") && 0 < (int)$input->pagesize )
        {
            $pageindex = (int)$input->pageindex;
            $pagesize = (int)$input->pagesize;
        }
        if( $pagesize > 0 )
        {
            $pages = ($count + $pagesize-1) / $pagesize;


            $datas = bjcreate(
                "bjphp.vendor.db.Dao",
                "select t1.account,t.num,t.cleantime from {m1} t LEFT JOIN {m2} t1 ON t.targetid = t1.id". $condition->where() . "order by t.id desc {page}" ,
                $condition->fill([ "m1" => $m->TableName(),"m2" => bjmeta('account.Account')->TableName(),"page" =>[$pageindex,$pagesize]])
            )->all();
        }
        return ["totalcount" => $count,"totalpage" =>(int) $pages,"datas" => $datas];

    }
}