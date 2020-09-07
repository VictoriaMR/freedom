<?php 

namespace App\Services;
use App\Services\Base as BaseService;
use App\Models\Wallet;
use App\Models\WalletLog;

class WalletService extends BaseService
{	
	protected static $constantMap = [
        'base' => Wallet::class,
        'log' => WalletLog::class,
    ];

	public function __construct(Wallet $model)
    {
        $this->baseModel = $model;
    }

    public function getKey($memberId, $next='')
    {
    	if(!empty($next))
    		$memberId .= '-'.$next;
    	return md5($memberId);
    }

    public function incrementByKey($key, $money, $data=[])
    {
    	return $this->baseModel->incrementByKey($key, $money, $data);
    }

    public function decrementByKey($key, $money, $data=[])
    {
    	return $this->baseModel->decrementByKey($key, $money, $data);
    }

    public function checkMoney($key, $money)
    {
    	return $this->baseModel->checkMoney($key, $money);
    }

    public function existKey($key)
    {
    	return $this->baseModel->existKey($key);
    }
}