<?php
namespace Admin\Controller;
use Think\Controller;
class EmptyController extends Controller{
    public function index(){
        if(C('CONTROLLER_LEVEL')>1){
            if(strpos(CONTROLLER_NAME,'/') === false){
                $this->redirect('admin/'.CONTROLLER_NAME.'/index');
            }
        }
        //处理空控制器
        $this->error('非法操作');
    }
}