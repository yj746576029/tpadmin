<?php
namespace Admin\Controller\Index;
use Admin\Controller\BaseController;

class IndexController extends BaseController {
    public function index(){
        $mysql= M()->query("select version() as v");
        // 系统信息
        $data['host']               = $_SERVER['HTTP_HOST'];//服务器地址
        $data['os']                 = PHP_OS;//操作系统
        $data['software']           = $_SERVER['SERVER_SOFTWARE'];//运行环境
        $data['php_version']        = PHP_VERSION;//PHP版本
        $data['php_sapi_name']      = php_sapi_name();//PHP运行方式
        $data['mysql_version']      = $mysql[0]['v'];//MYSQL版本
        $data['upload_max']         = ini_get('upload_max_filesize');//上传附件限制
        $data['max_execution_time'] = ini_get("max_execution_time")."秒";//执行时间限制
        $data['free_space']         = round(disk_free_space("/")/(1024*1024),2).'M';
        // 版权信息
        $data['product_name']   = 'yjadmin管理系统';
        $data['author']         = '杨杰';
        $this->assign(['system'=>$data]);
        $this->display();
    }
}