<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use frame\Html;

class ShowController extends Controller
{
	public function index()
	{
		$opt = ipost('opt');
		if ($opt == 'info')
			$this->getSystemInfo();
		elseif($opt == 'reboot')
			$this->rebootSystem();

		Html::addCss(['index']);
		Html::addJs(['index', 'echarts']);

		return view();
	}

	protected function getSystemInfo()
	{
		$systemService = make('App/Services/SystemService');
		$info = $systemService->getInfo();
		$this->result(200, $info);
	}

	protected function rebootSystem()
	{
		if (substr(php_uname(), 0, 7) == 'Windows')
			$cmd = 'shutdown -r -t 0';
		else
			$cmd = ROOT_PATH.'bash/reboot.sh';
		exec($cmd);
		$this->result(200, true, '执行成功');
	}
}