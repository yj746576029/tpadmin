<?php

namespace Admin\Controller;

use Admin\Controller\BaseController;

class AuthController extends BaseController
{
    public function index()
    {
        $authList = M('auth')->field('id,auth_name,status,rule,parent_id,create_time,update_time')->select();
        $list = list_to_tree($authList);
        $this->assign('list', $list);
        $this->display();
    }

    public function add()
    {
        if(IS_POST){
            $data['auth_name']=I('post.auth_name');
            $data['rule']=I('post.rule');
            $data['status']=I('post.status');
            $data['parent_id']=I('post.parent_id');
            $re=M('auth')->add($data);
            if($re){
                $this->success('新增成功', U('admin/auth/index'));
            }else{
                $this->error('新增失败');
            }
        }else{
            $authList = M('auth')->field('id,auth_name,status,rule,parent_id,create_time,update_time')->select();
            $list = list_to_tree($authList);
            $this->assign('list', $list);
            $this->display();
        }
    }

    public function edit()
    {
    }

    public function del()
    {
    }
}
