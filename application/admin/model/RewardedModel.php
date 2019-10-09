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

class RewardedModel extends Model
{
    // 确定链接表名
    protected $name = 'reward_user';

    /**
     * 查询中奖
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getRewardedByWhere($where, $offset, $limit)
    {
        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    /**
     * 根据搜索条件获取所有的中奖数量
     * @param $where
     */
    public function getAllRewarded($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 插入中奖信息
     * @param $param
     */
    public function insertRewarded($param)
    {
        try{

            $result =  $this->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{

                return msg(1, url('rewarded/index'), '添加中奖成功');
            }
        }catch(PDOException $e){

            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑中奖信息
     * @param $param
     */
    public function editRewarded($param)
    {
        try{

            $result = $this->save($param, ['id' => $param['id']]);

            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{

                return msg(1, url('rewarded/index'), '编辑中奖成功');
            }
        }catch(\Exception $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 根据中奖的id 获取中奖的信息
     * @param $id
     */
    public function getOneRewarded($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * 删除中奖
     * @param $id
     */
    public function delRewarded($id)
    {
        try{

            $this->where('id', $id)->delete();
            
            return msg(1, '', '删除中奖成功');

        }catch(\Exception $e){
            return msg(-1, '', $e->getMessage());
        }
    }
}
