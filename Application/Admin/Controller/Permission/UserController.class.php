<?php
namespace Admin\Controller\Permission;
use Admin\Controller\BaseController;

class UserController extends BaseController {
    public function index(){
        $this->assign('aaa','111');
        $this->display();
    }
}
