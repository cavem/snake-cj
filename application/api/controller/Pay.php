<?php
namespace app\api\controller;

use app\api\model\OrderModel;
use think\Controller;

class Pay extends Controller
{
    public function pay_order($orderid)
    {
        $order = new OrderModel();

        //查询订单信息
        $order_info = $order->getOneOrder($orderid);

        $ali = new Alipay(); 

        //异步回调地址
        $url = "http://".$_SERVER['SERVER_NAME'].'/api/Alipay/aliPayBack';
        
        $array = $ali->alipay('获取抽奖次数',$order_info['money'],$order_info['order_no'],$url);
        
        if (!$array) {
            return json(['status' => 0, 'msg' => '对不起请检查相关参数!@']);
        }

        return $array;

    }
}