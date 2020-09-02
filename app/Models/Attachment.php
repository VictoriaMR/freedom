<?php

namespace App\Models;
use App\Models\Base as BaseModel;

class Attachment extends BaseModel
{
	//表名
    protected $table = 'attachment';

    //主键
    protected $primaryKey = 'attach_id';

    public function create($data)
    {
    	if (empty($data['name'])) return false;
    	$insert = [
    		'name' => $data['name'],
		  	'type' => $data['type'],
		  	'cate' => $data['cate'],
            'size' => $data['size'] ?? 0,
		  	'create_at' => $this->getTime(),
    	];
    	return $this->insertGetId($insert);
    }

    public function getAttachmentByHash($name)
    {
    	if (empty($name)) return [];
        return $this->where('name', $name)->find();
    }

    public function isExitsHash($name)
    {
    	if (empty($name)) return false;
        return $this->where('name', $name)
                    ->count() > 0;
    }

    public function getListById($idArr = [])
    {
        if (empty($idArr)) return [];

        if (!is_array($idArr))
            $idArr = [(int) $idArr];

        return $this->whereIn($this->primaryKey, $idArr)
                    ->select('attach_id, name, type, cate')
                    ->get();
    }
}
