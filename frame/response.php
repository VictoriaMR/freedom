<?php
class Response
{
    // header参数
    protected static $header = [
    	'Access-Control-Allow-Origin' => '*',
    	'Content-Type' => 'text/html;charset=utf-8',
		'Cache-Control' => 'no-store,no-cache,must-revalidate',
    ];

    public static function send($data = '', $type = 0, $code = 200, array $header = [], $options = [])
    {
        // 处理输出数据
        if (!headers_sent()) {
            // 发送状态码
            http_response_code($code);
        	if ($type)
        		self::$header['Content-Type'] = 'application/json; charset=utf-8';
        	self::setHeader();
        }
        echo $data;
        if (function_exists('fastcgi_finish_request')) {
            // 提高页面响应
            fastcgi_finish_request();
        }
        exit();
    }

    protected static function setHeader($header = [])
    {
    	$header = array_merge(self::$header, $header);
    	foreach ($header as $name => $val) {
            header($name . ':' . $val);
        }
        header('Date:'.gmdate('D, d M Y H:i:s',time()).' GMT');
        header('Last-Modified:'.gmdate('D, d M Y H:i:s',time()).' GMT');
        return true;
    }
}