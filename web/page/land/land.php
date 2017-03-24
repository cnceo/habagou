<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/17
 * Time: 17:50
 */
class land{
    public function test_create(){
        if(bjconfig('site')['mode']=='debug'){
            $this->create(2);
        }
        /*foreach($user as $k=>$v){
             bjhttp()->request()->addParam($k,$v);
             $this->login();
        }*/
    }

    public function test_addOxygen(){
        if(bjconfig('site')['mode']=='debug'){
            //增氧的索引  增氧的地块
            $this->addOxygen();
        }
    }

    public function create($index){
        bjapi('land.Land.create',['index'=>$index]);
    }

    public function addOxygen(){
        bjapi('land.Land.addOxygen',['index'=>1,"num"=>200]);
        echo "addOxygen ok";
    }

    public function feed(){
        bjapi('land.Land.feed',['index'=>1,"num"=>100]);
        echo "feed ok";
    }

    public function harvest(){
        bjapi('land.Land.harvest',['index'=>1]);
        echo "harvest ok";
    }

    //广场
    public function home(){
        $result=bjapi('land.Land.home',[]);
        $arr=[];
        /*$result['landinfo']=[
            [
                'landindex'=>0,
                'dog'=>1,
                'pop'=>1,
                'type'=>0
            ],
            [
                'landindex'=>1,
                'dog'=>2,
                'pop'=>0,
                'type'=>1
            ],
            [
                'landindex'=>2,
                'dog'=>3,
                'pop'=>1,
                'type'=>1
            ],
            [
                'landindex'=>3,
                'dog'=>2,
                'pop'=>0,
                'type'=>1
            ]
        ];*/
        $yy=[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14];
        $zyyy=[];
        for($i=0;$i<15;$i++)
        {
            $tmp=[
                'landindex'=>$i,
                'dog'=>0,
                'pop'=>0,
                'type'=>$i>9 ? 1:0
            ];
            if($result['landinfo']!=null){
                foreach($result['landinfo'] as $k=>$v){
                    if((int)$v['landindex']==$i){
                        $zyyy[]=$i;
                        $tmp = $v;
                        unset($yy[$i]);
                        break;
                    }

                }
            }
            $arr[] = $tmp;

        }
        bjview()->register("dogarray",function($num){
            $arr = [];
            for($i=0;$i<$num;$i++) $arr[] = $i;
            return $arr;
        });

        bjview()->register("to_json",function($num){
           return json_encode($num);
        });
        foreach($result['friendinfo'] as $v){
            $v->account=substr_replace($v->phone,'****',7,4);
            $v->phone=substr_replace($v->phone,'****',7,4);
        }
        foreach($result['notice'] as $v){
            $v->sendtime=date('m-d',$v->sendtime);
        }
    	bjview("land.home")->display([
            'account'=>$result['userinfo']['accountinfo']['account'],
            'image'=>$result['userinfo']['accountinfo']['image'],
            'bone'=>sprintf("%.2f", $result['userinfo']['wealthinfo']['bone']),
            'total'=>sprintf("%.2f",$result['userinfo']['wealthinfo']['total']),
            'warehouse'=>$result['userinfo']['wealthinfo']['warehouse'],
            'level'=>$result['userinfo']['accountinfo']['level'],
            'landinfo'=>$arr,
            'notice' => $result['notice'],
            'topnotice' => $result['notice'][0]->title,
            'friend' => $result['friendinfo'],
            'feederlevel' => $result['userinfo']['feedlevel'],
            'status'    => $result['status'],
            'yy'=>implode(",",$yy),
            'zyyy'=>implode(",",$zyyy)
        ]);

    }

    //点击pop
    public function popup(){
        bjapi('land.Land.popup',['index'=>1]);
    }

}