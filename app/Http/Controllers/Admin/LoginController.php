<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use frame\Html;

class LoginController extends Controller
{
	public function index()
	{
		Html::addCss('login');
		$service = \App::make('App/Services/SystemService');
		// dd(sysconf(_SC_NPROCESSORS_CONF));
		// dd($service->getInfo());
		// dd($service->getCpuUsage());
		Html::addCss('index');
		
		return view();
	}
}