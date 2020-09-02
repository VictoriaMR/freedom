<?php 

namespace frame;

class Html
{
	public static $_CSS = [];
	public static $_JS = [];

	public static function addCss($name = '', $public = false)
	{
		if (empty($name)) return false;
		if (is_array($name)) {
			foreach ($name as $value) {
				self::$_CSS[] = env('APP_DOMAIN') . 'css/' . ($public ? '' : \frame\Router::$_route['path'].'/') . $value . '.css';
			}
		}
	}

	public static function addJs($name = '', $public = false)
	{
		if (empty($name)) return false;
		if (is_array($name)) {
			foreach ($name as $value) {
				self::$_JS[] = env('APP_DOMAIN') . 'js/' . ($public ? '' : \frame\Router::$_route['path'].'/') . $value . '.js';
			}
		}
		return true;
	}

	public static function getCss()
	{
		if (empty(self::$_CSS)) return [];
		return array_unique(self::$_CSS);
	}

	public static function getJs()
	{
		if (empty(self::$_JS)) return [];
		return array_unique(self::$_JS);
	}
}