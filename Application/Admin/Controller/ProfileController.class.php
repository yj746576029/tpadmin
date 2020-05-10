<?php

namespace Admin\Controller;

use Admin\Controller\BaseController;

class ProfileController extends BaseController
{
    public function index()
    {
        $user = session('user');
        $this->assign('user', $user);
        $this->display();
    }

    public function edit()
    {
        if (IS_POST) {
            $account = I('post.account');
            $password = I('post.password');
            $password2 = I('post.password2');
            $user = M('User')->where(['account' => $account])->find();
            if ($user['password'] === md5(md5($password) . $user['salt'])) {
                $data['password'] = md5(md5($password2) . $user['salt']);
                $re = M('User')->where(['account' => $user['account']])->save($data);
                if ($re) {
                    session('user', null);
                    $this->success('修改成功，请重新登录', U('admin/login/index'));
                } else {
                    $this->error('修改失败');
                }
            } else {
                $this->error('原始密码错误');
            }
        } else {
            $user = session('user');
            $this->assign('user', $user);
            $this->display();
        }
    }
}
