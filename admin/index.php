<?php
// 入口文件
define('APP_MEMORY_START', memory_get_usage());
define('APP_TIME_START', microtime(true));
define('DS', '/');
define('ROOT_PATH', strtr(realpath(dirname(__FILE__).'/../'), '\\', DS).DS);
define('APP_PATH', 'Admin');
require_once ROOT_PATH.'frame/start.php';