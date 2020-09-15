<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Services\Admin\ControllerService;
use frame\Html;

class SetController extends Controller
{
	public function __construct(ControllerService $service)
	{
		$this->baseService = $service;
		parent::_initialize();
	}

	public function index()
	{
		$opt = ipost('opt');
		switch ($opt) {
			case 'edit':
				$this->featureModify();
				break;
			case 'delete':
				$this->featureDelete();
				break;
			case 'sort':
				$this->updateSort();
				break;
		}

		Html::addCss(['index']);
		Html::addJs(['index']);
		$list = $this->baseService->getList();
		$iconList = [];
		foreach (scandir(ROOT_PATH.'admin/image/computer/icon/feature') as $value) {
			if ($value == '.' || $value == '..') continue;
			$temp = explode('.', $value);
			if (substr($temp[0], 0, 1) == '5') {
				$iconList[] = [
					'name' => $temp[0],
					'type' => $temp[1],
					'value' => $value,
					'url' => url('image/computer/icon/feature/'.$value),
				];
			}
		}
		$this->assign('iconList', $iconList);
		$this->assign('list', $list);
		return view();
	}

	public function updateSort()
	{
		$sort = ipost('sort');
		if (empty($sort))
			return $this->result(10000, false, ['message'=>'缺失参数']);
		foreach ($sort as $key => $value) {
			$this->baseService->updateDataById($value, ['sort'=>$key]);
		}
		$this->baseService->deleteCache();
		return $this->result(200, true, ['message' => '排序成功']);
	}

	protected function featureDelete()
	{
		$conId = (int) ipost('con_id');
		if (empty($conId))
			return $this->result(10000, false, ['message'=>'缺失参数']);

		//先删除子类 再删除 主类
		if ($this->baseService->isParent($conId))
			$this->baseService->deleteByParentId($conId);

		$result = $this->baseService->deleteById($conId);

		if ($result)
			return $this->result(200, $result, ['message' => '删除成功']);
		else
			return $this->result(10000, $result, ['message' => '删除失败']);
	}

	protected function featureModify()
	{
		$conId = (int) ipost('con_id');
		$parentId = (int) ipost('parent_id', 0);
		$status = ipost('status', null);
		$name = ipost('name', '');
		$nameEn = ipost('name_en', '');
		$icon = ipost('icon', '');
		$sort = ipost('sort');
		$data = [];

		if ($status !== null)
			$data['status'] = (int) $status;
		if (!empty($name))
			$data['name'] = $name;
		if (!empty($nameEn))
			$data['name_en'] = $nameEn;
		if (!empty($icon))
			$data['icon'] = $icon;
		if (!empty($sort))
			$data['sort'] = $sort;

		if (empty($data))
			return $this->result(10000, false, ['message'=>'参数不正确']);

		if (!empty($conId)) {
			$result = $this->baseService->updateInfo($conId, $data);
		} else {
			if (!empty($parentId))
				$data['parent_id'] = $parentId;
			$result = $this->baseService->insertGetId($data);
			if ($result)
				$this->baseService->deleteCache();
		}

		if ($result)
			return $this->result(200, $result, ['message' => '保存成功']);
		else
			return $this->result(10000, $result, ['message' => '保存失败']);
	}

	public function site()
	{
		$opt = ipost('opt');
		if ($opt == 'compress')
			$this->compress();

		Html::addJs(['site']);

		return view();
	}

	protected function compress()
	{
		$type = ipost('type');

		if (!in_array($type, ['css', 'js']))
			$this->result(10000, false, '参数不正确');

		$data = ['admin', 'home'];
		foreach ($data as $value) {
			$path = ROOT_PATH.$value.'/'.$type;
			if (!is_dir($path)) continue;
			$data = $this->getFile($path);
			if (empty($data)) continue;
			foreach ($data as $v) {
				if (strpos($v, 'jquery') !== false || strpos($v, 'bootstrap') !== false) continue;
				$len = strrpos($v, '.');
				$functionName = 'compress'.$type;
				$this->$functionName($v, substr($v, 0, $len).'.min'.substr($v, $len));
			}
		}
		$this->result(200, true, '压缩完成');
	}

	protected function compressjs($jsFile, $newFile)
	{
		$file = fopen($jsFile, 'r');
		
		$js = '';
		$kuai = false;
		while(! feof($file)) {
			$temp = trim(fgets($file));
			if ($kuai && substr($temp, 0, 1) != '*') $kuai = false;
			if ($kuai) continue;
			if (substr($temp, 0, 2) == '/*' && substr($temp, -2, 2) == '*/') continue;
			if (substr($temp, 0, 2) == '/*') $kuai = true;
			if ($kuai) continue;
			if (substr($temp, 0, 2) == '//') continue;
			if (empty($temp)) continue;
			$temp = preg_replace("/\s(?=\s)/", "\\1", $temp);
			$temp = explode('//', $temp)[0];
			$js .= $temp.PHP_EOL;
		}
		//关闭被打开的文件
		fclose($file);
	  	return file_put_contents($newFile, trim($js));
	}

	protected function compresscss($cssFile, $newFile)
	{
		$css = file_get_contents($cssFile);
		//去除注释
	  	$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
	  	//去除多个空格
	  	$css = preg_replace("/\s(?=\s)/", "\\1", $css);
	  	//去除换行
	  	$css = str_replace(["\r", "\n", "\t", ';}',': ', ' {', '{ ', '; '], ['', '', '', '}', ':', '{', '{', ';'], $css);
	  	return file_put_contents($newFile, $css);
	}

	protected function getFile($dir)
	{
		$returnData = [];
		if (is_dir($dir)) {
			foreach (scandir($dir) as $value) {
				if ($value == '.' || $value == '..') continue;
				if (is_dir($dir.'/'.$value)) {
					$returnData = array_merge($returnData, $this->getFile($dir.'/'.$value));
				} else {
					if (strpos($value, '.min.') !== false) continue;
					$returnData[] = $dir.'/'.$value;
				}
			}
		}
		return $returnData;
	}
}