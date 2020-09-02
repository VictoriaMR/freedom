<?php

namespace App\Http\Controllers\Home;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
	public function index()
	{
		$code = iget('code');
		//获取用户信息
		if (!empty($code)) {
			$weixinService = make('App/Services/WeixinService');
			$info = $weixinService->getUserInfoByCode($code);
			if (!empty($info)) {			
				$memberId = $weixinService->addNotExist($info);
				if (!empty($memberId)) {
					$memberService = make('App/Services/MemberService');
					$res = $memberService->login($memberId);
					if ($res) {
						$this->result(200, $res);
					} else {
						$message = '登陆失败';
					}
				} else {
					$message = '登陆失败, 新增用户失败';
				}
			} else {
				$message = '登陆失败, 获取信息不成功';
			}
		}
		$this->assign('message', $message ?? '');
		return view();
	}

	public function checktoken()
	{
		$token = iget('token', '');
		$param = [
			'appid' => 'wx2e3ee71ac2d9f0b8',
			'redirect_uri' => env('APP_DOMAIN'),
			'response_type' => 'code',
			'scope' => 'snsapi_base',
			'state' => '123123',
		];
		$this->result(10000, ['url'=>url('https://open.weixin.qq.com/connect/oauth2/authorize', $param).'#wechat_redirect'], ['message' => 'nologin']);
	}
}