<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/1
 * Time: 14:53
 */
class record{

    //生长记录
    public function growthrecord($pageindex=1){
        $result=bjapi("user.User.growthrecord",["pagesize"=>10,"pageindex"=>$pageindex]);
        $totalpage = $result['totalpage'];
        $datas = $result['datas'];
        //var_dump($datas);
        foreach($datas as $d)
        {
            $d->bonedate=date('m-d', $d->bonedate);
        }
        bjview("user.grow")->display([
            "totalcount"=>$result['totalcount'],
            'totalpage'=>$result['totalpage'],
            "datas" => $result['datas'],
            'pageindex'=>$pageindex,
            "next_page" => bjhttp()->request()->makeUrl('growthrecord',$pageindex < $totalpage ? $pageindex+1 : $pageindex),
            "prev_page" => bjhttp()->request()->makeUrl('growthrecord',$pageindex <= 1 ? $pageindex : $pageindex-1)
        ]);
    }

    //打扫记录
    public function cleanrecord($pageindex=1){
        $result=bjapi("user.User.cleanrecord",["pagesize"=>10,"pageindex"=>$pageindex]);
        $totalpage = $result['totalpage'];
        $datas = $result['datas'];
        if($datas!=null){
            foreach($datas as $d)
            {
                $d->cleantime=date('m-d', $d->cleantime);
            }
        }

        bjview("user.clean")->display([
            "totalcount"=>$result['totalcount'],
            'totalpage'=>$result['totalpage'],
            "datas" => $result['datas'],
            'pageindex'=>$pageindex,
            "next_page" => bjhttp()->request()->makeUrl('cleanrecord',$pageindex < $totalpage ? $pageindex+1 : $pageindex),
            "prev_page" => bjhttp()->request()->makeUrl('cleanrecord',$pageindex <= 1 ? $pageindex : $pageindex-1)
        ]);
    }

    //喂食记录
    public function feedrecord($pageindex=1){
        $result=bjapi("user.User.feedrecord",["pagesize"=>10,"pageindex"=>$pageindex]);
        $totalpage = $result['totalpage'];
        $datas = $result['datas'];
        if($datas!=null){
            foreach($datas as $d)
            {
                if($d->type==0){
                    $d->type='绿地';
                }elseif($d->type==1){
                    $d->type='金地';
                }
                $d->hatchtime=date('m-d', $d->hatchtime);
            }
        }
        bjview("user.feed")->display([
            "totalcount"=>$result['totalcount'],
            'totalpage'=>$result['totalpage'],
            "datas" => $result['datas'],
            'pageindex'=>$pageindex,
            "next_page" => bjhttp()->request()->makeUrl('feedrecord',$pageindex < $totalpage ? $pageindex+1 : $pageindex),
            "prev_page" => bjhttp()->request()->makeUrl('feedrecord',$pageindex <= 1 ? $pageindex : $pageindex-1)
        ]);
    }


    //增养记录
    public function raiserecordcord($pageindex=1){
        $result=bjapi("user.User.raiserecordcord",["pagesize"=>10,"pageindex"=>$pageindex]);
        $totalpage = $result['totalpage'];
        $datas = $result['datas'];
        if($datas!=null){
            foreach($datas as $d)
            {
                if($d->type==0){
                    $d->type='普通房';
                }elseif($d->type==1){
                    $d->type='金房';
                }
                $d->hatchtime=date('m-d', $d->hatchtime);
            }
        }

        bjview("user.raisere")->display([
            "totalcount"=>$result['totalcount'],
            'totalpage'=>$result['totalpage'],
            "datas" => $result['datas'],
            'pageindex'=>$pageindex,
            "next_page" => bjhttp()->request()->makeUrl('raiserecordcord',$pageindex < $totalpage ? $pageindex+1 : $pageindex),
            "prev_page" => bjhttp()->request()->makeUrl('raiserecordcord',$pageindex <= 1 ? $pageindex : $pageindex-1)
        ]);
    }
}