<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use frame\Html;
use frame\Session;

class LoginController extends Controller
{
	public function index()
	{
		Html::addCss(['login']);
		Html::addJs(['login']);
		return view();
	}

	public function login()
	{
		$phone = ipost('phone');
		$password = ipost('password');
		if (empty($phone))
			$this->result(10000, $result, '账户不能为空');
		if (empty($password))
			$this->result(10000, $result, '密码不能为空');

		$memberService = make('App/Services/Admin/MemberService');
		$result = $memberService->loginByPassword($phone, $password);
		if ($result)
			$this->result(200, $result, '登录成功');
		else
			$this->result(10000, $result, '账户或者密码不匹配');
	}

	public function logout()
	{
		Session::set('admin');
		redirect('/');
	}
}