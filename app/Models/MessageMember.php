<?php

namespace App\Models;

use App\Models\Base as BaseModel;

/**
 * 消息组成员
 */
class MessageMember extends BaseModel
{
	//表名
    protected $table = 'message_member';

    //主键
    protected $primaryKey = 'item_id';

    public function isExistMember($key, $memId)
    {
    	$memId = (int) $memId;
    	if (empty($key) || empty($memId)) return false;
    	return $this->where('group_key', $key)->where('mem_id', $memId)->count() > 0;
    }
}