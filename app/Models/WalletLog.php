<?php

namespace App\Models;
use App\Models\Base as BaseModel;

class WalletLog extends BaseModel
{
	const TYPE_INCREMENT = 0;
	const TYPE_DECREMENT = 1;

    //表名
    public $table = 'wallet_log';
    //主键
    protected $primaryKey = 'log_id';
}