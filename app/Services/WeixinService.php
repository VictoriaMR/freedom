<?php 

namespace App\Services;
use App\Services\Base as BaseService;
use frame\Http;

class WeixinService extends BaseService
{   
    private $_appid;
    private $_secret;

    public function __construct()
    {
        $secretService = make('App/Services/SecretService');
        $info = $secretService->getOne();
        $this->_appid = $info['appid'] ?? null;
        $this->_secret = $info['secret'] ?? null;
    }

    public function getUserInfoByCode($code)
    {
        if (empty($this->_appid) || empty($this->_secret)) return false;
        if (empty($code)) return false;
        $param = [
            'appid' => $this->_appid,
            'secret' => $this->_secret,
            'code' => $code,
            'grant_type'=> 'authorization_code',
        ];
        $result = Http::get('https://api.weixin.qq.com/sns/oauth2/access_token', $param);
        $result = isJson($result);
        if (empty($result['openid']) || !empty($result['errcode']) || !empty($result['errmsg'])) {
            return false;
        }

        return $this->getUserInfoByOpenid($result['openid']);
    }

    public function getUserInfoByOpenid($openid)
    {
        if (empty($openid)) return false;
        $token = $this->getToken();
        if (!$token) return false;

        $param = [
            'access_token' => $token,
            'openid' => $openid,
            'grant_type' => 'client_credential',
        ];
        $result = Http::get('https://api.weixin.qq.com/cgi-bin/user/info', $param);
        return isJson($result);
    }

    protected function getToken()
    {
        if (empty($this->_appid) || empty($this->_secret)) return false;
        $param = [
            'appid' => $this->_appid,
            'secret' => $this->_secret,
            'grant_type' => 'client_credential',
        ];
        $result = Http::get('https://api.weixin.qq.com/cgi-bin/token', $param);
        $result = isJson($result);
        return $result['access_token'] ?? false;
    }

    public function addNotExist($info, $type = 0)
    {
        if (empty($info['openid'])) return false;
        $bindService = make('App/Services/BindRelationService');
        $memberId = $bindService->getIdByOpenid($info['openid'], $type);
        if (!empty($memberId)) return $memberId;
        $fields = ['openid', 'nickname', 'sex', 'language', 'city', 'province', 'country', 'avatar'];
        $data = [];
        foreach ($fields as $value) {
            $data[$value] = $info[$value] ?? '';
        }
        $memberService = make('App/Services/MemberService');
        $memberId = $memberService->addMember($data);
        \frame\Hook::async([$memberService, 'updateUserAvatar'], ['memberId' => $memberId, 'url'=>$info['headimgurl'] ?? '']);
        return $memberId;
    }
}