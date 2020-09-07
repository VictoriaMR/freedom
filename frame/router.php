<?php

namespace frame;

class Router
{
	public static $_route = []; //路由
	public static $_param = []; //参数

	public static function analyze()
	{
		$pathInfo = trim($_SERVER['REQUEST_URI'] ?? '');
		$pathInfo = explode('?', $pathInfo)[0] ?? '';
		// 对Url网址进行拆分
		$pathInfoArr = explode( '/', $pathInfo);
		$pathInfoArr = array_map('trim', $pathInfoArr);
        // 类名
        $funcArr = [];
        $funcArr['class'] = array_shift($pathInfoArr);
		if (count($pathInfoArr) > 1) {
	        // 方法名
	        $funcArr['func'] = array_pop($pathInfoArr);
	        // 中间路径
	       	$funcArr['path'] = $pathInfoArr;
		} else {
			// 方法名
	        $funcArr['path'] = array_pop($pathInfoArr);
	        // 中间路径
	        $funcArr['func'] = $pathInfoArr;
		}
		$defaultClass = '';
		if(defined('APP_PATH'))
			$defaultClass = APP_PATH;

        $funcArr = [
			'class' => $funcArr['class'] ? $funcArr['class'] : $defaultClass,
			'path' => $funcArr['path'] ? $funcArr['path'] : 'Index',
			'func' => $funcArr['func'] ? $funcArr['func'] : 'index',
		];
		self::$_route = self::realFunc($funcArr);
		return true;
	}

	public static function realFunc($funcArr)
	{
		if (!empty($funcArr)) {
			$count = count($funcArr);
			$i = 1;
			foreach ($funcArr as $key => $value) {
				if (empty($value)) continue;
				if ($i == $count) {
					//方法名小写
					$funcArr[$key] = strtolower(substr($value, 0, 1)) . substr($value, 1);
				} else {
					if (is_array($value)) {
						foreach ($value as $k => $v) {
							$value[$k] = strtoupper(substr($v, 0, 1)) . substr($v, 1);
						}
						$funcArr[$key] = implode('\\', $value);
					} else {
						$funcArr[$key] = strtoupper(substr($value, 0, 1)) . substr($value, 1);
					}
					$i ++;
				}
			}
		}
		return $funcArr;
	}
}