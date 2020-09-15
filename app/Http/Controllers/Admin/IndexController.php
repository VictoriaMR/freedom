<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use frame\Html;

class IndexController extends Controller
{
	public function index()
	{
		Html::addCss(['index']);
		Html::addJs(['index']);

		//用户信息
		$memberService = make('App/Services/Admin/MemberService');
		$info = $memberService->getInfoCache(\frame\Session::get('admin_member_id'));

		//功能列表
		$controllerService = make('App/Services/Admin/ControllerService');
		$list = $controllerService->getPerantList();

		$this->assign('list', $list);
		$this->assign('info', $info);

		return view();
	}
}