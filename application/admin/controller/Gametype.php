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

use app\admin\model\GametypeModel;

class Gametype extends Base
{
    // 游戏类型列表
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

            $gametype = new GametypeModel();
            $selectResult = $gametype->getGametypeByWhere($where, $offset, $limit);

            foreach($selectResult as $key=>$vo){
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $gametype->getAllGametype($where);  // 总数据
            $return['rows'] = $selectResult;

            return json($return);
        }

        return $this->fetch();
    }

    // 添加游戏类型
    public function gametypeAdd()
    {
        if(request()->isPost()){
            $param = input('post.');

            $gametype = new GametypeModel();
            
            $flag = $gametype->insertGametype($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        return $this->fetch();
    }

    public function gametypeEdit()
    {
        $gametype = new GametypeModel();
        if(request()->isPost()){

            $param = input('post.');
            $flag = $gametype->editGametype($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $id = input('param.id');
        $this->assign([
            'gametype' => $gametype->getOneGametype($id)
        ]);
        return $this->fetch();
    }

    public function gametypeDel()
    {
        $id = input('param.id');

        $gametype = new GametypeModel();
        $flag = $gametype->delGametype($id);
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
                'auth' => 'gametype/gametypeedit',
                'href' => url('gametype/gametypeedit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'gametype/gametypedel',
                'href' => "javascript:gametypeDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ]
        ];
    }
}
