<?php

namespace Api\Controller;

use Think\Controller;

class BaseController extends Controller
{

    public function _empty($name)
    {
        //空操作
        json(0,'请求出错');
    }

    public function _initialize()
    {
        cross_origin();
        $this->checkToken();
        // $this->checkAuth();
        
    }

    /**
     * 校验登录状态
     *
     * @return void
     */
    private function checkToken()
    {
        // code 1 成功,0失败,-1未登录或登录过期
        $uid = authcode($_SERVER['HTTP_X_TOKEN']);
        if (empty($uid)) {
            json(-1,'未登录或登录过期');
        }else{
            $this->uid = $uid;
        }
    }

    /**
     * 校验操作权限
     *
     * @return void
     */
    public function checkAuth()
    {
        // $module = MODULE_NAME;
        $controller = strtolower(CONTROLLER_NAME);
        $action = strtolower(ACTION_NAME);
        $authArr = auth_list();
        
        $permArr = [
            'c' => false, //控制器是否有权限
            'a' => false, //方法是否有权限
        ];
        foreach ($authArr as $v) {
            if ($v['rule'] === $controller) {
                $permArr['c'] = true;
            }
            if ($v['rule'] === $controller . '/' . $action) {
                $permArr['a'] = true;
            }
        }
        if (!($permArr['c'] || $permArr['a'])) {
            $noCheckArr=['index/index'];//忽略校验的控制器/方法
            if(!in_array($controller . '/' . $action,$noCheckArr)){
                json(0,'您没有权限');
            }
        }
    }
}
