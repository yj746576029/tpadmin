<?php

namespace Api\Controller;

use Api\Controller\BaseController;

class AuthController extends BaseController
{
    public function index()
    {
        $authList = M('Auth')->field('id,auth_name,status,rule,parent_id,sort')->select();
        $list = list_to_tree($authList);
        json(1, ['list' => $list]);
    }

    public function add()
    {
        if (IS_POST) {
            $data['auth_name'] = I('post.authName');
            $data['rule'] = I('post.rule');
            $data['status'] = I('post.status');
            $pid = I('post.parentId');
            if ($pid == '') {
                $data['parent_id'] = 0;
            } else {
                $data['parent_id'] = $pid[count($pid) - 1];
            }
            $data['sort'] = I('post.sort');
            $data['create_time'] = time();
            $data['update_time'] = $data['create_time'];
            if ($data['parent_id'] == 0) {
                $data['icon'] = I('post.icon');
            } else {
                $data['icon'] = '';
            }
            $re = M('Auth')->add($data);
            if ($re) {
                json(1);
            } else {
                json(1, '新增失败');
            }
        }
    }

    public function edit()
    {
        if (IS_POST) {
            $id = I('post.id');
            if (I('post.authName')!='') {
                $data['auth_name'] = I('post.authName');
            }
            if (I('post.rule')!='') {
                $data['rule'] = I('post.rule');
            }
            if (I('post.status')!='') {
                $data['status'] = I('post.status');
            }
            if (I('post.parentId')!='') {
                $data['parent_id'] = I('post.parentId');
            }
            if (I('post.sort')!='') {
                $data['sort'] = I('post.sort');
            }
            if (I('post.icon2')!='') {
                $data['icon2'] = I('post.icon2');
            }
            $data['update_time'] = time();
            if ($data['parent_id'] == 0) {
                $data['icon'] = I('post.icon');
            } else {
                $data['icon'] = '';
            }
            $re = M('Auth')->where(['id' => $id])->save($data);
            if ($re) {
                json(1);
            } else {
                json(0, '编辑失败');
            }
        } else {
            $id = I('get.id');
            $auth = M('Auth')->where(['id' => $id])->find();
            json(1, ['detail' => $auth]);
        }
    }

    public function del()
    {
        $id = I('post.id');
        $re = M('Auth')->where(['parent_id' => $id])->find();
        if ($re) {
            json(0, '当前规则下有子节点，不能直接删除');
        } else {
            $res = M('Auth')->where(['id' => $id])->delete();
            if ($res) {
                json(1);
            } else {
                json(0, '删除失败');
            }
        }
    }
}
