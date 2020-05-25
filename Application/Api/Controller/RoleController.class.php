<?php

namespace Api\Controller;

use Api\Controller\BaseController;

class RoleController extends BaseController
{
    public function index()
    {
        $page = empty(I('get.page')) ? 1 : I('get.page');
        $pageSize = 10;
        $total = M('User')->count();
        $list = M('Role')->page($page . ',' . $pageSize)->select();
        $authList1 = M('Auth')->field('id,auth_name,parent_id')->where(['status' => 1])->select();
        $authList2 = list_to_tree($authList1);
        json(1, ['list' => $list, 'total' => $total * 1, 'page' => $page, 'page_size' => $pageSize, 'auth_list' => $authList1, 'auth_list_tree' => $authList2]);
    }

    public function add()
    {
        if (IS_POST) {
            $data['role_name'] = I('post.roleName');
            $data['status'] = I('post.status');
            $data['create_time'] = time();
            $data['update_time'] = $data['create_time'];
            M()->startTrans();
            $re = D('Role')->add($data);
            if ($re) {
                $auth_ids = I('post.authids');
                $dataList = [];
                foreach ($auth_ids as $v) {
                    $item = array('role_id' => $re, 'auth_id' => $v);
                    array_push($dataList, $item);
                }
                $res = D('RoleAuth')->addAll($dataList);
                if ($res) {
                    M()->commit();
                    json(1);
                } else {
                    M()->rollback();
                    json(0, '新增失败');
                }
            } else {
                M()->rollback();
                json(0, '新增失败');
            }
        }
    }

    public function edit()
    {
        if (IS_POST) {
            M()->startTrans();
            $id = I('post.id');
            if (I('post.roleName') != '') {
                $data['role_name'] = I('post.roleName');
            }
            if (I('post.status') != '') {
                $data['status'] = I('post.status');
            }
            $data['update_time'] = time();
            $re = M('Role')->where(['id' => $id])->save($data);
            if ($re) {
                if (!empty(I('post.authids'))) {
                    D('RoleAuth')->where(['role_id' => $id])->delete();
                    $auth_ids = I('post.authids');
                    $dataList = [];
                    foreach ($auth_ids as $v) {
                        $item = array('role_id' => $id, 'auth_id' => $v);
                        array_push($dataList, $item);
                    }
                    $res = D('RoleAuth')->addAll($dataList);
                    if ($res) {
                        M()->commit();
                        json(1);
                    } else {
                        M()->rollback();
                        json(1, '编辑失败');
                    }
                } else {
                    M()->commit();
                    json(1);
                }
            } else {
                M()->rollback();
                json(1, '编辑失败');
            }
        } else {
            $id = I('get.id');
            $role = D('Role')->relation(true)->where(['id' => $id])->find();
            $auth_ids = [];
            foreach ($role['auth'] as $v) {
                array_push($auth_ids, $v['id']);
            }
            $role['auth'] = $auth_ids;
            json(1, ['detail' => $role]);
        }
    }

    public function del()
    {
        $id = I('post.id');
        M()->startTrans();
        $res = M('Role')->where(['id' => $id])->delete();
        if ($res) {
            D('RoleAuth')->where(['role_id' => $id])->delete();
            M()->commit();
            json(1);
        } else {
            M()->rollback();
            json(0, '删除失败');
        }
    }
}
