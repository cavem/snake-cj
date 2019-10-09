<?php
namespace app\api\controller;

use app\api\model\OrderModel;
use app\api\model\GameModel;
use app\api\model\GamerewardModel;

use think\Controller;
use think\Cache;

class Lucky extends Controller
{
    /*首页列表*/
    public function gamelist(){

        $page = input("page")?input("page"):1;

        $typeid = input("typeID")?input("typeID"):0;

        $limit = 10;
        $offset = ($page - 1) * $limit;

        $where = [];
    
        if($typeid){
            $where['game_type'] = $typeid;
        }

        $game = new GameModel();

        $selectResult = $game->getGameByWhere($where, $offset, $limit);

        foreach($selectResult as $k=>$v){
            $selectResult[$k]['img'] = "http://".$_SERVER['SERVER_NAME'].$selectResult[$k]['img'];
        }

        $total = $game->getAllGame($where);

        return json(["error_code"=>0,"msg"=>"获取成功","data"=>["count"=>$total,"page_index"=>$page,"list"=>$selectResult]]);
    }

    /* 抽奖详情 */

    public function detail(){

        $gid = input('gameid');
        $uid = input('uid');
        $token = checkNull(input('token'));

        $rule = [
            "uid" => 'require|number',
            "token" => 'require|length:32',
            "gid" => 'require|number',
        ];

        $param = [
            "uid" => $uid,
            "token" => $token,
            "gid" => $gid,
        ];

        $valires = $this->validate($param,$rule);

        if($valires!=1){
            return json(["erro_code"=>800,"msg"=>$valires]);
        }

        $checkToken=checkToken($uid,$token);

		if($checkToken==700){
            return json(["error_code"=>$checkToken,"msg"=>"您的登陆状态失效，请重新登陆！"]);
        }

        $selectResult = db('game_reward')->where("gid",$gid)->select();

        //奖品总数
        $game_reward_num = count($selectResult);

        if($game_reward_num>=10){
            $temp = array_rand($selectResult,10);
            foreach($temp as $val){
                $res[] = $selectResult[$val];
            }
            $selectResult = $res;
        }else{
            $cl_num = 10-$game_reward_num;
            for($i=0;$i<$cl_num;$i++){
                $selectResult[] = ["id"=>-$i,"gid"=>0,"content"=>"谢谢惠顾","img"=>"/upload/20190813/417106a634cc03c45f3a2e1fbfcc0cc7.png","chance"=>20,"price"=>0.00];
            }
        }
        
        foreach($selectResult as $k=>$v){
            $selectResult[$k]['img'] = "http://".$_SERVER['SERVER_NAME'].$selectResult[$k]['img'];
        }

        shuffle($selectResult);

        Cache::set("detail_".$uid."_".$gid,json_encode($selectResult));

        return json(["error_code"=>0,"msg"=>"获取成功","data"=>["list"=>$selectResult]]);

    }

    /* 创建支付订单*/
    public function neworder(){

        $gid = input('gameid');
        $uid = input('uid');
        $token = checkNull(input('token'));
        $cagegory = input('cagegory');

        $rule = [
            "uid" => 'require|number',
            "token" => 'require|length:32',
            "gid" => 'require|number',
            "cagegory" => 'require|number'
        ];

        $param = [
            "uid" => $uid,
            "token" => $token,
            "gid" => $gid,
            "cagegory" => $cagegory
        ];

        $valires = $this->validate($param,$rule);

        if($valires!=1){
            return json(["erro_code"=>800,"msg"=>$valires]);
        }

        $checkToken=checkToken($uid,$token);

		if($checkToken==700){
            return json(["error_code"=>$checkToken,"msg"=>"您的登陆状态失效，请重新登陆！"]);
        }

        $price = '1';
        
        $param['order_no'] = date("YmdHis");
        $param['money'] = $cagegory==1?$price:$price*10;
        $param['createtime'] = time();

        $order = new OrderModel();

        $res = $order->insertOrder($param);

        if($res==1001||$res==1002){
            return json(["error_code"=>1001,"msg"=>"订单提交失败"]);
        }

        //支付
        $pay = new Pay();

        $data = $pay->pay_order($res['orderid']);

        return json(["error_code"=>0,"msg"=>"订单提交成功","data"=>["param"=>$data,"orderid"=>$res['orderid']]]);
    }

    /*验证支付是否成功 */

    public function checkorder(){
        $uid = input('uid');
        $token = checkNull(input('token'));
        $orderid = input('orderid');

        $rule = [
            "uid" => 'require|number',
            "orderid" => 'require|number',
        ];

        $param = [
            "uid" => $uid,
            "orderid" => $orderid,
        ];

        $valires = $this->validate($param,$rule);

        if($valires!=1){
            return json(["error_code"=>800,"msg"=>$valires]);
        }

        $checkToken=checkToken($uid,$token);

		if($checkToken==700){
            return json(["error_code"=>$checkToken,"msg"=>"您的登陆状态失效，请重新登陆！"]);
        }

        $order_info = db("order")->where("id",$orderid)->find();

        if($order_info['status']==0){
            return json(["error_code"=>1001,"msg"=>"支付失败"]);
        }
        //抽奖类型
        $cagegory = $order_info['cagegory']==1?1:11;
        //查询该订单下的gid
        $gid = $order_info['gid'];
        //查找该gid下的奖品
        $game_reward_list = Cache::get("detail_".$uid."_".$gid);
        $game_reward_list = json_decode($game_reward_list,true);
        $reward = [];
        $reward2 = [];
        for($i=0;$i<$cagegory;$i++){
            foreach ($game_reward_list as $key => $val) {
                $arr[$val['id']] = $val['chance'];
            }
            $rid = get_rand($arr); //根据概率获取奖项id
            if($rid>0){
                $reward[] = $rid;
            }
            $reward2[] = $rid;

        }
        $res = [
            "rewardtype" => $cagegory,
            "reward" => $reward2
        ];
        //添加抽奖纪录
        $record = [
            "orderid" => $orderid,
            "uid" => $uid,
            "gid" => $gid,
            "createtime" => time()
        ];
        if(count($reward)>0){
            $record['isreward'] = 1;
            $record['reward'] = json_encode($reward);
        }

        db("reward_record")->insert($record);
        

        return json(["error_code"=>0,"msg"=>"支付成功","data"=>$res]);
    }

    /*添加中奖账号信息 */
    public function addinfo(){
        $uid = input("uid");
        $token = checkNull(input("token"));
        $gid = input("gameid");
        $cagegory = input("cagegory");
        $orderid = input("orderid");
        $phone_type = input("phonetype");
        $account_type = input("accounttype");
        $account = checkNull(input("account"));
        $phone = checkNull(input("phone"));
        $other = input("other");

        $rule = [
            "uid" => 'require|number',
            "gid" => 'require|number',
            "cagegory" => 'require|number',
            "orderid" => 'require|number',
            "phone_type" => 'require',
            "account_type" => 'require',
            "account" => 'require',
            "phone" => 'require',
        ];

        $param = [
            "uid" => $uid,
            "gid" => $gid,
            "cagegory" => $cagegory,
            "orderid" => $orderid,
            "phone_type" => $phone_type,
            "account_type" => $account_type,
            "account" => $account,
            "phone" => $phone,
            "other" => $other,
        ];

        $valires = $this->validate($param,$rule);

        if($valires!=1){
            return json(["error_code"=>800,"msg"=>$valires]);
        }

        $checkToken=checkToken($uid,$token);

		if($checkToken==700){
            return json(["error_code"=>$checkToken,"msg"=>"您的登陆状态失效，请重新登陆！"]);
        }

        $param['createtime'] = time();
        $res = db("reward_user")->insert($param);
        if(!$res){
            return json(["error_code"=>1001,"msg"=>"添加失败"]);
        }

        return json(["error_code"=>0,"msg"=>"添加成功"]);
    }

    /*抽奖种类 */
    public function typelist(){
        $selectResult = db('game_type')->field("id,name")->select();

        return json(["error_code"=>0,"msg"=>"获取成功","data"=>["list"=>$selectResult]]);
    }

}