<?php
//框架文件加载
require_once ROOT_PATH.'frame/app.php';
require_once ROOT_PATH.'frame/container.php';
// 执行应用
if (is_cli()) {
	App::init();
    //注册异常处理
    \frame\Error::register();
} else {
	//加载composer配置文件
	if (is_file(ROOT_PATH . 'vendor/autoload.php'))
		require_once ROOT_PATH . 'vendor/autoload.php';
	App::run()->send();
}
