<?php

namespace frame;

class Hook 
{
    private static $hook_list = [];
    private static $hooked = false;

    public static function async($callback, $params) 
    {
        self::$hook_list[] = ['callback' => $callback, 'params' => $params];
        if(self::$hooked == false) {
            self::$hooked = true;
            register_shutdown_function([__CLASS__, '__run']);
        }
    }

    public static function __run() 
    {
        if (function_exists('fastcgi_finish_request'))
            fastcgi_finish_request();
        if(empty(self::$hook_list)) return false;
        foreach(self::$hook_list as $hook) {
            $callback = $hook['callback'];
            $params = $hook['params'];
            call_user_func_array($callback, $params);
        }
    }
}