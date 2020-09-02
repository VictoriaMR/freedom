<?php 

namespace App\Services;
use App\Services\Base as BaseService;

class SecretService extends BaseService
{	
    const LIST_CACHE_KEY = 'SECRET_LIST_CACHE';

    public function getListCache()
    {
    	$list = redis()->get(self::LIST_CACHE_KEY);
        if (empty($list)) {
            $model = make('App/Models/Secret');
            $list = $model->where('status', 1)->select(['appid', 'secret'])->get();
            redis()->set(self::LIST_CACHE_KEY, $list);
        }
        return $list;
    }

    public function getOne()
    {
        $list = $this->getListCache();
        return $list[0] ?? [];
    }
}
