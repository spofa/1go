<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace think\controller;


abstract class Rpc
{

    protected $allowMethodList = '';
    protected $debug           = false;

    
    public function __construct()
    {
        //控制器初始化
        if (method_exists($this, '_initialize')) {
            $this->_initialize();
        }

        //导入类库
        \think\Loader::import('vendor.phprpc.phprpc_server');
        //实例化phprpc
        $server = new \PHPRPC_Server();
        if ($this->allowMethodList) {
            $methods = $this->allowMethodList;
        } else {
            $methods = get_class_methods($this);
            $methods = array_diff($methods, array('__construct', '__call', '_initialize'));
        }
        $server->add($methods, $this);

        if (APP_DEBUG || $this->debug) {
            $server->setDebugMode(true);
        }
        $server->setEnableGZIP(true);
        $server->start();
        echo $server->comment();
    }

    
    public function __call($method, $args)
    {}
}