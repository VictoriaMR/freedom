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
}
