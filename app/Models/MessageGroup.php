<?php

namespace App\Models;

use App\Models\Base as BaseModel;

/**
 * 消息聊天组
 */
class MessageGroup extends BaseModel
{
	//表名
    protected $table = 'message_group';

    public function isExistGroup($key)
    {
    	if (empty($key)) return false;
    	return $this->where('group_key', $key)->count() > 0;
    }

    public function createGroup($memId, $type = 1, $toUser = 0)
    {
    	$groupKey = $this->getGroupKey();
        $this->begin();
    	$insert = [
			'group_key' => $groupKey,
			'mem_id' => $memId,
			'type' => (int) $type,
			'create_at' => $this->getTime(),
		];
		$result = $this->insert($insert);
		//群组加人员
		$insert = [
			'group_key' => $groupKey,
			'mem_id' => $memId,
			'create_at' => $this->getTime(),
		];
		if (!empty($toUser) && $memId != $toUser) {
			$insert = [$insert];
			$insert[] = [
				'group_key' => $groupKey,
				'mem_id' => $toUser,
				'create_at' => $this->getTime(),
			];
		}
		$memberModel = make('App/Models/MessageMember');
		$memberModel->insert($insert);
        $this->commit();
		return $groupKey;
    }

    protected function getGroupKey()
    {
        $key = '';
        $counter = 0;
        do {
            $key = \frame\Str::random(32);
        } while ($this->isExistGroup($key) && ($counter++) < 10);
        return $key;
    }
}