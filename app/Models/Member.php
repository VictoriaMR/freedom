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
    	$memberId = $this->insertGetId($data);
    	//绑定关系
    	$relation->addNotExist($openid, $memberId, $type);
    	$this->commit(); //事务结束
    	return $memberId;
    }
}