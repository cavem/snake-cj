<?php
namespace app\api\model;

use think\Model;
use think\Cache;

class UsersModel extends Model
{
    // 确定链接表名
    protected $name = 'users';

    /**
     * 用户登录
     */
     public function userLogin($phonenum,$password){
        $res = ["error_code"=>0,"data"=>[]];
        $map = [
            "user_login" => $phonenum,
            "user_pass" => md5($password)
        ];
        $info = $this->where($map)->find();

        if(!$info){
            $res["error_code"] = 1001;
            return $res;
        }

        $token = md5(md5($info['id'].$info['user_login'].time()));

        $info['token'] = $token;

        $this->updateToken($info['id'],$token);

        $res['data'] = $info;
        return $res;
     }

    /**
     * 用户注册
     */
    public function userReg($phonenum,$password)
    {
        $res = ["error_code"=>0,"data"=>[]];
        $isexsit = $this->where("user_login",$phonenum)->find();

        if($isexsit){
            $res["error_code"] = 1006;
            return $res;
        }

        $userdata = [
            "user_login" => $phonenum,
            "user_pass" => md5($password),
            "last_login_ip" => $_SERVER["REMOTE_ADDR"],
            "last_login_time" => date("Y-m-d H:i:s"),
            "create_time" => time()
        ];
        
        $instres = $this->insert($userdata);

        if(!$instres){
            $res["error_code"] = 1007;
            return $res;
        }

        $uid = $this->getLastInsID();

        $res["data"] = ["uid"=>$uid];

        return $res;
    }

    /*根据uid获取用户信息*/
    public function getUserinfoByid($uid){
        $res = $this->where("id",$uid)->find();
        if(!$res){
            return 0;
        }
        return $res;
    }

    /* 更新token 登陆信息 */
    public function updateToken($uid,$token,$data=array()) {
        $expiretime=time()+60*60*24*300;
        
        $this->where("id",$uid)
             ->update(["token"=>$token,"expiretime"=>$expiretime ,'last_login_time' => date("Y-m-d H:i:s"), "last_login_ip"=>$_SERVER['REMOTE_ADDR']]);

		$token_info=array(
			'uid'=>$uid,
			'token'=>$token,
			'expiretime'=>$expiretime,
		);
		
		Cache::set("token_".$uid,$token_info);		
        
		return 1;
    }
}