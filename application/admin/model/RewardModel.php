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

class RewardModel extends Model
{
    // 确定链接表名
    protected $name = 'reward_record';

    /**
     * 查询抽奖
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getRewardByWhere($where, $offset, $limit)
    {
        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    /**
     * 根据搜索条件获取所有的抽奖数量
     * @param $where
     */
    public function getAllReward($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 插入抽奖信息
     * @param $param
     */
    public function insertReward($param)
    {
        try{

            $result =  $this->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{

                return msg(1, url('reward/index'), '添加抽奖成功');
            }
        }catch(PDOException $e){

            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑抽奖信息
     * @param $param
     */
    public function editReward($param)
    {
        try{

            $result = $this->save($param, ['id' => $param['id']]);

            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{

                return msg(1, url('reward/index'), '编辑抽奖成功');
            }
        }catch(\Exception $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 根据抽奖的id 获取抽奖的信息
     * @param $id
     */
    public function getOneReward($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * 删除抽奖
     * @param $id
     */
    public function delReward($id)
    {
        try{

            $this->where('id', $id)->delete();
            
            return msg(1, '', '删除抽奖成功');

        }catch(\Exception $e){
            return msg(-1, '', $e->getMessage());
        }
    }
}
