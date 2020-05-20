<?php
namespace Api\Controller;
use Think\Controller;
class EmptyController extends Controller{
    public function index(){
        //处理空控制器
        json(0,'非法请求');
    }
}