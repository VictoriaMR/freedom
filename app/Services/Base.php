<?php

namespace App\Services;

/**
 * 业务模型基类.
 */
class Base
{
    /**
     * 关联数据Model.
     *
     * var App\Model\Base
     */
    protected $baseModel = null;

    /**
     * 常量映射关系表.
     */
    protected static $constantMap = [];

    /**
     * 通过主键获取资料.
     *
     * @param mix $id 主键值
     *
     * @return array
     */
    public function loadData($id)
    {
        return $this->baseModel->loadData($id);
    }

    /**
     * 新增数据.
     *
     * @param array $data 新增数据
     */
    public function insertGetId($data)
    {
        return $this->baseModel->insertGetId($data);
    }

    /**
     * 通过主键更新数据.
     *
     * @param mix   $id
     * @param array $data
     *
     * @return bool
     */
    public function updateDataById($id, $data)
    {
        return $this->baseModel->updateDataById($id, $data);
    }

    /**
     *  获取Model.
     */
    public function getBaseModel()
    {
        return $this->baseModel;
    }

    /**
     * 获取常量继承方法
     * @author   Mingrong
     * @DateTime 2020-01-10
     * @param    [type]     $const [description]
     * @param    string     $model [description]
     * @return   
     */
    public static function constant($const, $model = 'base')
    {
        $namespace = 'static';

        if (isset(static::$constantMap[$model])) {
            $namespace = static::$constantMap[$model];
        }

        return constant($namespace.'::'.$const);
    }

    /**
     * @method 返回页码总数格式
     * @author Victoria
     * @date   2020-04-13
     * @return array
     */
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
            $str .= $chars[mt_rand(0, $charsLen)];    //随机取出一位
        }
        return $str;
    }
}