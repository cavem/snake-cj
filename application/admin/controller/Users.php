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

use app\admin\model\UsersModel;

class Users extends Base
{
    // 用户列表
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

            $users = new UsersModel();
            $selectResult = $users->getUsersByWhere($where, $offset, $limit);

            foreach($selectResult as $key=>$vo){
                $selectResult[$key]['create_time'] = date('Y-m-d H:i:s', $vo['create_time']);
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $users->getAllUsers($where);  // 总数据
            $return['rows'] = $selectResult;

            return json($return);
        }

        return $this->fetch();
    }

    // 添加用户
    public function usersAdd()
    {
        if(request()->isPost()){
            $param = input('post.');

            $users = new UsersModel();
            
            $flag = $users->insertUsers($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        return $this->fetch();
    }

    public function usersEdit()
    {
        $users = new UsersModel();
        if(request()->isPost()){

            $param = input('post.');
            $flag = $users->editUsers($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $id = input('param.id');
        $this->assign([
            'users' => $users->getOneUsers($id)
        ]);
        return $this->fetch();
    }

    public function usersDel()
    {
        $id = input('param.id');

        $users = new UsersModel();
        $flag = $users->delUsers($id);
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
                'auth' => 'users/usersedit',
                'href' => url('users/usersedit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'users/usersdel',
                'href' => "javascript:usersDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ]
        ];
    }
}
