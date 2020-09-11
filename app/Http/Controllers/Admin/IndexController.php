<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use frame\Html;

class IndexController extends Controller
{
	public function index()
	{
		Html::addCss('index');
		Html::addJs(['index', 'echarts']);

		return view();
	}

	public function getSystemInfo()
	{
		$systemService = make('App/Services/SystemService');
		$info = $systemService->getInfo();
		$this->result(200, $info);
	}
}