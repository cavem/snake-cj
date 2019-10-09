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
namespace app\api\model;

use think\Model;

class RewardrecordModel extends Model
{
    // 确定链接表名
    protected $name = 'reward_record';

    /**
     * 根据搜索条件获取记录列表信息
     * @param $where
     * @param $offset
     * @param $limit
     */
    public function getRecordByWhere($where, $offset, $limit)
    {
        return $this->where($where)->order('id desc')->select();
    }
    /**
     * 根据搜索条件获取所有的记录数量
     * @param $where
     */
    public function getAllRecord($where)
    {
        return $this->where($where)->count();
    }
}