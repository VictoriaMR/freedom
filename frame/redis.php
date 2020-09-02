<?php

namespace frame;

class Redis
{
	private static $_instance = null;
    private static $_link = null;
    private static $_db = 0;
    const DEFAULT_EXT_TIME = 60;
    const DEFAULT_CONNECT_TIME = 5;

    public static function getInstance($db = 0) 
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();
            try {
                self::$_link = new \Redis();
                self::connect();
            } catch (\Exception $e) {
                throw new \Exception('Redis connect error', 1);
            }
        }
        if (!is_null(self::$_link)) {
        	// 重新链接
            if (!self::$_link->ping()) {
                self::connect();
            }
            //选择数据库
            if (self::$_db != $db) {
	            self::$_db = $db;
	            self::$_link->select($db);
            }
        }
        return self::$_instance;
    }

    private static function connect() 
    {
        if (is_null(self::$_link)) return false;
        self::$_link->connect(env('REDIS_HOST', '127.0.0.1'), env('REDIS_PORT', '6379'), self::DEFAULT_CONNECT_TIME);
        if (!empty(env('REDIS_PASSWORD'))) {
            self::$_link->auth(env('REDIS_PASSWORD'));
        }
        return true;
    }

    public function __call($func, $arg)
    {
        if (is_null(self::$_link)) return false;
        if (!in_array($func, ['hmset'])) {
            foreach ($arg as $key => $value) {
                if (is_array($value)) 
                	$arg[$key] = json_encode($value, JSON_UNESCAPED_UNICODE);
            }   
        }
        $info = self::$_link->$func(...$arg);
        $temp = isJson($info);
        if ($temp) return $temp;
        return $info;
    }
}