<?php

namespace App\Http\Controllers\Home;
use App\Http\Controllers\Controller;
use frame\Html;

class ChatController extends Controller
{
	public function index()
	{
		Html::addJs('index');
		Html::addCss('index');
		return view();
	}

	public function create()
	{
		$key = ipost('key');
		if (empty($key)) 
			$this->result(10000, false, ['message'=> 'param error']);
		$memId = config('login_member_id');
		$messageService = make('App/Services/MessageService');
		$res = $messageService->joinInGroup($key, $memId);
		if (!$res)
			$this->result(10000, false, ['message'=> 'connect error']);

		$data = $messageService->getListByKey($key, 20, 0, 'down', $memId);

		$this->result(200, $data);
	}
}