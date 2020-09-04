<?php

namespace App\Services;
use App\Services\Base as BaseService;
use App\Models\Message;
use App\Models\MessageGroup;
use App\Models\MessageMember;

class MessageService extends BaseService
{
    public function __construct(Message $model, MessageGroup $group, MessageMember $member)
    {
        $this->baseModel = $model;
        $this->groupModel = $group;
        $this->memberModel = $member;
    }

    public function sendMessage($from, $to, $content, $type=0)
    {
    	$from = (int) $from;
    	if (empty($from) || empty($to) || empty($content)) return false;
    	$groupKey = $this->createGroup($from, $type, $to);
    	if ($groupKey === false) return false;

    	return $this->sendMessageByKey($groupKey, $content, $from);
    }

    public function sendMessageByKey($groupKey, $content, $memId)
    {
    	if (empty($groupKey) || empty($content) || empty($memId)) return false;

        //群组不存在
    	if (!$this->isExistGroup($groupKey)) return false;
    	//组内成员不存在则失败
    	if (!$this->isExistMember($groupKey, $memId)) return false;

    	//消息数据
    	$insert = [
    		'group_key' => $groupKey,
    		'mem_id' => $memId,
    		'content' => substr(trim($content), 0, 250),
    		'create_at' => $this->getTime(),
    	];
    	$result = $this->baseModel->insert($insert);
    	return $result;
    }

    public function joinInGroup($groupKey, $memId)
    {
    	$memId = (int) $memId;
    	if (empty($groupKey) || empty($memId)) return false;
    	//用户组是否存在
    	if (!$this->isExistGroup($groupKey)) return false;

    	//组用户是否存在
    	if ($this->isExistGroup($groupKey, $memId)) return true;
    	$insert = [
			'group_key' => $key,
			'mem_id' => $memId,
			'create_at' => time(),
		];
		return $this->memberModel->insert($insert);
    }

    public function createGroup($memId, $type = 1, $toUser = 0)
    {
		return $this->groupModel->createGroup($memId, $type, $toUser);
    }

    public function isExistGroup($key)
    {
    	return $this->groupModel->isExistGroup($key);
    }

    public function isExistMember($key, $memId)
    {
    	return $this->memberModel->isExistMember($key, $memId);
    }

    public function getListByKey($key, $size, $nowId, $type, $memId = 0)
    {
        $list = $this->baseModel->getListByKey($key, $size, $nowId, $type);
        if (!empty($list)) {
            $memberService = make('App/Services/MemberService');
            $time = $list[0]['create_at'];
            foreach ($list as $key => $value) {
                $info = $memberService->getInfoCache($value['mem_id']);
                $value['nickname'] = $info['nickname'] ?? '';
                $value['avatar'] = $info['avatar'] ?? '';
                $value['sex'] = $info['sex'] ?? '';

                if ($key > 0) {
                    if (strtotime($value['create_at']) - strtotime($time) > 60*10 ) {
                        $value['tips'] = $this->formatTime($value['create_at']);
                        $time = $value['create_at'];
                    }
                }

                $value['is_self'] = $value['mem_id'] == $memId ? 1 : 0;
                $value['is_special'] = $memberService->specialMember($value['mem_id']);

                $list[$key] = $value;
            }
        }
        return $list;
    }

    protected function formatTime($time)
    {
        if (empty($time)) return false;
        //昨天
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $time = strtotime($time);

        if (strtotime($yesterday.' 23:59:59') < $time)
            return date('H:i', $time);

        if ($time > strtotime($yesterday.' 00:00:00'))
            return '昨天 '.date('H:i', $time);

        return date('Y-m-d H:i', $time);
    }
}