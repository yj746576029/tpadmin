import request from '@/utils/request'

// 列表
export function list(data) {
  return request({
    url: '/role/index',
    method: 'get',
    params: data
  })
}

// 新增
export function add(data) {
  return request({
    url: '/role/add',
    method: 'post',
    data
  })
}

// 详情
export function detail(id) {
  return request({
    url: '/role/edit',
    method: 'get',
    params: { id }
  })
}

// 新增
export function edit(data) {
  return request({
    url: '/role/edit',
    method: 'post',
    data
  })
}

// 删除
export function del(data) {
  return request({
    url: '/role/del',
    method: 'post',
    data
  })
}
