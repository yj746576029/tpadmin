<?php
namespace Api\Controller;
use Api\Controller\BaseController;

class IndexController extends BaseController {
    public function index(){
        $mysql= M()->query("select version() as v");
        // 系统信息
        $sys['host']               = $_SERVER['HTTP_HOST'];//服务器地址
        $sys['os']                 = PHP_OS;//操作系统
        $sys['software']           = $_SERVER['SERVER_SOFTWARE'];//运行环境
        $sys['php_version']        = PHP_VERSION;//PHP版本
        $sys['php_sapi_name']      = php_sapi_name();//PHP运行方式
        $sys['mysql_version']      = $mysql[0]['v'];//MYSQL版本
        $sys['upload_max']         = ini_get('upload_max_filesize');//上传附件限制
        $sys['max_execution_time'] = ini_get("max_execution_time")."秒";//执行时间限制
        $sys['free_space']         = round(disk_free_space("/")/(1024*1024),2).'M';
        // 版权信息
        $sys['product_name']   = 'yjadmin管理系统';
        $sys['author']         = '杨杰';
        json(1,$sys);
    }
}