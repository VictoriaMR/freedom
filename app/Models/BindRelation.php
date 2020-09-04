<?php

namespace App\Models;
use App\Models\Base as BaseModel;

class BindRelation extends BaseModel
{
    //表名
    public $table = 'bind_relation';
    //主键
    protected $primaryKey = 'rela_id';

    public function addNotExist($openid, $memberId, $type)
    {
    	if (empty($openid) || empty($memberId)) return false;
    	if ($this->isExist($openid, $memberId, $type)) return true;
    	return $this->insert([
    		'openid'=>$openid, 
    		'mem_id'=>$memberId, 
    		'type'=>$type, 
    		'create_at'=>$this->getTime(),
    	]);
    }

    public function isExist($openid, $memberId, $type)
    {
    	if (empty($openid) || empty($memberId)) return false;

    	return $this->where('openid', $openid)
    				->where('type', $type)
    				->where('mem_id', $memberId)
    				->count() > 0;
    }

    public function getIdByOpenid($openid, $type = 0)
    {
        return $this->where('openid', $openid)
                    ->where('type', $type)
                    ->value('mem_id');
    }
}