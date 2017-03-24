<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/1
 * Time: 15:00
 */
namespace feature\primary;
class Raiserecord{
    public $meta;
    public function __construct()
    {
        $this->meta = bjmeta('wealth.Raiserecord');
    }
    public function loadraiserecord($user,$input){
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
                    "select * from {m1} t ". $condition->where() .' order by hatchtime desc '. "{page}" ,
                    $condition->fill([ "m1" => $m->TableName(),"page" =>[$pageindex,$pagesize]])
                )->all();
            }
            return ["totalcount" => $count,"totalpage" =>(int) $pages,"datas" => $datas];

    }

    public function add($input){
         $this->meta->add($input);
    }
}