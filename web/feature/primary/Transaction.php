<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 17:15
 */
namespace feature\primary;
class Transaction{
    public $meta;
    public function __construct()
    {
        $this->meta = bjmeta('wealth.Transaction');
    }

    public function getStatus($index){
        $arr=['start'=>1,'toConfirm'=>2,'fromConfirm'=>3,'toCancel'=>4];
        return $arr[$index];
    }

    public function create($fromUser,$toUser,$num,$scale){
        $this->meta->save([
            'launchid'  => $fromUser->getAccid(),
            'receiveid' => $toUser->getAccid(),
            'num'        => $num,
            'fee'        =>$scale,
            'status'     => 1,
            'launchtime' => time(),
        ]);
        return bjstaticcall("bjphp.vendor.db.Connection.master")->lastID();
    }

    //购买人确认付款
    public function toConfirm($transaction){
        return $this->meta->update(["status"=>$this->getStatus('toConfirm')],['id'=>$transaction->getId()]);
    }


    //发起人确认收款
    public function fromConfirm($transaction){
        return $this->meta->update(["status"=>$this->getStatus('fromConfirm')],['id'=>$transaction->getId()]);
    }


    //发起人取消交易
    public function toCancel($transaction){
        //修改状态为4
        $this->meta->update(["status"=>$this->getStatus('toCancel')],['id'=>$transaction->getId()]);
        return bjfeature('primary.Transaction')->toCancel($this);
    }

    public function loadTodaySale($transaction){
        $condition = bjcond("t.{launchid=}","launchid",(int)$transaction->getFromUser()->getAccid());
        $condition = $condition->_and(bjcond("date(from_unixtime(t.launchtime))=date(now())",'',''));
        $data = bjcreate(
            "bjphp.vendor.db.Dao",
            "select *  from {m1} t".$condition->where(),
            $condition->fill([
                "m1" => $this->meta->TableName()
            ])
        )->all();
        return $data;
    }

    public function updateStatus($transaction,$status){
        $this->meta->update(['status'=>$status],['id'=>$transaction->getId()]);
    }


    /*
     * 记录--->购买记录   出售记录
     */
    public function record($input){
        $obj = bjobject($input);
        $m = $this->meta;
        $type=$obj->type;
        $condition = bjcond('');
        if( property_exists($obj,"launchid"))
        {
            $condition = $condition->_and( bjcond("t.{launchid=}","launchid",$obj->launchid));

        }
        if( property_exists($obj,"receiveid"))
        {
            $condition = $condition->_and(bjcond("t.{receiveid=}","receiveid",$obj->receiveid));
        }
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

            if( property_exists($obj,"launchid"))
            {
                $onsql = " on t.receiveid=t2.id ";

            }
            if( property_exists($obj,"receiveid"))
            {
                $onsql = " on t.launchid=t2.id ";
            }
            $datas = bjcreate(
                "bjphp.vendor.db.Dao",
                "select t.id,t2.account,t.num,t.status,t.launchtime from {m1} t left join {m2} t2 " .$onsql. $condition->where() . "order by t.id desc {page}" ,
                $condition->fill([ "m1" => $m->TableName(),"m2" => bjmeta('account.Account')->TableName(),"page" =>[$pageindex,$pagesize]])
            )->all();
        }
        return ["totalcount" => $count,"totalpage" =>(int) $pages,"datas" => $datas];
    }

    public function loadById($id){
        return $this->meta->load(['id'=>$id]);
    }

    public function updataStatus($id,$status){
        $this->meta->update(['status'=>$status],['id'=>$id]);

    }


}