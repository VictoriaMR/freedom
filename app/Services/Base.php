<?php

namespace App\Services;

class Base
{
    protected $baseModel = null;

    protected static $constantMap = [];

    public function loadData($id, $field=[])
    {
        return $this->baseModel->loadData($id, $field);
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
}