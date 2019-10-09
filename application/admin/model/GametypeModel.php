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

class GametypeModel extends Model
{
    // 确定链接表名
    protected $name = 'game_type';

    /**
     * 查询游戏类型
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getGametypeByWhere($where, $offset, $limit)
    {
        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    /**
     * 根据搜索条件获取所有的游戏类型数量
     * @param $where
     */
    public function getAllGametype($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 插入游戏类型信息
     * @param $param
     */
    public function insertGametype($param)
    {
        try{

            $result =  $this->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{

                return msg(1, url('gametype/index'), '添加游戏类型成功');
            }
        }catch(PDOException $e){

            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑游戏类型信息
     * @param $param
     */
    public function editGametype($param)
    {
        try{

            $result = $this->save($param, ['id' => $param['id']]);

            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{

                return msg(1, url('gametype/index'), '编辑游戏类型成功');
            }
        }catch(\Exception $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 根据游戏类型的id 获取游戏类型的信息
     * @param $id
     */
    public function getOneGametype($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * 删除游戏类型
     * @param $id
     */
    public function delGametype($id)
    {
        try{

            $this->where('id', $id)->delete();
            
            return msg(1, '', '删除游戏类型成功');

        }catch(\Exception $e){
            return msg(-1, '', $e->getMessage());
        }
    }
}
