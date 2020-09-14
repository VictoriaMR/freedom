<?php

namespace App\Models\Admin;

use App\Models\Base as BaseModel;

class Controller extends BaseModel
{
    const STATUS_OPEN = 1;
    const STATUS_CLOSE = 0;
    const EXPIRE_TIME = 60 * 60 * 24;
    
    //表名
    public $table = 'admin_controller';

    //主键
    protected $primaryKey = 'con_id';

    public function getList($where = [])
    {
    	return $this->where($where)
    				->orderBy('sort', 'desc')
    				->get();
    }

    public function isParent($conId)
    {
        if (empty($conId)) return false;
        return $this->where('con_id', (int) $conId)
                    ->where('parent_id', 0)
                    ->count() > 0;
    }

    public function modifyIndoByParentId($conId, $data)
    {
        if (empty($conId)) return false;
        return $this->where('parent_id', (int) $conId)
                    ->update($data);
    }
}