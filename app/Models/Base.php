<?php

namespace App\Models;

use frame\Query;

/**
 * 封装一些常用的ORM方法，所有Model以此为基类
 */
class Base extends Query
{
    public function __construct()
    {
        $this->_table = $this->table ?? null;
    }

    public function loadData($id)
    {
        if (empty($id)) return [];
        return $this->where($this->primaryKey, (int) $id)
                    ->find();
    }

    public function updateDataById($id, $data)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function deleteById($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

    public function getTime()
    {
        return date('Y-m-d H:i:s', time());
    }

    public function getPaginationList($total, $list, $page = 1, $pagesize = 10)
    {
        return [
            'total' => $total,
            'pagesize' => $pagesize,
            'page' => $page,
            'page_total' => ceil($total / $pagesize),
            'list' => $list,
        ];
    }
}