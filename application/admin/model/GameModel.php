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

class GameModel extends Model
{
    // 确定链接表名
    protected $name = 'game';

    /**
     * 查询游戏
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getGameByWhere($where, $offset, $limit)
    {
        return $this->where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    /**
     * 根据搜索条件获取所有的游戏数量
     * @param $where
     */
    public function getAllGame($where)
    {
        return $this->where($where)->count();
    }

    /**
     * 插入游戏信息
     * @param $param
     */
    public function insertGame($param)
    {
        try{

            $result =  $this->save($param);
            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{

                return msg(1, url('game/index'), '添加游戏成功');
            }
        }catch(PDOException $e){

            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 编辑游戏信息
     * @param $param
     */
    public function editGame($param)
    {
        try{

            $result = $this->save($param, ['id' => $param['id']]);

            if(false === $result){
                // 验证失败 输出错误信息
                return msg(-1, '', $this->getError());
            }else{

                return msg(1, url('game/index'), '编辑游戏成功');
            }
        }catch(\Exception $e){
            return msg(-2, '', $e->getMessage());
        }
    }

    /**
     * 根据游戏的id 获取游戏的信息
     * @param $id
     */
    public function getOneGame($id)
    {
        return $this->where('id', $id)->find();
    }

    /**
     * 删除游戏
     * @param $id
     */
    public function delGame($id)
    {
        try{

            $this->where('id', $id)->delete();
            
            return msg(1, '', '删除游戏成功');

        }catch(\Exception $e){
            return msg(-1, '', $e->getMessage());
        }
    }
}
