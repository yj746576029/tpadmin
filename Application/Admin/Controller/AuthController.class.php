<?php

namespace Admin\Controller;

use Admin\Controller\BaseController;

class AuthController extends BaseController
{
    public function index()
    {
        $authList = M('Auth')->field('id,auth_name,status,rule,parent_id,create_time,update_time')->select();
        $list = list_to_tree($authList);
        $this->assign('list', $list);
        $this->display();
    }

    public function add()
    {
        if (IS_POST) {
            $data['auth_name'] = I('post.auth_name');
            $data['rule'] = I('post.rule');
            $data['status'] = I('post.status');
            $data['parent_id'] = I('post.parent_id');
            $data['create_time'] = time();
            $data['update_time'] = $data['create_time'];
            $re = M('Auth')->add($data);
            if ($re) {
                $this->success('新增成功', U('admin/auth/index'));
            } else {
                $this->error('新增失败');
            }
        } else {
            $authList = M('Auth')->field('id,auth_name,status,rule,parent_id,create_time,update_time')->select();
            $list = list_to_tree($authList);
            $this->assign('list', $list);
            $this->display();
        }
    }

    public function edit()
    {
        if (IS_POST) {
            if(IS_AJAX){
                $id = I('post.id');
                $data['status'] = I('post.status');
                $data['update_time'] = time();
                $re = M('Auth')->where(['id' => $id])->save($data);
                if ($re) {
                    $this->ajaxReturn(['code'=>1,'msg'=>'成功']);
                } else {
                    $this->ajaxReturn(['code'=>0,'msg'=>'失败']);
                }
            }else{
                $id = I('post.id');
                $data['auth_name'] = I('post.auth_name');
                $data['rule'] = I('post.rule');
                $data['status'] = I('post.status');
                $data['parent_id'] = I('post.parent_id');
                $data['update_time'] = time();
                $re = M('Auth')->where(['id' => $id])->save($data);
                if ($re) {
                    $this->success('编辑成功', U('admin/auth/index'));
                } else {
                    $this->error('编辑失败');
                }
            }
        } else {
            $id = I('get.id');
            $auth = M('Auth')->where(['id' => $id])->find();
            $authList = M('Auth')->field('id,auth_name,status,rule,parent_id,create_time,update_time')->select();
            $list = list_to_tree($authList);
            $this->assign('list', $list);
            $this->assign('item', $auth);
            $this->display();
        }
    }

    public function del()
    {
        $id = I('get.id');
        $re = M('Auth')->where(['parent_id' => $id])->find();
        if ($re) {
            $this->error('当前规则下有子规则，不能直接删除');
        } else {
            $res = M('Auth')->where(['id' => $id])->delete();
            if ($res) {
                $this->success('删除成功', U('admin/auth/index'));
            } else {
                $this->error('删除失败');
            }
        }
    }
}
