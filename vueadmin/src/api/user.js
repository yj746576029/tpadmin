import request from '@/utils/request'

// 登录
export function login(data) {
  return request({
    url: '/login/index',
    method: 'post',
    data
  })
}

// 退出
export function logout() {
  return request({
    url: '/login/logout',
    method: 'post'
  })
}

// 我的信息
export function getInfo() {
  return request({
    url: '/profile/index',
    method: 'get'
  })
}

// 修改我的密码
export function editInfo(data) {
  return request({
    url: '/profile/edit',
    method: 'post',
    data
  })
}

// 用户列表
export function list(data) {
  return request({
    url: '/user/index',
    method: 'get',
    params: data
  })
}

// 用户新增
export function add(data) {
  return request({
    url: '/user/add',
    method: 'post',
    data
  })
}

// 用户详情
export function detail(id) {
  return request({
    url: '/user/edit',
    method: 'get',
    params: { id }
  })
}

// 用户新增
export function edit(data) {
  return request({
    url: '/user/edit',
    method: 'post',
    data
  })
}

// 用户删除
export function del(data) {
  return request({
    url: '/user/del',
    method: 'post',
    data
  })
}
