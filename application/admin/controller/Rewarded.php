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

use app\admin\model\RewardedModel;

class Rewarded extends Base
{
    // 中奖列表
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

            $rewarded = new RewardedModel();
            $selectResult = $rewarded->getRewardedByWhere($where, $offset, $limit);

            foreach($selectResult as $key=>$vo){
                $uid = $selectResult[$key]['uid'];
                $selectResult[$key]['uid'] = getuname($uid).'('.$uid.')';
                $gid = $selectResult[$key]['gid'];
                $selectResult[$key]['gid'] = getgname($gid).'('.$gid.')';
                $selectResult[$key]['cagegory'] = $selectResult[$key]['cagegory']==1?"单抽":"11连抽";
                $selectResult[$key]['phone_type'] = $selectResult[$key]['phone_type']==1?"安卓":"苹果";
                $selectResult[$key]['account_type'] = $selectResult[$key]['account_type']==1?"微信":"QQ";
                $selectResult[$key]['createtime'] = date('Y-m-d H:i:s', $vo['createtime']);
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $rewarded->getAllRewarded($where);  // 总数据
            $return['rows'] = $selectResult;

            return json($return);
        }

        return $this->fetch();
    }

    // 添加中奖
    public function rewardedAdd()
    {
        if(request()->isPost()){
            $param = input('post.');

            $rewarded = new RewardedModel();
            
            $flag = $rewarded->insertRewarded($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        return $this->fetch();
    }

    public function rewardedEdit()
    {
        $rewarded = new RewardedModel();
        if(request()->isPost()){

            $param = input('post.');
            $flag = $rewarded->editRewarded($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $id = input('param.id');
        $this->assign([
            'rewarded' => $rewarded->getOneRewarded($id)
        ]);
        return $this->fetch();
    }

    public function rewardedDel()
    {
        $id = input('param.id');

        $rewarded = new RewardedModel();
        $flag = $rewarded->delRewarded($id);
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
                'auth' => 'rewarded/rewardededit',
                'href' => url('rewarded/rewardededit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'rewarded/rewardeddel',
                'href' => "javascript:rewardedDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ]
        ];
    }
}
