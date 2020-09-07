<?php

namespace App\Models;
use App\Models\Base as BaseModel;

class Wallet extends BaseModel
{
    //表名
    public $table = 'wallet';
    //主键
    protected $primaryKey = 'wallet_id';

    public function incrementByKey($key, $money, $data=[])
    {
    	if (empty($key) || empty($money)) return false;
    	$logModel = make('App/Models/WalletLog');
    	$this->begin();
    	$this->where('wallet_key', $key)->increment('subtotal', (int) $money);
    	$data['wallet_key'] = $key;
    	$data['subtotal'] = (int) $money;
    	$data['type'] = $logModel::TYPE_INCREMENT;
    	$data['create_at'] = $this->getTime();
    	$logModel->insert($data);
    	$this->commit();
    	return true;
    }

    public function decrementByKey($key, $money, $data=[])
    {
    	if (empty($key) || empty($money)) return false;
    	$logModel = make('App/Models/WalletLog');
    	$this->begin();
    	$this->where('wallet_key', $key)->decrement('subtotal', (int) $money);
    	$data['wallet_key'] = $key;
    	$data['subtotal'] = (int) $money;
    	$data['type'] = $logModel::TYPE_DECREMENT;
    	$data['create_at'] = $this->getTime();
    	$logModel->insert($data);
    	$this->commit();
    	return true;
    }

    public function checkMoney($key, $money)
    {
    	if (empty($key) || empty($money)) return false;
    	return $this->where('wallet_key', $key)->where('subtotal', '>=', (int) $money)->count() > 0;
    }

    public function existKey($key)
    {
    	if (empty($key)) return false;
    	return $this->where('wallet_key', $key)->count() > 0;
    }
}