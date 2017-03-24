<?php
class user{

     public function test_login(){
          $user=['phone'=>15270765826,'pwd'=>123456];
          bjhttp()->request()->addParam($user);
          echo $this->login_submit();
     }

     //登录
     public function login(){
          bjview("user.login")->display([
          ]);
     }

     public function login_submit(){
          $user = bjmeta('account.Account')->getFormMeta();
          return bjapi('user.User.login',$user);
     }

     /*
      * 开发新狗场
      */
     public function register(){
          //echo bjapi('user.User.register',['fromAccount'=>'123','account'=>'test2','name'=>'张三','sex'=>0,'phone'=>'13875596745']);
          //$account=bjsession()->get('account');
          $result=bjapi('user.User.loadSelfAccount',[]);
          //var_dump($result);exit;

          bjview("user.register")->display([
               'account'=>$result['account'],
               'edit'   =>$result['edit'],
               'headimg' =>$result['headimg'],
          ]);
     }

     //出售小狗
     public function sale(){
          //echo bjapi('user.User.sale',['toAccount'=>'test','toName'=>'张三','num'=>100]);exit;
          $result=bjapi('user.User.loadSelfWealth',[]);
          bjview('user.sale')->display([
               'warehouse'=>$result['warehouse'],
               'headimg'  =>$result['headimg'],
          ]);
     }

     public function superSale(){
          $res=bjapi('user.User.superSale',['fromAccount'=>'123','toAccount'=>'test','toName'=>'张三','num'=>100]);
          var_dump($res);
     }

     public function clean(){
          echo bjapi('user.User.clean',['toAccid'=>53]);
     }


     public function shopCenterD(){
     	bjview("user.shopCenterD")->display([

          ]);
     }
     public function grow(){
     	bjview("user.grow")->display([

          ]);
     }

     
     public function feed(){
     	bjview("user.feed")->display([

          ]);
     }
     
     public function breeding(){
     	bjview("user.breeding")->display([

          ]);
     }
     //登录进来首页
     public function home(){
          $result=bjapi('user.User.home',[]);
          foreach($result['sysmessage'] as $v){
               $v->sendtime=date('m-d',$v->sendtime);
          }
          foreach($result['notice'] as $v){
               $v->sendtime=date('m-d',$v->sendtime);
          }

          bjview("user.index")->display([
              'notice'=>$result['notice'],
              'account'=>$result['userinfo']['accountinfo']['account'],
              'image'=>$result['userinfo']['accountinfo']['image'],
              'bone'=>$result['userinfo']['wealthinfo']['bone'],
              'total'=>$result['userinfo']['wealthinfo']['total'],
              'warehouse'=>$result['userinfo']['wealthinfo']['warehouse'],
              'bone'=>sprintf("%.2f", $result['userinfo']['wealthinfo']['bone']),
              'level'=>$result['userinfo']['accountinfo']['level'],
              'message'=>$result['sysmessage'],
              'status'=>$result['status'],
              'images' =>$result['headimg']
          ]);
     }





     public function buyList($type=1,$pageindex=1){
               $result=bjapi("transaction.Transaction.record",["type"=>$type,"pagesize"=>10,"pageindex"=>$pageindex]);
               $totalpage = $result['totalpage'];
               $datas = $result['datas'];
               foreach($datas as $d)
               {
                    $d->launchtime=date("m-d",$d->launchtime);
               }
               bjview("user.buylist")->display([
                   "totalcount"=>$result['totalcount'],
                   'totalpage'=>$result['totalpage'],
                   "datas" => $result['datas'],
                   'pageindex'=>$pageindex,
                   "next_page" => bjhttp()->request()->makeUrl('buyList',$type,$pageindex < $totalpage ? $pageindex+1 : $pageindex),
                   "prev_page" => bjhttp()->request()->makeUrl('buyList',$type,$pageindex <= 1 ? $pageindex : $pageindex-1)
               ]);
     }

     public function saleList($type=2,$pageindex=1){
          $result=bjapi("transaction.Transaction.record",["type"=>$type,"pagesize"=>10,"pageindex"=>$pageindex]);
          $totalpage = $result['totalpage'];
          $datas = $result['datas'];
          foreach($datas as $d)
          {
               $d->launchtime=date("m-d",$d->launchtime);
          }
          bjview("user.salelist")->display([
              "totalcount"=>$result['totalcount'],
              'totalpage'=>$result['totalpage'],
              "datas" => $result['datas'],
              'pageindex'=>$pageindex,
              "next_page" => bjhttp()->request()->makeUrl('saleList',$type,$pageindex < $totalpage ? $pageindex+1 : $pageindex),
              "prev_page" => bjhttp()->request()->makeUrl('saleList',$type,$pageindex <= 1 ? $pageindex : $pageindex-1)
          ]);
     }


     public function visitFriend($accid){
          $result=bjapi('user.User.visitFriend',['toUserId'=>$accid]);
          $arr=[];
          /*$result['landinfo']=[
              [
                  'landindex'=>0,
                  'dog'=>1,
                  'pop'=>1,
                  'type'=>0
              ],
          ];*/
          for($i=0;$i<15;$i++)
          {
               $tmp=[
                   'landindex'=>$i,
                   'dog'=>0,
                   'type'=>$i>9 ? 1:0
               ];
               foreach($result['landinfo'] as $k=>$v){
                    if((int)$v['landindex']==$i){
                         $tmp = $v;
                         break;
                    }
               }
               $arr[] = $tmp;

          }

          foreach($result['notice'] as $v){
               $v->sendtime=date('m-d',$v->sendtime);
          }

          bjview()->register("dogarray",function($num){
               $arr1 = [];
               for($i=0;$i<$num;$i++) $arr[] = $i;
               return $arr1;
          });

          bjview()->register("to_json",function($num){
               return json_encode($num);
          });
          //var_dump($arr);exit;

          /*bjview("land.home")->display([
              'account'=>'123',
              'image'=>'',
              'bone'=>200,
              'total'=>1920,
              'level'=>1,
              'landinfo'=>$arr,

          ]);*/

          bjview("user.visitfriend")->display([
              'account'=>$result['userinfo']['accountinfo']['account'],
              'headimg'=>$result['userinfo']['accountinfo']['image'],
              'bone'=>sprintf("%.2f", $result['userinfo']['wealthinfo']['bone']),
              'total'=>sprintf("%.2f", $result['userinfo']['wealthinfo']['total']),
              'level'=>$result['userinfo']['accountinfo']['level'],
              'landinfo'=>$arr,
              'notice'=>$result['notice'],
              'clean'=>$result['clean'],
              'toAccid'=>$accid
          ]);

     }
     /*
      * 注销登录
      */
     public function loginOut(){
          bjsession()->set('accid','');
          bjhttp()->response()->redirect("/user/user/login");
     }

     /*
      * 更新交易状态  0  卖家   1  买家
      */
     public function updataStatus($id,$type,$status){
          bjapi('user.User.updataStatus',['id'=>$id,'type'=>$type,'status'=>$status]);
          if($type==0){
               bjhttp()->response()->redirect("/user/user/saleList");
          }elseif($type==1){
               bjhttp()->response()->redirect("/user/user/buyList");
          }
     }

}