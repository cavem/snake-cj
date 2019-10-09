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

class GamerewardModel extends Model
{
    // 确定链接表名
    protected $name = 'game_reward';

    /**
     * 查询奖品
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getGamerewardByWhere($where, $offset, $limit)
    {
        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    /**
     * 根据搜索条件获取所有的奖品数量
     * @param $where
     */
    public function getAllGamereward($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 插入奖品信息
     * @param $param
     */
    public function insertGamereward($param)
    {
        try{

            $result =  $this->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{

                return msg(1, url('gamereward/index'), '添加奖品成功');
            }
        }catch(PDOException $e){

            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑奖品信息
     * @param $param
     */
    public function editGamereward($param)
    {
        try{

            $result = $this->save($param, ['id' => $param['id']]);

            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{

                return msg(1, url('gamereward/index',['pageNumber'=>$param['pageNumber'],'gid'=>$param["gid_slt"]]), '编辑奖品成功');
            }
        }catch(\Exception $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 根据奖品的id 获取奖品的信息
     * @param $id
     */
    public function getOneGamereward($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * 删除奖品
     * @param $id
     */
    public function delGamereward($id)
    {
        try{

            $this->where('id', $id)->delete();
            
            return msg(1, '', '删除奖品成功');

        }catch(\Exception $e){
            return msg(-1, '', $e->getMessage());
        }
    }
}
