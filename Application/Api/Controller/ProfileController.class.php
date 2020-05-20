<?php

namespace Api\Controller;

use Api\Controller\BaseController;

class ProfileController extends BaseController
{
    public function index()
    {
        if (IS_GET) {
            $uid = $this->uid;
            $user = M('User')->field('username')->where(['id' => $uid])->find();
            json(1, ['name' => $user['username'], 'avatar' => 'https://cube.elemecdn.com/0/88/03b0d39583f48206768a7534e55bcpng.png']);
        }
    }

    public function edit()
    {
        if (IS_POST) {
            $username = I('post.username');
            $password = I('post.password');
            $newPassword = I('post.newPassword');
            $user = M('User')->where(['username' => $username])->find();
            if ($user['password'] === md5(md5($password) . $user['salt'])) {
                $data['password'] = md5(md5($newPassword) . $user['salt']);
                $re = M('User')->where(['username' => $user['username']])->save($data);
                if ($re) {
                    json(1);
                } else {
                    json(0,'修改失败');
                }
            } else {
                json(0,'原始密码错误');
            }
        }
    }
}
