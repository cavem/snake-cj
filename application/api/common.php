<?php
use think\Cache;
/* 检验手机号 */
function checkMobile($mobile){
    $ismobile = preg_match("/^1[3|4|5|6|7|8|9]\d{9}$/",$mobile);
    if($ismobile){
        return 1;
    }else{
        return 0;
    }
}
/* 密码检查 */
function passcheck($user_pass) {
    $num = preg_match("/^[a-zA-Z]+$/",$user_pass);
    $word = preg_match("/^[0-9]+$/",$user_pass);
    $check = preg_match("/^[a-zA-Z0-9]{6,12}$/",$user_pass);
    if($num || $word ){
        return 2;
    }else if(!$check){
        return 0;
    }		
    return 1;
}
/* 是否注册 */
function checkUser($where){
    if($where==''){
        return 0;
    }

    $isexist=db("users")->where($where)->find();
    
    if($isexist){
        return 1;
    }
    
    return 0;
}

/* ip限定 */
function ip_limit(){
    $date = date("Ymd");
    $ip= ip2long($_SERVER["REMOTE_ADDR"]) ; 
    
    $isexist=db("getcode_limit_ip")
            ->field('ip,date,times')
            ->where('ip',$ip) 
            ->find();
    if(!$isexist){
        $data=array(
            "ip" => $ip,
            "date" => $date,
            "times" => 1,
        );
        $isexist=db("getcode_limit_ip")->insert($data);
        return 0;
    }elseif($date == $isexist['date'] && $isexist['times'] >= 10 ){
        return 1;
    }else{
        if($date == $isexist['date']){
            $isexist=db("getcode_limit_ip")
                    ->where('ip',$ip)
                    ->setInc('times',1);
            return 0;
        }else{
            $isexist=db("getcode_limit_ip")
                    ->where('ip',$ip) 
                    ->update(['date'=> $date ,'times'=>1]);
            return 0;
        }
    }	
}
/* 随机数 */
function random($length = 6 , $numeric = 0) {
    PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
    if($numeric) {
        $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
    } else {
        $hash = '';
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
        $max = strlen($chars) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
    }
    return $hash;
}
/* 发送验证码 */
function sendCode($mobile,$mobile_code){
    $url = 'http://'.$_SERVER['SERVER_NAME'].'/aliyunsms/api_demo/SmsDemo.php?';
    $arr['alisms_access'] = "LTAI4I0A1wU7Ycpj";
    $arr['alisms_key'] = "0SX8QJCzCG2EIiIvWyvJJTSVUjazQ5";
    $arr['alisms_pname'] = "徐州亚瑟网络科技有限公司";
    $arr['alisms_pid'] = "SMS_171857937";
    $arr['mobile'] = $mobile;
    $arr['mobile_code'] = $mobile_code;
    $str = '';
    foreach ($arr as $k => $v) {
        $str .= $k . '=' . $v . '&';
    }
    $res = file_get_contents($url . $str);
    $resArr = json_decode($res,true);
    return $resArr;
}
/* 验证码记录 */
function setSendcode($data){
    if($data){
        $data['addtime']=time();
        db('sendcode')->insert($data);
    }
}
/* 密码加密 */
function setPass($pass){
    $authcode='rCt52pF2cnnKNB3Hkp';
    $pass="###".md5(md5($authcode.$pass));
    return $pass;
}	
/* 去除NULL 判断空处理 主要针对字符串类型*/
function checkNull($checkstr){
    $checkstr=trim($checkstr);
    $checkstr=urldecode($checkstr);
    if(get_magic_quotes_gpc()==0){
        $checkstr=addslashes($checkstr);
    }
    //$checkstr=htmlspecialchars($checkstr);
    //$checkstr=filterEmoji($checkstr);
    if( strstr($checkstr,'null') || (!$checkstr && $checkstr!=0 ) ){
        $str='';
    }else{
        $str=$checkstr;
    }
    return $str;	
}
/* 判断token */
function checkToken($uid,$token) {
    $userinfo=Cache::get("token_".$uid);
    if(!$userinfo){
        $userinfo=db("users")
                    ->field('token,expiretime')
                    ->where('id', $uid)
                    ->find();	
        Cache::set("token_".$uid,$userinfo);
    }

    if($userinfo['token']!=$token || $userinfo['expiretime']<time()){
        return 700;				
    }else{
        return 	0;				
    } 		
}

/**
 * 中奖率算法
 */
function get_rand($proArr) {
    $result = '';
    //概率数组的总概率精度
    $proSum = array_sum($proArr);
    //概率数组循环
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum);
        if ($randNum <= $proCur) {
            $result = $key;
            break;
        } else {
            $proSum -= $proCur;
        }
    }
    unset ($proArr);
    return $result;
}
	