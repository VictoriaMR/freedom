<?php
// 入口文件
define('APP_MEMORY_START', memory_get_usage());
define('APP_TIME_START', microtime(true));
@session_start();
define('DS', '/');
define('ROOT_PATH', strtr(realpath(dirname(__FILE__).'/../'), '\\', DS).DS);
require_once ROOT_PATH.'frame/helper.php';
require_once ROOT_PATH.'frame/env.php';
ini_set('date.timezone', 'Asia/Shanghai');
define('APP_PATH', 'Home');
define('APP_DOMAIN', env('APP_DOMAIN'));
define('APP_TEMPLATE_TYPE', true);
require_once ROOT_PATH.'frame/start.php';