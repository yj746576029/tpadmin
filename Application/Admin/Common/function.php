<?php
if (!function_exists('create_menu')) {
    function create_menu()
    {
        $user = session('user');
        $user = D('User')->relation(true)->where(['id' => $user['id']])->find();
        if ($user['is_super'] != 1) {
            $roleIds = [];
            foreach ($user['role'] as $v) {
                if ($v['status'] == 1) {
                    array_push($roleIds, $v['id']);
                }
            }
            $roleIdsStr = implode(',', $roleIds);
            $roleArr = D('Role')->relation(true)->where(['id' => ['in', $roleIdsStr]])->select();
            $authArr = [];
            foreach ($roleArr as $v) {
                foreach ($v['auth'] as $vv) {
                    $d = $vv;
                    unset($d['create_time'], $d['update_time']);
                    if ($vv['status'] == 1) {
                        array_push($authArr, $d);
                    }
                }
            }
            $tree = list_to_tree(array_unique($authArr, SORT_REGULAR));
        } else {
            $authList = M('Auth')->select();
            $authArr = [];
            foreach ($authList as $vv) {
                $d = $vv;
                unset($d['create_time'], $d['update_time']);
                if ($vv['status'] == 1) {
                    array_push($authArr, $d);
                }
            }
            $tree = list_to_tree(array_unique($authArr, SORT_REGULAR));
        }
        $controller = strtolower(CONTROLLER_NAME);
        $menuHtml = '<aside class="Hui-aside"><div class="menu_dropdown bk_2">';
        foreach ($tree as $v) {
            $chirldrenCtrls = [];
            foreach ($v['children'] as $vv) {
                array_push($chirldrenCtrls, $vv['rule']);
            }
            if (in_array($controller, $chirldrenCtrls)) {
                $class1 = 'selected';
                $style1 = 'display: block;';
            }
            $menuHtml .= '<dl><dt class="' . $class1 . '"><i class="Hui-iconfont '.$v['icon'].'"></i> ' . $v['auth_name'] . '<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt><dd style="' . $style1 . '"><ul>';
            foreach ($v['children'] as $vv) {
                if ($controller == $vv['rule']) {
                    $class2 = 'current';
                } else {
                    $class2 = '';
                }
                $menuHtml .= '<li class="' . $class2 . '"><a href="' . U('admin/' . $vv['rule'] . '/index') . '" title="' . $vv['auth_name'] . '">' . $vv['auth_name'] . '</a></li>';
            }
            $menuHtml .= '</ul></dd></dl>';
        }
        $menuHtml .= '</div></aside>';
        return $menuHtml;
    }
}
