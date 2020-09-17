<?php
function dd($data = '') 
{
	print_r($data);
    exit();
}
function vv($data = '') 
{
    var_dump($data);
    exit();
}
function make($name)
{
	return \App::make($name);
}
function isAjax()
{
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
}
function isMobile()
{
    if (!empty($_SESSION['site_type'])) return $_SESSION['site_type'];
    if (isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], 'wap'))
        return true;
    if (isset($_SERVER['HTTP_ACCEPT']) && strpos(strtoupper($_SERVER['HTTP_ACCEPT']), 'VND.WAP.WML')) 
        return true;
    if (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE']))
        return true;
    if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])) {
        return true;
    }
    return false;
}
function config($name = '') 
{
    if (empty($name)) return $GLOBALS;
    return $GLOBALS[$name] ?? [];
}
function env($name = '', $replace = '')
{
    if (empty($name)) return config('ENV');
    return config('ENV')[$name] ?? $replace;
}
function view($template = '')
{
	return \frame\View::getInstance()->display($template);
}
function fetch($template = '')
{
	return \frame\View::getInstance()->fetch($template);
}
function assign($name, $value = null)
{
	return \frame\View::getInstance()->assign($name, $value);
}
function isJson($string) 
{ 
    if (is_array($string)) return false;
    $string = json_decode($string, true); 
    return json_last_error() == JSON_ERROR_NONE ? $string : false;
}
function redis($db = 0) 
{
    return \frame\Redis::getInstance($db);
}
function ipost($name = '', $default = null) 
{
    if (empty($name)) return $_POST;
    if (isset($_POST[$name]))
        return  $_POST[$name];
    return $default;
}
function iget($name = '', $default = null) 
{
    if (empty($name)) return $_GET;
    if (isset($_GET[$name]))
        return  $_GET[$name];
    return $default;
}
function input($name = '', $default = null)
{
    $temp = array_merge($_GET, $_POST);
    if (empty($name)) return $temp;
    return $temp[$name] ?? $default;
}
function ifile($name = '', $default = null) 
{
    if (empty($name)) return $_FILES;
    return $_FILES[$name] ?? $default;
}
function url($url = '', $param = [])
{
    if (strpos($url, 'http') === false)
        $url = APP_DOMAIN.$url;
    
    if (!empty($param)) {
        $url .= '?'. http_build_query($param);
    }
    return $url;
}
function redirect($url)
{
    header('Location:'.$url);
    exit();
}
function is_cli()
{
    return preg_match('/cli/i', php_sapi_name()) ? true : false;
}
function load($template)
{
    return \frame\View::load($template);
    $template = (APP_PATH ? APP_PATH.'.' : '').(APP_TEMPLATE_TYPE ? (isMobile() ? 'mobile.' : 'computer.') : '').$template;
    $template = 'view/'.strtr($template, '.', '/');
    return ROOT_PATH.$template.'.php';
}
function paginator()
{
    return \frame\Paginator::getInstance();
}