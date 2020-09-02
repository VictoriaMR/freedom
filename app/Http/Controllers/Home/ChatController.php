<?php

namespace App\Http\Controllers\Home;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
	public function index()
	{
		return view();
	}

	public function checktoken()
	{
		$headers = getallheaders();
		$access_token = $headers['Access-Token'] ?? null;
		$refrash_token = $headers['Refrash-Token'] ?? null;
		$memberService = make('App/Services/MemberService');
		$res = $memberService->checkToken($access_token, $refrash_token);
		if ($res) {
			$this->result(200, array_merge(['url'=>url('chat')], $res), ['message' => 'keeplogin']);
		} else {
			$param = [
				'appid' => 'wx2e3ee71ac2d9f0b8',
				'redirect_uri' => env('APP_DOMAIN'),
				'response_type' => 'code',
				'scope' => 'snsapi_base',
				'state' => 'STATE',
			];
			$this->result(301, ['url'=>url('https://open.weixin.qq.com/connect/oauth2/authorize', $param).'#wechat_redirect'], ['message' => 'nologin']);
		}
	}
}