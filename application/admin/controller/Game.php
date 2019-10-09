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

use app\admin\model\GameModel;
use app\admin\model\GametypeModel;

class Game extends Base
{
    // 游戏列表
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

            $game = new GameModel();
            $selectResult = $game->getGameByWhere($where, $offset, $limit);

            foreach($selectResult as $key=>$vo){
                $selectResult[$key]['img'] = '<img src="' . $vo['img'] . '" width="40px" height="40px">';
                $reward_list = db('game_reward')->field('chance,price')->where('gid',$vo['id'])->select();
                $gl = 0;
                foreach($reward_list as $v){
                    $gl+=$v['price']*($v['chance']/100);
                }
                $selectResult[$key]['gl'] = $gl;
                $selectResult[$key]['operate'] = showOperate($this->makeButton($vo['id']));
            }

            $return['total'] = $game->getAllGame($where);  // 总数据
            $return['rows'] = $selectResult;

            return json($return);
        }

        return $this->fetch();
    }

    // 添加游戏
    public function gameAdd()
    {
        if(request()->isPost()){
            $param = input('post.');

            $game = new GameModel();
            
            $flag = $game->insertGame($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $gametype = new GametypeModel();

        $game_type = $gametype->getGametypeByWhere(["id"=>["gt",0]],0,10);

        $this->assign([
            "game_type" => $game_type
        ]);

        return $this->fetch();
    }

    public function gameEdit()
    {
        $game = new GameModel();
        if(request()->isPost()){

            $param = input('post.');
            $flag = $game->editGame($param);

            return json(msg($flag['code'], $flag['data'], $flag['msg']));
        }

        $id = input('param.id');

        $gametype = new GametypeModel();

        $game_type = $gametype->getGametypeByWhere(["id"=>["gt",0]],0,10);
        $this->assign([
            'game' => $game->getOneGame($id),
            "game_type" => $game_type
        ]);
        return $this->fetch();
    }

    public function gameDel()
    {
        $id = input('param.id');

        $game = new GameModel();
        $flag = $game->delGame($id);
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
    private function makeButton($id)
    {
        return [
            '编辑' => [
                'auth' => 'game/gameedit',
                'href' => url('game/gameedit', ['id' => $id]),
                'btnStyle' => 'primary',
                'icon' => 'fa fa-paste'
            ],
            '删除' => [
                'auth' => 'game/gamedel',
                'href' => "javascript:gameDel(" . $id . ")",
                'btnStyle' => 'danger',
                'icon' => 'fa fa-trash-o'
            ],
            '添加奖品' => [
                'auth' => 'gamereward/gamerewardadd',
                'href' => url('gamereward/gamerewardadd', ['id' => $id]),
                'btnStyle' => 'info',
                'icon' => 'fa fa-plus'
            ],
        ];
    }
}
