<?php
namespace app\api\model;

use think\Model;
use think\Cache;

class OrderModel extends Model
{
    // 确定链接表名
    protected $name = 'order';

    /**
     * 插入订单信息
     * @param $param
     */
    public function insertOrder($param)
    {
        try{
            $result =  $this->save($param);
            
            if(!$result){
                return 1001;
            }

            $orderid = $this->getLastInsID();

            return ["orderid"=>$orderid];

        }catch(PDOException $e){

            return 1002;
        }
    }

    /**
     * 根据订单id获取订单信息
     * @param $id
     */
    public function getOneOrder($id)
    {
        return $this->where('id', $id)->find();
    }

}