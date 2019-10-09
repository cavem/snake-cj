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

use app\admin\model\GamerewardModel;

class Gamereward extends Base
{
    // 奖品列表
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
            if (!empty($param['gid'])) {
                $where['gid'] = $param['gid'];
            }
            $gamereward = new GamerewardModel();
            $selectResult = $gamereward->getGamerewardByWhere($where, $offset, $limit);
                        
            foreach($selectResult as $key=>$vo){
                $gid = $selectResult[$key]['gid'];
                $selectResult[$key]['gid'] = getgname($gid).'('.$gid.')';
                $selectResult[$key]['img'] = '<img src="' . $vo['img'] . '" width="40px" height="40px">';
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id'],$param['pageNumber'],$param['gid']));
            }

            $return['total'] = $gamereward->getAllGamereward($where);  // 总数据
            $return['rows'] = $selectResult;

            return json($return);
        }
        $game_list = db('game')->field('id,title')->select();
        $this->assign(["game_list"=>$game_list]);
        return $this->fetch();
    }

    // 添加奖品
    public function gamerewardAdd()
    {
        if(request()->isPost()){
            $param = input('post.');

            $gamereward = new GamerewardModel();
            
            $flag = $gamereward->insertGamereward($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $gid = input('id');

        $title = db('game')->where("id",$gid)->value("title");

        $this->assign([
            "gid" => $gid,
            "title" => $title
        ]);

        return $this->fetch();
    }

    public function gamerewardEdit()
    {
        $gamereward = new GamerewardModel();
        if(request()->isPost()){

            $param = input('post.');
            $flag = $gamereward->editGamereward($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $id = input('param.id');

        $title = db('game')->where("id",$id)->value("title");


        $this->assign([
            'gamereward' => $gamereward->getOneGamereward($id),
            'title' => $title
        ]);
        return $this->fetch();
    }

    public function gamerewardDel()
    {
        $id = input('param.id');

        $gamereward = new GamerewardModel();
        $flag = $gamereward->delGamereward($id);
        return json(msg($flag['code'], $flag['data'], $flag['msg']));
    }

    // 上传缩略图
    public function uploadImg()
    {
        if(request()->isAjax()){

            $file = request()->file('file');
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'upload');
            if($info){
                $src =  '/upload' . '/' . date('Ymd') . '/' . $info->getFilename();
                return json(msg(0, ['src' => $src], ''));
            }else{
                // 上传失败获取错误信息
                return json(msg(-1, '', $file->getError()));
            }
        }
    }


    /**
     * 拼装操作按钮
     * @param $id
     * @return array
     */
    private function makeButton($id,$pageNumber,$gid)
    {
        return [
            '编辑' => [
                'auth' => 'gamereward/gamerewardedit',
                'href' => url('gamereward/gamerewardedit', ['id' => $id,'pageNumber'=>$pageNumber,'gid'=>$gid]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'gamereward/gamerewarddel',
                'href' => "javascript:gamerewardDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ]
        ];
    }
}
