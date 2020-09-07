<?php

namespace App\Models;

use App\Models\Base as BaseModel;

class Member extends BaseModel
{
    //表名
    public $table = 'member';
    //主键
    protected $primaryKey = 'mem_id';

    public function addMember($data, $type = 0)
    {
    	$relation = make('App/Models/BindRelation');
    	$this->begin(); //事务开启
    	$openid = $data['openid'] ?? '';
    	unset($data['openid']);
    	$data['code'] = $this->getCode();
    	$memberId = $this->insertGetId($data);
    	//绑定关系
    	$relation->addNotExist($openid, $memberId, $type);
    	$this->commit(); //事务结束
    	return $memberId;
    }

    public function getCode()
    {
    	$key = '';
        $counter = 0;
        do {
            $key = \frame\Str::random(6);
        } while ($this->isExistCode($key) && ($counter++) < 10);
        return $key;
    }

    public function isExist($memberId)
    {
        return $this->where('mem_id', (int) $memberId)->count() > 0;
    }

    public function isExistCode($code)
    {
        return $this->where('recommend_code', $code)->count() > 0;
    }
}