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
		//解析路由
		\frame\Router::analyze();
		return self::instance();
	}

    public function send() 
    {
        //路由解析
        $info = \frame\Router::$_route;
        $class = 'App\\Http\\Controllers\\'.($info['class'] ? $info['class'].'\\' : '').$info['path'].'Controller';
        //中间组件
        $handle = make('App\Http\Middleware\VerifyToken');
        $handle = $handle->handle();

        //公共样式
        if (!isAjax()) {
            \frame\Html::addJs(['jquery', 'common'], true);
        }

        if (is_callable([self::autoload($class), $info['func']])) {
            call_user_func_array([self::autoload($class), $info['func']], []);
            $this->end();
        } else {
            throw new \Exception(implode('->', [self::autoload($class), $info['func']]) .' was not exist!', 1);
        }
    }

	private static function init() 
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
        if (env('APP_DEBUG')) {
            \frame\Debug::debugInit();
        }
        exit();
    }
}