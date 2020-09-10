<?php

namespace App\Models\Admin;

use App\Models\Base as BaseModel;

class Member extends BaseModel
{
	//表名
    protected $table = 'admin_member';

    //主键
    protected $primaryKey = 'mem_id';
}