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
		$where = [];
		if (!empty($keyword))
			$where['name, nickname, mobile'] = ['like', '%'.$keyword.'%'];

		$memberService = make('App/Services/Admin/MemberService');
		$total = $memberService->getTotal($where);
		if ($total > 0) {
			$list = $memberService->getList($where, $page, $size);
		}

		$paginator = paginator()->make($size, $total, $page);

		$this->assign('list', $list ?? []);
		$this->assign('paginator', $paginator);

		return view();
	}
}