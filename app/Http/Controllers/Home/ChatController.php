<?php

namespace App\Http\Controllers\Home;
use App\Http\Controllers\Controller;
use GatewayClient\Gateway;
use frame\Html;

class ChatController extends Controller
{
	public function __construct()
	{
		Gateway::$registerAddress = '127.0.0.1:1238';
		$this->memId = config('login_member_id');
	}

	public function index()
	{
		// $messageService = make('App/Services/MessageService');
		// $messageService->createGroup(1000000000, 1, 1000000000);
		// vv();
		Html::addJs('index');
		Html::addCss('index');
		return view();
	}

	public function getUserInfo()
	{
		$memberService = make('App/Services/MemberService');
		$info = $memberService->getInfoCache($this->memId);
		$this->result(200, $info);
	}

	public function create()
	{
		$key = ipost('key');
		if (empty($key)) 
			$this->result(10000, false, ['message'=> 'param error']);
		$messageService = make('App/Services/MessageService');
		$res = $messageService->joinInGroup($key, $this->memId);
		if (!$res)
			$this->result(10000, false, ['message'=> 'connect error']);

		$data = $messageService->getListByKey($key, 20, 0, 'down', $this->memId);

		$this->result(200, $data);
	}

	public function bind()
	{
		$client_id = ipost('client_id');
		$key = ipost('key');
		if (empty($client_id) || empty($key))
			$this->result(10000, flase, ['message'=> 'param error']);

		Gateway::bindUid($client_id, $this->memId);
		Gateway::joinGroup($client_id, $key);
		$this->result(200, true, ['message'=> 'connect success']);
	}

	public function send()
	{
		$content = ipost('content');
		$key = ipost('key');

		if (empty($content) || empty($key))
			$this->result(10000, flase, ['message'=> 'param error']);

		$messageService = make('App/Services/MessageService');
		$res = $messageService->sendMessageByKey($key, $content, $this->memId);
		$memberService = make('App/Services/MemberService');
		$data = $memberService->getInfoCache($this->memId);
		$data['content'] = $content;
		$data['type'] = 'message';
		Gateway::sendToGroup($key, json_encode($data));
		$this->result(200, true, ['message'=> 'send success']);
	}
}