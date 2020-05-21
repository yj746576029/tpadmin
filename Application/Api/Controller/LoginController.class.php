<?php

namespace Api\Controller;

use Think\Controller;

class LoginController extends Controller
{
    public function _initialize()
    {
        cross_origin();
    }

    public function index()
    {
        if (IS_POST) {
            $userName = I('post.userName');
            $password = I('post.password');
            $user = M('User')->where(['user_name' => $userName])->find();
            if (!$user) {
                json(0,'用户不存在');
            } else {
                if ($user['password'] === md5(md5($password) . $user['salt'])) {
                    unset($user['password'], $user['salt']);
                    $token=authcode($user['id'],'ENCODE','',3600*24);
                    json(1,['token'=>$token]);
                } else {
                    json(0,'密码错误');
                }
            }
        }else{
            json(0,'请求出错');
        }
    }

    public function logout()
    {
        // 你可以做一些退出处理...
        json(1);
    }

}