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
namespace app\admin\controller;

use app\admin\model\RewardModel;

class Reward extends Base
{
    // 抽奖列表
    public function index()
    {
        if(request()->isAjax()){

            $param = input('param.');

            $limit = $param['pageSize'];
            $offset = ($param['pageNumber'] - 1) * $limit;

            $where = [];
            if (!empty($param['searchText'])) {
                $where['title'] = ['like', '%' . $param['searchText'] . '%'];
            }

            $reward = new RewardModel();
            $selectResult = $reward->getRewardByWhere($where, $offset, $limit);

            foreach($selectResult as $key=>$vo){
                $uid = $selectResult[$key]['uid'];
                $selectResult[$key]['uid'] = getuname($uid).'('.$uid.')';
                $gid = $selectResult[$key]['gid'];
                $selectResult[$key]['gid'] = getgname($gid).'('.$gid.')';
                $selectResult[$key]['isreward'] = $selectResult[$key]['isreward']==1?"中奖":"未中奖";
                $selectResult[$key]['reward'] = getRewardname($selectResult[$key]['reward']);
                $selectResult[$key]['createtime'] = date('Y-m-d H:i:s', $vo['createtime']);
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $reward->getAllReward($where);  // 总数据
            $return['rows'] = $selectResult;

            return json($return);
        }

        return $this->fetch();
    }

    // 添加抽奖
    public function rewardAdd()
    {
        if(request()->isPost()){
            $param = input('post.');

            $reward = new RewardModel();
            
            $flag = $reward->insertReward($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        return $this->fetch();
    }

    public function rewardEdit()
    {
        $reward = new RewardModel();
        if(request()->isPost()){

            $param = input('post.');
            $flag = $reward->editReward($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $id = input('param.id');
        $this->assign([
            'reward' => $reward->getOneReward($id)
        ]);
        return $this->fetch();
    }

    public function rewardDel()
    {
        $id = input('param.id');

        $reward = new RewardModel();
        $flag = $reward->delReward($id);
        return json(msg($flag['code'], $flag['data'], $flag['msg']));
    }


    /**
     * 拼装操作按钮
     * @param $id
     * @return array
     */
    private function makeButton($id)
    {
        return [
            '编辑' => [
                'auth' => 'reward/rewardedit',
                'href' => url('reward/rewardedit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'reward/rewarddel',
                'href' => "javascript:rewardDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ]
        ];
    }
}
