<?php

namespace frame;

class View 
{
    private static $_instance = null;
    
    protected static $data = [];

    public static function getInstance() 
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function display($template = '')
    {
        $template = $this->getTemplate($template);
        if (!file_exists($template)) {
            throw new \Exception($template . ' 模板不存在', 1);
        }
        extract(self::$data);
        include($template);
        $this->clear();
        return true;
    }

    private function getTemplate($template) 
    {
        if (!empty($template)) {
            if (strpos($template, '.') !== false)
                $template = 'view/'.strtr($template, '.', '/');
        } else {
            $info = array_filter(\frame\Router::$_route);
            if (APP_TEMPLATE_TYPE) {
                $prev = array_shift($info);
                $template = 'view/'.$prev.'/'.(APP_TEMPLATE_TYPE ? (isMobile() ? 'mobile/' : 'computer/') : '').implode('/', $info);
            } else {
                $template = 'view/'.implode('/', $info);
            }
        }
        return ROOT_PATH.$template.'.php';
    }

    private function clear()
    {
        self::$data = [];
    }

    public function fetch($template = '')
    {
        ob_start();
        $this->display($template);
        $content = ob_get_clean();
        return $content;
    }

    public function assign($name, $value = null)
    {
        if (is_array($name)) {
            self::$data = array_merge(self::$data, $name);
        } else {
            self::$data[$name] = $value;
        }
        return $this;
    }

    public function load($template = '')
    {
        if (empty($template)) return false;
        return $this->display((APP_PATH ? APP_PATH.'.' : '').(APP_TEMPLATE_TYPE ? (isMobile() ? 'mobile.' : 'computer.') : '').$template);
    }
}