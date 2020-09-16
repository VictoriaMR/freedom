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
		$logincode = \frame\Str::random(6);
		// dd();
		// $i = redis()->get('login');
		// echo $i.PHP_EOL;
		// $i ++;
		// if ($i == 151) {
		// 	// dd(\frame\Router::$_route);
		// }
		// redis()->set('login', $i);
		// echo $logincode.PHP_EOL;
		// echo $i.PHP_EOL;
		Session::set('admin_login_code', $logincode);
		print_r(Session::get());
		$this->assign('login_code', $logincode);
		return view();
	}

	public function login()
	{
		$phone = ipost('phone');
		$password = ipost('password');
		$code = ipost('verify_code');
		dd(Session::get());
		if (empty($code) || $code != Session::get('admin_login_code'))
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
		Session::set('admin');
		redirect('/');
		session_destroy();
	}
}