<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/20
 * Time: 20:09
 */
namespace feature\primary;
class Hatchrecord{
    public $meta;
    public function __construct()
    {
        $this->meta = bjmeta('wealth.Hatchrecord');
    }

    //获取该用户当天的孵化小狗数目
    /*
     * 更新日志
     * 2017 3 8  只算每天骨头记录
     */
    public function getTodayHatchWealth($user){
        $condition = bjcond("t.{accid=}","accid",$user->accid);
        $condition = $condition->_and(bjcond("date(from_unixtime(t.bonedate))=date(now())",'',''));
        $condition = $condition->_and(bjcond("t.get=1",'',''));
        $data = bjcreate(
            "bjphp.vendor.db.Dao",
            "select sum([num]) as c from {m1} t".$condition->where(),
            $condition->fill([
                "m1" => bjmeta('wealth.Bonetrack')->TableName()
            ])
        )->first();

        return $data->c;
    }

    /*
     * 查询用户当天是否有孵化记录
     */
    public function getToDayHatchrecord($user){
        $condition = bjcond("t.{accid=}","accid",$user->getAccid());
        $condition = $condition->_and(bjcond("date(from_unixtime(t.hatchtime))=date(now())",'',''));
        $data = bjcreate(
            "bjphp.vendor.db.Dao",
            "select *  from {m1} t".$condition->where(),
            $condition->fill([
                "m1" => $this->meta->TableName()
            ])
        )->all();
        return $data;
    }


    public function loadhatchrecord($user,$input){
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
                "select * from {m1} t ". $condition->where() .' order by t.hatchtime desc '. "{page}" ,
                $condition->fill([ "m1" => $m->TableName(),"page" =>[$pageindex,$pagesize]])
            )->all();
        }
        return ["totalcount" => $count,"totalpage" =>(int) $pages,"datas" => $datas];

    }

    public function add($input){
        $this->meta->add($input);
    }
}