<?php

namespace App\Services;

class Base
{
    protected $baseModel = null;

    protected static $constantMap = [];

    public function loadData($id)
    {
        return $this->baseModel->loadData($id);
    }

    public function insertGetId($data)
    {
        return $this->baseModel->insertGetId($data);
    }

    public function updateDataById($id, $data)
    {
        return $this->baseModel->updateDataById($id, $data);
    }

    public function getBaseModel()
    {
        return $this->baseModel;
    }

    public static function constant($const, $model = 'base')
    {
        $namespace = 'static';
        if (isset(static::$constantMap[$model])) {
            $namespace = static::$constantMap[$model];
        }
        return constant($namespace.'::'.$const);
    }

    public function getPaginationList($total, $list, $page, $pagesize)
    {
        return $this->baseModel->getPaginationList($total, $list, $page, $pagesize);
    }

    public function getName()
    {
        return str_replace(['-', ':', ' '], '', date('Y-m-d H:i:s', time())).strtolower($this->getSalt(8));
    }

    public function getTime()
    {
        return $this->baseModel->getTime();
    }

    public function getSalt($len = 4)
    {
        $chars = [
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k',
            'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
            'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G',
            'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
            'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2',
            '3', '4', '5', '6', '7', '8', '9'
        ];
        $charsLen = count($chars) - 1;
        shuffle($chars);
        $str = '';
        for($i=0; $i<$len; $i++){
            $str .= $chars[mt_rand(0, $charsLen)];
        }
        return $str;
    }
}