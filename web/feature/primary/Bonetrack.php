<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/21
 * Time: 10:40
 */
namespace feature\primary;
class Bonetrack{

    public $meta;
    public function __construct()
    {
        $this->meta = bjmeta('wealth.Bonetrack');
    }

    public function judgeTodayFirst($user){
        $condition = bjcond("t.{accid=}","accid",$user->getAccid());
        //DATE() 函数返回日期或日期/时间表达式的日期部分
        $condition = $condition->_and(bjcond("date(from_unixtime(t.bonedate))=date(now())",'',''));
        $data = bjcreate(
            "bjphp.vendor.db.Dao",
            "select *  from {m1} t".$condition->where(),
            $condition->fill([
                "m1" => $this->meta->TableName()
            ])
        )->all();
        return $data;
    }

    public function addbatch($arrs){
        foreach($arrs as $v){
            $this->meta->add($v);
        }
    }

    public function judgePop($user,$landindex){
        $condition = bjcond("t.{accid=}","accid",$user->getAccid());
        $condition = $condition->_and(bjcond("t.{landindex=}","landindex",$landindex));
        $condition = $condition->_and(bjcond("date(from_unixtime(t.bonedate))=date(now())",'',''));
        $data = bjcreate(
            "bjphp.vendor.db.Dao",
            "select *  from {m1} t".$condition->where(),
            $condition->fill([
                "m1" => $this->meta->TableName()
            ])
        )->first();
        return $data;
    }

    public function isPopget($land){
        $condition = bjcond('t.{accid=}','accid',$land->getUser()->getAccid());
        $condition = $condition->_and(bjcond("t.{landindex=}","landindex",$land->getIndex()));
        $condition = $condition->_and(bjcond("date(from_unixtime(t.bonedate))=date(now())",'',''));
        $bonetrack = bjcreate(
            'bjphp.vendor.db.Dao',
            'select *  from {m1} t '.$condition->where(),
            $condition->fill([
                'm1' => $this->meta->TableName()
            ])
        )->first();
        return $bonetrack;
    }

    public function hitPop($land,$bonetrack){
        $this->meta->update(['get'=>1,'gettime'=>time()],['id'=>(int)$bonetrack->id]);

        return (double)$bonetrack->num;
    }

    public function loadgrowthrecord($user,$input){
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
                "select t.num,t.basic,t.breeder,t.level,t.total,t.bonedate from {m1} t ". $condition->where() . "order by t.bonedate desc {page}" ,
                $condition->fill([ "m1" => $m->TableName(),"m2" => bjmeta('account.Account')->TableName(),"page" =>[$pageindex,$pagesize]])
            )->all();
        }
        return ["totalcount" => $count,"totalpage" =>(int) $pages,"datas" => $datas];

    }

}