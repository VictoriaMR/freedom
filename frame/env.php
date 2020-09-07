<?php
/**
 * 配置env参数到 getenv()
 */
if (is_file(ROOT_PATH . '.env')) {
    $GLOBALS['ENV'] = parse_ini_file(ROOT_PATH . '.env', true);
    if (defined('APP_DOMAIN'))
    	$GLOBALS['ENV']['APP_DOMAIN'] = APP_DOMAIN;
}