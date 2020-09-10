<?php 

namespace App\Services\Admin;
use App\Services\MemberService as BaseService;
use App\Models\Admin\Member;

class MemberService extends BaseService
{	
    public function __construct(Member $model)
    {
        $this->baseModel = $model;
    }

    public function loginByPassword($phone, $password)
    {
        if (empty($phone) || empty($password)) return false;
        $info = $this->getInfoByPhone($phone);
        if (empty($info)) return false;
        $res = $this->checkPassword($this->getPasswd($password, $info['code']), $info['password']);
        if (!$res) return false;
        return $this->login($info['mem_id'], 5);
    }
}
