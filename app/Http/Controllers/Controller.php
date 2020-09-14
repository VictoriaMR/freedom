<?php

namespace App\Http\Controllers;

class Controller 
{
    protected $tabs = [];

    protected function result($code, $data=[], $options=[])
    {
       $data = [
            'code' => $code,
            'data' => $data
        ];
        if (!empty($options)) {
            if (!is_array($options)) {
                $options = ['message' => $options];
            } else if (!empty($options[0])) {
                $options['message'] = $options[0];
                unset($options[0]);
            }
        }
        $data = array_merge($data, $options);
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit();
    }

    protected function assign($name = '', $value = '')
    {
        return \frame\View::getInstance()->assign($name, $value);
    }

    protected function _initialize()
    {
        if (isAjax()) return false;

        $info = \frame\Router::$_route;
        $controllerService = \App::make('App\Services\Admin\ControllerService');
        $data = $controllerService->getListByParentName($info['path']);
        if (!empty($data['name'])) 
            $navArr[] = $data['name'];
        if (!empty($data['son'][$info['func']]))
            $navArr[] = $data['son'][$info['func']]['name'];

        $this->assign('navArr', $navArr ?? []);
        $this->assign('path', $info['path'] ?? '');
        $this->assign('func', $info['func'] ?? '');
        $this->assign('tabs', $data['son'] ?? []);
    }
}
