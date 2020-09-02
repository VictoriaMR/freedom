<?php 

namespace App\Services;
use App\Services\Base as BaseService;
use App\Models\BindRelation;

class BindRelationService extends BaseService
{	
	public function __construct(BindRelation $model)
    {
        $this->baseModel = $model;
    }

    public function getIdByOpenid($openid, $type = 0)
    {
    	return $this->baseModel->where('openid', $openid)
    						   ->where('type', $type)
    						   ->value('mem_id');
    }

}
