<?php
namespace app\api\controller;

use app\api\model\UsersModel;
use app\api\model\RewardrecordModel;

use think\Controller;
use think\Cache;

class User extends Controller
{
    public function _initialize(){
        session_start();
    }
    /*登录*/
    public function login(){
        $phonenum=checkNull(input('phonenum'));
		$password=checkNull(input('password'));

        $users = new UsersModel();
        $info = $users->userLogin($phonenum,$password);

		if($info['error_code']==1001){
            return json(["error_code"=>1001,"msg"=>"账号或密码错误"]);
		}
        
        return json(["error_code"=>0,"msg"=>"登录成功","data"=>$info['data']]);
    }
    /*注册*/
    public function register(){
        $phonenum = checkNull(input("phonenum"));
        $verifycode = checkNull(input("verifycode"));
        $password = checkNull(input("password"));
        $password2 = checkNull(input("password2"));
        if(!cache('reg_mobile') || !cache('reg_mobile_code')){
            return json(["error_code"=>1001,"msg"=>"请先获取验证码"]);		
        }
	
		if(cache('reg_mobile')&&$phonenum!=cache('reg_mobile')){
            return json(["error_code"=>1002,"msg"=>"手机号码不一致"]);				
		}

		if(cache('reg_mobile_code')&&$verifycode!=cache('reg_mobile_code')){
            return json(["error_code"=>1003,"msg"=>"验证码错误"]);				
		}	

		if($password!=$password2){
            return json(["error_code"=>1008,"msg"=>"两次输入的密码不一致"]);			
		}	
        
		$check = passcheck($password);

		if($check==0){
            return json(["error_code"=>1004,"msg"=>"密码6-12位数字与字母"]);
        }else if($check==2){
            return json(["error_code"=>1005,"msg"=>"密码不能纯数字或纯字母"]);									
        }			
		$users = new UsersModel();
		$info = $users->userReg($phonenum,$password);

		if($info['error_code']==1006){
            return json(["error_code"=>1006,"msg"=>"该手机号已被注册！"]);	
		}else if($info['error_code']==1007){
            return json(["error_code"=>1007,"msg"=>"注册失败，请重试"]);	
		}
		
		// $_SESSION['reg_mobile'] = '';
		// $_SESSION['reg_mobile_code'] = '';
		// $_SESSION['reg_mobile_expiretime'] = '';
		cache('reg_mobile',NULL);
        cache('reg_mobile_code',NULL);
        cache('reg_mobile_expiretime',NULL);	
        return json(["error_code"=>0,"msg"=>"注册成功","data"=>$info['data']]);
        
    }
    /*发送验证码*/
    public function sendcode(){
        $phonenum = checkNull(input("phonenum"));

        $ismobile=checkMobile($phonenum);
		if(!$ismobile){
			return json(["error_code"=>1001,"msg"=>"请输入正确的手机号"]);	
		}
        
        $where="user_login='{$phonenum}'";
        
		$checkuser = checkUser($where);	
        
        if($checkuser){
            return json(["error_code"=>1002,"msg"=>"该手机号已注册，请登录"]);
        }

        if(cache('reg_mobile')&&cache('reg_mobile')==$phonenum &&cache('reg_mobile_expiretime')&&cache('reg_mobile_expiretime')> time() ){
            return json(["error_code"=>1003,"msg"=>"验证码5分钟有效，请勿多次发送"]);
		}

        $limit = ip_limit();	
		if( $limit == 1){
            return json(["error_code"=>1004,"msg"=>"您已当日发送次数过多"]);
		}

		$mobile_code = random(6,1);
        $resArr = sendCode($phonenum,$mobile_code);
        if ($resArr['Message'] == 'OK') {
            // $_SESSION['reg_mobile'] = $phonenum;
            // $_SESSION['reg_mobile_code'] = $mobile_code;
            // $_SESSION['reg_mobile_expiretime'] = time() +60*5;
            cache('reg_mobile',$phonenum);
            cache('reg_mobile_code',$mobile_code);
            cache('reg_mobile_expiretime',time() +60*5);
            //添加验证码记录
            setSendcode(array('account'=>$phonenum,'content'=>$mobile_code));
        }else{
            return json(["error_code"=>1005,"msg"=>"发送失败"]);
        }
        

        return json(["error_code"=>0,"msg"=>"发送成功"]);
    }
    /*我的抽奖列表*/
    public function myluckylist(){
        $uid = checkNull(input("userId"));
        $page = input("page")?input("page"):1;
        $users = new UsersModel();
        $userinfo = $users->getUserinfoByid($uid);

        if(!$userinfo){
            return json(["error_code"=>1001,"msg"=>"用户不存在"]);
        }

        $limit = 10;
        $offset = ($page - 1) * $limit;

        $where = [];

        $where['id'] = $uid;

        $rewardrecord = new RewardrecordModel();

        $selectResult = $rewardrecord->getRecordByWhere($where, $offset, $limit);

        $total = $rewardrecord->getAllRecord($where);

        return json(["error_code"=>0,"msg"=>"获取成功","data"=>["count"=>$total,"page_index"=>$page,"list"=>$selectResult]]);
    }
}