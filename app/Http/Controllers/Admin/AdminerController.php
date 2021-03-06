<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use frame\Html;

class AdminerController extends Controller
{
	public function __construct()
	{
		parent::_initialize();
	}

	public function index()
	{
		$page = iget('page', 1);
		$size = iget('size', 20);
		$keyword = iget('keyword', '');
		$status = iget('status', '');
		$where = [];
		if (!empty($keyword))
			$where['name, nickname, mobile'] = ['like', '%'.$keyword.'%'];

		$memberService = make('App/Services/Admin/MemberService');
		$constrollService = make('App/Services/Admin/ControllerService');

		$total = $memberService->getTotal($where);
		if ($total > 0) {
			$list = $memberService->getList($where, $page, $size);
			if (!empty($list)) {
				foreach ($list as $key => $value) {
					if (!empty($value['rule'])) {
						$temp = $constrollService->getListByIds($value['rule']);
						$value['rule_text'] = implode(',', array_column($temp, 'name'));
					} else {
						$value['rule_text'] = '';
					}
					$list[$key] = $value;
				}
			}
		}

		$paginator = paginator()->make($size, $total, $page);

		$this->assign('list', $list ?? []);
		$this->assign('paginator', $paginator);
		$this->assign('keyword', $keyword);
		$this->assign('status', $status);

		return view();
	}
}