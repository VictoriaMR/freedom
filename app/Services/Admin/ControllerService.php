<?php 

namespace App\Services\Admin;

use App\Services\Base as BaseService;
use App\Models\Admin\Controller;

class ControllerService extends BaseService
{	
    const CACHE_LIST_KEY = 'ADMIN_CONTROLLER_LIST_CACHE';

	protected static $constantMap = [
        'base' => Controller::class,
    ];

    public function __construct(Controller $model)
    {
        $this->baseModel =  $model;
    }

    public function getPerantList()
    {
        return $this->getListCache();
    }

    public function getList($where = [])
    {
    	$list = $this->baseModel->getList($where);
        if (!empty($list)) {
            foreach ($list as $key => $value) {
                $value['url'] = url($value['name_en']);
                $value['icon_url'] = url('image/computer/icon/feature/'.$value['icon'].'.png');
                $list[$key] = $value;
            }
        }
        return $this->listFormat($list);
    }

    public function getListCache() 
    {
        $list = redis()->get(self::CACHE_LIST_KEY);
        if (empty($list)) {
	        $list = $this->listFormat($this->getList(['status'=>1]));
            if (!empty($list))
                redis()->set(self::CACHE_LIST_KEY, $list);
        }
    	return $list;
    }

    protected function listFormat($list, $parentId = 0) 
    {
    	$returnData = [];
    	foreach ($list as $value) {
    		if ($value['parent_id'] == $parentId) {
    			$temp = $this->listFormat($list, $value['con_id']);
    			if (!empty($temp)) {
	    			$value['son'] = $temp;
    			}
	    		$returnData[$value['name_en']] = $value;
    		}
    	}
    	return $returnData;
    }

    public function getListByParentName($name)
    {
        $name = strtolower(substr($name, 0, 1)).substr($name, 1);
        return $this->getListCache()[$name] ?? [];
    }

    public function isParent($conId)
    {
        return $this->baseModel->isParent($conId);
    }

    protected function modifyIndoByParentId($conId, $data)
    {
        return $this->baseModel->modifyIndoByParentId($conId, $data);
    }

    public function deleteCache()
    {
        return redis()->del(self::CACHE_LIST_KEY);
    }

    public function updateInfo($conId, $data)
    {
        $result = $this->updateDataById($conId, $data);
        if ($result) {
            if (isset($data['status']) && $data['status'] == self::constant('STATUS_CLOSE')) {
                if ($this->isParent($conId)) {
                    $result = $this->modifyIndoByParentId($conId, ['status' => 0]);
                }
            }
            $this->deleteCache();
        }
        return $result;
    }

    public function deleteByParentId($parentId)
    {
        return $this->baseModel->deleteByParentId($parentId);
        $parentId = (int) $parentId;
        if (empty($parentId)) return false;

        return $this->baseModel->where('parent_id', $parentId)
                               ->delete();
    }
}