<?php
namespace Admin\Controller\Index;
use Admin\Controller\BaseController;

class IndexController extends BaseController {
    public function index(){
        $this->assign('aaa','111');
        $this->display();
    }
}