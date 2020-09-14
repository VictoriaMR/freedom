<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use frame\Html;

class LoginController extends Controller
{
	public function index()
	{
		Html::addCss(['login']);
		Html::addJs(['login']);
		$logincode = \frame\Str::random(6);
		\frame\Session::set('admin_login_code', $logincode);
		$this->assign('login_code', $logincode);
		return view();
	}

	public function login()
	{
		$phone = ipost('phone');
		$password = ipost('password');
		$code = ipost('verify_code');

		if (empty($code) || $code != \frame\Session::get('admin_login_code'))
			$this->result(10000, false, '验证未通过');

		$memberService = make('App/Services/Admin/MemberService');
		$result = $memberService->loginByPassword($phone, $password);
		if ($result)
			$this->result(200, $result, '登录成功');
		else
			$this->result(10000, $result, '账户或者密码不匹配');
	}

	public function logout()
	{
		\frame\Session::set('admin');
		redirect('/');
	}
}