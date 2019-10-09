<?php
// +----------------------------------------------------------------------
// | snake
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 http://baiyf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: NickBai <1902822973@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model;

use think\Model;

class OrderModel extends Model
{
    // 确定链接表名
    protected $name = 'order';

    /**
     * 查询订单
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getOrderByWhere($where, $offset, $limit)
    {
        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    /**
     * 根据搜索条件获取所有的订单数量
     * @param $where
     */
    public function getAllOrder($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 插入订单信息
     * @param $param
     */
    public function insertOrder($param)
    {
        try{

            $result =  $this->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{

                return msg(1, url('order/index'), '添加订单成功');
            }
        }catch(PDOException $e){

            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑订单信息
     * @param $param
     */
    public function editOrder($param)
    {
        try{

            $result = $this->save($param, ['id' => $param['id']]);

            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{

                return msg(1, url('order/index'), '编辑订单成功');
            }
        }catch(\Exception $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 根据订单的id 获取订单的信息
     * @param $id
     */
    public function getOneOrder($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * 删除订单
     * @param $id
     */
    public function delOrder($id)
    {
        try{

            $this->where('id', $id)->delete();
            
            return msg(1, '', '删除订单成功');

        }catch(\Exception $e){
            return msg(-1, '', $e->getMessage());
        }
    }
}
