<?php
namespace app\api\controller;

use think\Controller;
use think\Loader;

class AliPay extends Controller
{
    protected $appId = '2019072365955397';//支付宝AppId
    protected $rsaPrivateKey = 'MIIEpAIBAAKCAQEAvRhE0dFeEBcsouapLQ0YP+RnKCdCzFdzBzHdKR5ym886d084mdqzRMifu1siMvvR4AaPmPtqrXcsSCOPxzbVVVWjJIgZFsJZFn79KRnTiDDxGydn4QEDOLV8poekVdCxFbRUwaCjvu78dGo6//GkdPZc/B+wWkfltK1/I+s1raH2T1R/GKjK9k00Hke6qO/uYhYYhkIW0C6MeJCVRwzHCUy2uxH1kJk53piHQukvIYMgugPre7esnq37mNT5he+sGypjgm3qDFOcZBmU/WXW2oGe0IqZXshyFfZxKMH0sGfP9DGojrA0rd/3ntZbSVJx7qfOHvhxQR3klbMm4lCGAwIDAQABAoIBAB58eSvyu12QvuuMkJ3ozI4QfukkW0qilrMEQQnOFOA16tEtfoyfi+N9DPOh38OkgdPCewhJEIvi+MqwoQ+XMZHOerz7DEB1GPPdiWdE+KuuaFDea4sFJMXRzMT0fXQwbzx2lGubQIsZ8K28KsL07HTG+3rSiRYlHU2h4yuhUkgp23IbIsXvBkO0/3ZqpsqhKP82NNY313FY1OVsTTFuAGDWVxyAWt3cotOnLhDv5OfiNqEpZO5ow0X0/khfXgQc7gDZ6x3mCOfW4dXTlz2uRi9UTLcFUXreMjRrpunzo2AdcyQwZb8CMqEAomXes9ipqBwID42hrwG+1ZO8V/gOXSECgYEA7El54kZlv+dhPGMBvCCEoAKKg0ymyTaRRB/K+X0JJi9PHf/Hph+SJ8sr8+cEWHHwj8nvqthHfV75ubwHO2tw1Hf3IME29tk5PrW7IoxFaa+ppMN2pkrZbCLRa+LHJK9qHMT6ZQIBPkBp/ViAPgddG8wezk3s3LwP5v6X6sPvZ3kCgYEAzN7hU6xfiAMRTMnOzvcYytne7aY7RNlUQGTCo0IP/VYSiAq5iQDo0QoF8yf/QGzR1seqRirV2w+gxYlR+SMZFENOG10GhYs0Gsr5AADc4VRDTwNiup7eYFsvmbUtB9UqZUYZUZ3WtNXz4l+B7TQQKcFsLV+42RnGJRG0j5nXLlsCgYEAxyevCZ8YR8V32XQBlFU8M7mwkbZbpaDOk8LQS7Str3eUkc5ysnxNrii65RrEON/gSPGFhlRA/VS922S2G8kVEqACtaLk9u5kJ4z5K8dbAhdDP1JYgRT5aQF3zh0YiL0pDRKhkd08uwGD1Dtx69Y9Dh7foDdh+zKvnJzd4sw+ztECgYEAmfgv1FjJ+0iC6fkJipY+dcXEO0shZB8JqNORTG1L4xObHc8hnYunbeNgkVxQA7VFB2xL1iw/SUG3jH4ls13jbU0gGISTcC4sXK6K8Ku1VUukwm9C9gqMClK9tYFqdGaKVE6YnLRJNuNNRaaN2R21wAv0Jy23mMI8HoPt9bai990CgYAIaqCiFF7zPO9zQlDnxcUchyVt/hfCZZgjEn3o+Ig39Zr+YnITLHOKrZS/7AdeF/Ft12qiD79GXjWSTI8r3PbOizpSIVOJ5Y3/mbWekSE1iZn7bYbkpggEeQX6bye9c4KmJWpXBYxfMEoJgjLaMw0pPvP7KL/eeHAMSuZOeNyj7g==';//支付宝私钥
    protected $aliPayRsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA82YyGh0eeVgoR5Um8sUQoL1N/evaPcwRVVfhb1rfgGk+GUp5/5YYVqW/P1VSzln4MBaTte6ELcnOPwdk7JIxJhDQlKD+nIuEF+Swq/JLbQFoC1gnN+A5ejkRPAaA2HKvEo5yTp9sg7LZBlnq6mw5DtOnBY01HiDzlvP8/Sa0ZUO43jKqprT7UMKYpdgfXc8JJdmZu/nXpg1sJTdozGOAoc2hJDWBnI3GEmMTvz6lcLtnLMkvpDA+f17JhLejXnWflc84f20Usl+To2SGvmD8lOl4ys7++HzZu4EKkGx6lPYHBVOU7FqQ0+4XwmwVui2UzThMmeNnkwq4PoelSmupvwIDAQAB';//支付宝公钥
    private $seller = '';

    /*
     * 支付宝支付
     */
    public function alipay($body, $total_amount, $product_code, $notify_url)
    {
        /**
         * 调用支付宝接口。
         */
        Loader::import('alipay\aop\AopClient', EXTEND_PATH);
        Loader::import('alipay\aop\request\AlipayTradeAppPayRequest', EXTEND_PATH);

        $aop = new \AopClient();

        $aop->gatewayUrl = "https://openapi.alipay.com/gateway.do";
        $aop->appId = $this->appId;
        $aop->rsaPrivateKey = $this->rsaPrivateKey;
        $aop->format = "json";
        $aop->charset = "UTF-8";
        $aop->signType = "RSA2";
        $aop->alipayrsaPublicKey = $this->aliPayRsaPublicKey;
        $request = new \AlipayTradeAppPayRequest();
        $arr['body'] = $body;
        $arr['subject'] = $body;
        $arr['out_trade_no'] = $product_code;
        $arr['timeout_express'] = '30m';
        $arr['total_amount'] = floatval($total_amount);
        $arr['product_code'] = 'QUICK_MSECURITY_PAY';
        
        $json = json_encode($arr);
        $request->setNotifyUrl($notify_url);
        $request->setBizContent($json);

        $response = $aop->sdkExecute($request);
        return $response;

    }

    /*
     * 支付宝支付回调修改订单状态
     */
    public function aliPayBack()
    {
        if ($_POST['trade_status'] == 'TRADE_SUCCESS') {//如果支付成功
            //===============修改订单状态===========================//
            $order_no = $_POST['out_trade_no'];//获取订单号
            $where['order_no'] = $order_no;
            $data['status'] = 1;
            $data['trade_no'] = $_POST['trade_no'];
            db("order")->where($where)->update($data);//修改订单状态 插入交易号
            echo 'success';
            exit;
        }
    }

    function createLinkstring($para)
    {
        $arg = "";
        while (list ($key, $val) = each($para)) {
            $arg .= $key . "=" . $val . "&";
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, count($arg) - 2);

        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $arg = stripslashes($arg);
        }

        return $arg;
    }


    function argSort($para)
    {
        ksort($para);
        reset($para);
        return $para;
    }


}