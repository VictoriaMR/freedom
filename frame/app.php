<?php
class App 
{
	private static $_instance = null;

    public static function instance() 
    {
    	if (!(self::$_instance instanceof self)) {
			self::$_instance = new self();
		}
		return self::$_instance;
    }

	public static function run() 
	{
		//初始化方法
		self::init();
        //注册异常处理
        \frame\Error::register();
        \frame\Router::analyze();
		//解析路由
		return self::instance();
	}

    public function send() 
    {
        //路由解析
        $info = \frame\Router::$_route;
        $class = 'App\\Http\\Controllers\\'.($info['class'] ? $info['class'].'\\' : '').$info['path'].'Controller';
        //中间组件
        $handle = make('App\Http\Middleware\VerifyToken');
        $handle = $handle->handle($info);

        //公共样式
        if (!isAjax()) {
            \frame\Html::addJs(['jquery', 'common'], true);
            \frame\Html::addCss(['common'], true);
            if ($info['class'] == 'Home') {
                \frame\Html::addCss(['iconfont'], true);
            }
        }

        if (is_callable([self::autoload($class), $info['func']])) {
            call_user_func_array([self::autoload($class), $info['func']], []);
            $this->end();
        } else {
            throw new \Exception(implode('->', [self::autoload($class), $info['func']]) .' was not exist!', 1);
        }
    }

	public static function init() 
	{
		spl_autoload_register([__CLASS__ , 'autoload']);
	}

	private static function autoload($abstract) 
    {
        //命名空间反斜杠
        $abstract = strtr($abstract, ['/'=>'\\']);
        //容器加载
        if (!empty(Container::$_building[$abstract]))
            return Container::$_building[$abstract];

        $fileName = $abstract;
        $fileName = strtr($fileName, ['\\'=>'/', 'App\\'=>'app/']);
        if (strpos($fileName, 'frame/') !== false)
            $fileName = strtolower($fileName);

        $file = ROOT_PATH.$fileName.'.php';
        if (is_file($file))
			require_once $file;
		else 
			throw new \Exception($abstract.' was not exist!', 1);

        $concrete = Container::getInstance()->autoload($abstract);
		return $concrete;
    }

    public static function make($abstract)
    {
    	return self::autoload($abstract);
    }

    protected function end()
    {   
        // 应用调试模式
        if (env('APP_DEBUG'))
            \frame\Debug::debugInit();
        exit();
    }

    public static function Log($msg = '')
    {
        $now         = date('Y-m-d H:i:s');
        $destination = ROOT_PATH.'runtime/'.date('Ymd').'/runlog.log';

        $path = dirname($destination);
        !is_dir($path) && mkdir($path, 0755, true);

        // 获取基本信息
        if (isset($_SERVER['HTTP_HOST'])) {
            $current_uri = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        } else {
            $current_uri = "cmd:" . implode(' ', $_SERVER['argv']);
        }

        $runtime    = number_format(microtime(true) - APP_TIME_START, 10,'.','');
        $reqs       = $runtime > 0 ? number_format(1 / $runtime, 2,'.','') : '∞';
        $time_str   = ' [Time：' . number_format($runtime, 6) . 's][QPS：' . $reqs . 'req/s]';
        $memory_use = number_format((memory_get_usage() - APP_MEMORY_START) / 1024, 2,'.','');
        $memory_str = ' [MEM：' . $memory_use . 'kb]';
        $file_load  = ' [Files：' . count(get_included_files()) . ']';
        $file_load  .= implode(PHP_EOL, get_included_files());
        $info   = '[ log ] ' . $current_uri . $time_str . $memory_str . $file_load . "\r\n";
        $server = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '0.0.0.0';
        $remote = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'CLI';
        $message = error_get_last()['message'] ?? '';
        if (empty($message)) $message = preg_replace('/\s(?=\s)/', '\\1', $msg);

        $message = rtrim($message, PHP_EOL);

        return error_log("\r\n[{$now}] {$server} {$remote} {$method} {$current_uri}\r\n{$info}{$message}---------------------------------------------------------------", 3, $destination);
    }
}