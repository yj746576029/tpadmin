<?php

namespace Api\Controller;

use Api\Controller\BaseController;

class UserController extends BaseController
{
    public function index()
    {
        $page = empty(I('get.page')) ? 1 : I('get.page');
        $pageSize = 10;
        $condition['is_super'] = 0;
        if (!empty(I('get.start_time'))) {
            $s = strtotime(I('get.start_time'));
            $condition['create_time'] = array('egt', $s);
        }
        if (!empty(I('get.end_time'))) {
            $e = strtotime(I('get.end_time'));
            $condition['end_time'] = array('elt', $e);
        }
        if (!empty(I('get.keywords'))) {
            $condition['username'] = array('like', '%' . I('get.keywords') . '%');
        }
        $total = M('User')->where($condition)->count();
        $list = D('User')->field('id,username,mobile,email,create_time')->relation(true)->where($condition)->page($page . ',' . $pageSize)->select();
        foreach ($list as &$v) {
            $v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
        }
        unset($v);
        $roleList = M('Role')->field('id,role_name')->where(['status' => 1])->select();
        json(1, ['list' => $list, 'total' => $total * 1, 'page' => $page, 'page_size' => $pageSize, 'role_list' => $roleList]);
    }

    public function add()
    {
        if (IS_POST) {
            $data['username'] = I('post.username');
            $data['salt'] = substr(md5(uniqid(true)), 0, 4);
            if (!empty(I('post.password'))) {
                $data['password'] = md5(md5(I('post.password')) . $data['salt']);
            } else {
                $data['password'] = md5(md5('123456') . $data['salt']);
            }
            $data['mobile'] = I('post.mobile');
            $data['email'] = I('post.email');
            $data['create_time'] = time();
            $data['update_time'] = $data['create_time'];
            $data['is_super'] = 0;
            M()->startTrans();
            $re = D('User')->add($data);
            if ($re) {
                $role_ids = I('post.roleids');
                $dataList = [];
                foreach ($role_ids as $v) {
                    $item = array('user_id' => $re, 'role_id' => $v);
                    array_push($dataList, $item);
                }
                $res = D('UserRole')->addAll($dataList);
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
            $data['username'] = I('post.username');
            if (!empty(I('post.password'))) {
                $data['salt'] = substr(md5(uniqid(true)), 0, 4);
                $data['password'] = md5(md5(I('post.password')) . $data['salt']);
            }
            $data['mobile'] = I('post.mobile');
            $data['email'] = I('post.email');
            $data['update_time'] = time();
            $data['is_super'] = 0;
            $re = M('User')->where(['id' => $id])->save($data);
            if ($re) {
                D('UserRole')->where(['user_id' => $id])->delete();
                $role_ids = I('post.roleids');
                $dataList = [];
                foreach ($role_ids as $v) {
                    $item = array('user_id' => $id, 'role_id' => $v);
                    array_push($dataList, $item);
                }
                $res = D('UserRole')->addAll($dataList);
                if ($res) {
                    M()->commit();
                    json(1, '编辑成功');
                } else {
                    M()->rollback();
                    json(0, '编辑失败');
                }
            } else {
                M()->rollback();
                json(0, '编辑失败');
            }
        } else {
            $id = I('get.id');
            $user = D('User')->relation(true)->where(['id' => $id])->find();
            $role_ids = [];
            foreach ($user['role'] as $v) {
                array_push($role_ids, $v['id']);
            }
            $user['role'] = $role_ids;
            json(1, ['detail' => $user]);
        }
    }

    public function del()
    {
        if (IS_POST) {
            $id = I('post.id');
            M()->startTrans();
            $res = M('User')->where(['id' => $id])->delete();
            if ($res) {
                D('UserRole')->where(['user_id' => $id])->delete();
                M()->commit();
                json(1);
            } else {
                M()->rollback();
                json(0,'删除失败');
            }
        }
    }
}
