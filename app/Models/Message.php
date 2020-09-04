<?php

namespace App\Models;

use App\Models\Base as BaseModel;

class Message extends BaseModel
{
	//表名
    protected $table = 'message';

    //主键
    protected $primaryKey = 'msg_id';

    public function getListByKey($key, $size, $nowId, $type)
    {
    	if (empty($key)) return false;
    	$this->where('group_key', $key);
    	$this->orderBy('msg_id', $type == 'prev' ? 'asc' : 'desc');
    	$nowId = (int) $nowId;
    	if ($nowId > 0)
    		$this->where('msg_id', $type == 'prev' ? '<' : '>', $nowId);
    	$list = $this->offset(0)->limit($size)->select(['msg_id', 'mem_id', 'content', 'create_at'])->get();
    	if (!empty($list) && $type == 'down')
    		$list = array_reverse($list);
    	return $list;
    }
}