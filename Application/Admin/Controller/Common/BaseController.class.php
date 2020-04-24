<?php
namespace User\Controller\Common;
use Think\Controller;
class BaseController extends Controller {

    public function _before_index(){
        // $this->checkLogin();
        // $this->checkAuth();
    }

    /**
     * 校验登录状态
     *
     * @return void
     */
    private function checkLoginAction()
    {
        // code 1 成功,0失败,-1未登录或登录过期
        if (!session('?user')) {
            $this->ajaxReturn(array('code' => '-1', 'msg' => '请先登录！'));
        }
    }

    /**
     * 校验操作权限
     *
     * @return void
     */
    public function checkAuthAction()
    {
        // $module = MODULE_NAME;
        $controller = CONTROLLER_NAME;
        $controllerArr = explode('/', $controller);
        $action = ACTION_NAME;
        //获取用户的权限集合
        $user = session('user');
        // $user = M('User')->where('id='.$user['id'])->find();
        // if ($user['is_super'] != 1) {
        //     $authArr = $this->getAuthList($user['id']);
        //     $permArr = [
        //         'm' => false, //模块是否有权限（这里的模块是指控制器所在文件夹名，比如控制器user.php所在文件夹名为permission）
        //         'c' => false, //控制器是否有权限
        //         'a' => false, //方法是否有权限
        //     ];
        //     foreach ($authArr as $v) {
        //         if ($v['rule'] === $controllerArr[0]) {
        //             $permArr['m'] = true;
        //         }
        //         if ($v['rule'] === $controllerArr[0] . ',' . $controllerArr[1]) {
        //             $permArr['c'] = true;
        //         }
        //         if ($v['rule'] === $controllerArr[0] . ',' . $controllerArr[1] . ',' . $action) {
        //             $permArr['a'] = true;
        //         }
        //     }

        //     if (!($permArr['m'] && $permArr['c'] && $permArr['a'])) {
        //         $this->ajaxReturn(array('code' => '0', 'msg' => '您没有权限'));
        //     }
        // }
    }

    /**
     * 获取用户的权限集合
     *
     * @param int $userId
     * @return array
     */
    private function getAuthListAction($userId)
    {
        // $user = UserModel::get(['id' => $userId]);
        // $roleIds = [];
        // $arr = collection($user->role)->toArray();
        // foreach ($arr as $v) {
        //     if ($v['status'] == 1) {
        //         array_push($roleIds, $v['id']);
        //     }
        // }
        // $roleIdsStr = implode(',', $roleIds);
        // $role = RoleModel::all(['id' => ['in', $roleIdsStr], 'status' => 1], 'auth');
        // $roleArr = collection($role)->toArray();
        // $authArr = [];
        // foreach ($roleArr as $v) {
        //     foreach ($v['auth'] as $vv) {
        //         $d = $vv;
        //         unset($d['create_time'], $d['update_time']);
        //         if ($vv['status'] == 1) {
        //             array_push($authArr, $d);
        //         }
        //     }
        // }
        // return $authArr;
    }

}