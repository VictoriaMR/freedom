<?php

namespace App\Models;
use App\Models\Base as BaseModel;

class Secret extends BaseModel
{
    //表名
    public $table = 'secret_key';
    //主键
    protected $primaryKey = 'sec_id';
}