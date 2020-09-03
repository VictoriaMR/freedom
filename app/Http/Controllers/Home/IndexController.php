<?php

namespace App\Http\Controllers\Home;
use App\Http\Controllers\Controller;
use frame\Html;

class IndexController extends Controller
{
	public function index()
	{
		Html::addCss('index');
		Html::addJs('index');
		return view();
	}

	public function checktoken()
	{
		$headers = getallheaders();
		$access_token = $headers['Access-Token'] ?? null;
		$refrash_token = $headers['Refrash-Token'] ?? null;
		$memberService = make('App/Services/MemberService');
		$res = $memberService->getToken($access_token);
		if ($res)
			$this->result(301, array_merge(['url'=>url('chat')]), ['message' => 'keep login']);
		//刷新token
		$res = $memberService->refreshToken($refrash_token);
		if (!$res)
			$this->result(201, false, ['message' => 'login is expired']);

		$this->result(200, array_merge(['url'=>url('chat')], $res), ['message' => 'keep login']);
	}

	public function loginByCode()
	{
		$code = ipost('code');
		if (empty($code))
			$this->result(10000, false, ['message' => 'param error']);

		$weixinService = make('App/Services/WeixinService');
		$info = $weixinService->getUserInfoByCode($code);
		if (empty($info))
			$this->result(10000, false, ['message' => 'http error']);

		$memberId = $weixinService->addNotExist($info);
		if(empty($memberId))
			$this->result(10000, false, ['message' => 'create error']);

		$memberService = make('App/Services/MemberService');
		$res = $memberService->login($memberId);
		if (!$res) 
			$this->result(10000, false, ['message' => 'login error']);

		$this->result(200, array_merge(['url'=>url('chat')], $res), ['message' => 'login success']);
	}
}