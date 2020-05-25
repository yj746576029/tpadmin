<?php

namespace Api\Controller;

use Api\Controller\BaseController;

class ProfileController extends BaseController
{
    public function index()
    {
        if (IS_GET) {
            $uid = $this->uid;
            $user = M('User')->field('user_name')->where(['id' => $uid])->find();
            json(1, ['name' => $user['user_name'], 'avatar' => 'https://cube.elemecdn.com/0/88/03b0d39583f48206768a7534e55bcpng.png','routers'=>list_to_tree(auth_list())]);
        }
    }

    public function edit()
    {
        if (IS_POST) {
            $userName = I('post.userName');
            $password = I('post.password');
            $newPassword = I('post.newPassword');
            $user = M('User')->where(['user_name' => $userName])->find();
            if ($user['password'] === md5(md5($password) . $user['salt'])) {
                $data['password'] = md5(md5($newPassword) . $user['salt']);
                $re = M('User')->where(['user_name' => $user['user_name']])->save($data);
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
