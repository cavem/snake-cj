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

use app\admin\model\OrderModel;

class Order extends Base
{
    // 订单列表
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

            $order = new OrderModel();
            $selectResult = $order->getOrderByWhere($where, $offset, $limit);

            foreach($selectResult as $key=>$vo){
                $uid = $selectResult[$key]['uid'];
                $selectResult[$key]['uid'] = getuname($uid).'('.$uid.')';
                $gid = $selectResult[$key]['gid'];
                $selectResult[$key]['gid'] = getgname($gid).'('.$gid.')';
                $selectResult[$key]['cagegory'] = $selectResult[$key]['cagegory']==1?"单抽":"11连抽";
                $selectResult[$key]['status'] = $selectResult[$key]['status']==1?"已支付":"未支付";
                $selectResult[$key]['createtime'] = date('Y-m-d H:i:s', $vo['createtime']);
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $order->getAllOrder($where);  // 总数据
            $return['rows'] = $selectResult;

            return json($return);
        }

        return $this->fetch();
    }

    // 添加订单
    public function orderAdd()
    {
        if(request()->isPost()){
            $param = input('post.');

            $order = new OrderModel();
            
            $flag = $order->insertOrder($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        return $this->fetch();
    }

    public function orderEdit()
    {
        $order = new OrderModel();
        if(request()->isPost()){

            $param = input('post.');
            $flag = $order->editOrder($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $id = input('param.id');
        $this->assign([
            'order' => $order->getOneOrder($id)
        ]);
        return $this->fetch();
    }

    public function orderDel()
    {
        $id = input('param.id');

        $order = new OrderModel();
        $flag = $order->delOrder($id);
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
                'auth' => 'order/orderedit',
                'href' => url('order/orderedit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'order/orderdel',
                'href' => "javascript:orderDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ]
        ];
    }
}
