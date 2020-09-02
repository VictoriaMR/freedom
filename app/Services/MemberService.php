<?php 

namespace App\Services;
use App\Services\Base as BaseService;
use App\Models\Member;

class MemberService extends BaseService
{	
    const INFO_CACHE_KEY = 'MEMBER_INFO_CACHE_';
    const INFO_CACHE_EXPIRETIME = 60*60*24;

	public function __construct(Member $model)
    {
        $this->baseModel = $model;
    }

    public function addMember($data)
    {
        return $this->baseModel->addMember($data);
    }

    public function updateUserAvatar($memberId, $url)
    {
        if (empty($memberId) || empty($url)) return false;

        $fileService = make('App/Services/FileService');
        $attach = $fileService->uploadByUrl($url, 'avatar');
        if (empty($attach)) return false;
        //加入关联
        $attachmentService = make('App\Services\AttachmentService');
        $attachmentService->updateData($memberId, $attach['attach_id'], $attachmentService::constant('TYPE_MEMBER_AVATAR'));
        // 更新用户信息
        $res = $this->updateDataById($memberId, ['avatar'=>$attach['path'].DS.$attach['name'].'.'.$attach['type']]);
        if ($res) {
            $this->deleteCache($memberId);
        }
        return $res;
    }

    public function getInfoCache($memberId)
    {
        if (empty($memberId)) return [];
        self::INFO_CACHE_KEY.$memberId;
        $info = redis()->get($cacheKey);
        if (empty($info)) {
            $info = $this->getInfo();
            redis()->set($cacheKey, $info, self::INFO_CACHE_EXPIRETIME);
        }
        return $info;
    }

    public function getInfo($memberId)
    {
        if (empty($memberId)) return [];
        $info = $this->loadData($memberId);
        $info['avatar'] = !empty($info['avatar']) ? url('upload'.DS.$info['avatar']) : $this->getDefaultAvatar($memberId);
        return $info;
    }

    public function getDefaultAvatar($memberId)
    {
        return '';
    }

    public function deleteCache($memberId)
    {
        $cacheKey = self::INFO_CACHE_KEY.$memberId;
        return redis()->del($cacheKey);
    }

    public function isExist($memberId)
    {
        return $this->baseModel->isExist($memberId);
    }

    public function login($memberId, $type=0)
    {
        if (empty($memberId)) return false;
        if (!$this->isExist($memberId)) return false;

        return $this->generateToken($memberId, $type);
    }

    protected function generateToken($memberId, $type)
    {
        return ['access_token'=> '112233', 'refrash_token' => '445566'];
    }

    public function checkToken($access_token, $refrash_token)
    {
        return ['access_token'=> '112233', 'refrash_token' => '445566'];
    }
}
