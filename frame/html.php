<?php 

namespace frame;

class Html
{
	public static $_CSS = [];
	public static $_JS = [];

	public static function addCss($name = '', $public = false)
	{
		if (!is_array($name)) $name = [$name];
		foreach ($name as $value) {
			if ($public) {
				self::$_CSS[] = env('APP_DOMAIN') . 'css/'.(APP_TEMPLATE_TYPE ? (isMobile() ? 'mobile/' : 'computer/') : '').$value.'.css';
			} else {
				self::$_CSS[] = env('APP_DOMAIN') . 'css/'.(APP_TEMPLATE_TYPE ? (isMobile() ? 'mobile/' : 'computer/') : '').strtolower(\frame\Router::$_route['path']).'/'.$value . '.css';
			}
		}
	}

	public static function addJs($name = '', $public = false)
	{
		if (empty($name)) return false;
		if (!is_array($name)) $name = [$name];
		foreach ($name as $value) {
			if ($value == 'jquery' || ($public && $value == 'common')) {
				self::$_JS[] = env('APP_DOMAIN') . 'js/'.($public ? '' : strtolower(\frame\Router::$_route['path']).'/') . $value . '.js';
			} else {
				self::$_JS[] = env('APP_DOMAIN') . 'js/'.(APP_TEMPLATE_TYPE ? (isMobile() ? 'mobile/' : 'computer/') : ''). ($public ? '' : strtolower(\frame\Router::$_route['path']).'/') . $value . '.js';
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