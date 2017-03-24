<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/16
 * Time: 13:12
 */
class Transcationtrack{

    public function record($type,$pageindex=1){
        echo 0;
        $result=bjapi("log.Transcationtrack.record",["type"=>$type,"pagesize"=>1,"pageindex"=>$pageindex]);
        //var_dump($result);exit;
        $totalpage = $result['totalpage'];
        $datas = $result['datas'];
        foreach($datas as $d)
        {
            if( (int)$d->status == 1 ) $d->status='kkkkk';
        }
        bjview("user.trading")->display([
            "totalcount"=>$result['totalcount'],
            'totalpage'=>$result['totalpage'],
            "datas" => $result['datas'],
            'pageindex'=>$pageindex,
            "next_page" => bjhttp()->request()->makeUrl('record',$type,$pageindex < $totalpage ? $pageindex+1 : $pageindex),
            "prev_page" => bjhttp()->request()->makeUrl('record',$type,$pageindex <= 1 ? $pageindex : $pageindex-1)
        ]);
    }

}