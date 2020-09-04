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
        $url = Env('APP_DOMAIN').$url;
    
    if (!empty($param)) {
        $url .= '?'. http_build_query($param);
    }
    return $url;
}
function redirect($url)
{
    if (isAjax()) {
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode(['code'=>201, 'data'=>false, 'message'=>'login is expired'], JSON_UNESCAPED_UNICODE);
    } else {
        header('Location:'.$url);
    }
    exit();
}