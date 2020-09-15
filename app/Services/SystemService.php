<?php 

namespace App\Services;
use App\Services\Base as BaseService;

class SystemService extends BaseService
{
	public function getInfo()
    {
    	switch (PHP_OS) {
    		case 'Linux':
 				$info = $this->sysLinux();
 				break;
		 	case 'WINNT':
			 	$info = $this->sysWindows();
			 	break;
		 	default:
		 		break;
	    }
	    return $info ?? [];
    }

    public function sysLinux()
    {
        $res = [];
        if (false === ($str = @file('/etc/redhat-release'))) return $res;
        $res['os_name'] = $this->strFormat($str[0]);

        //运行时间
        if (false === ($str = @file('/proc/uptime'))) return $res;
        $str = explode(' ', $str[0]);
        $str = trim($str[0]);
        $min =  $str / 60;
        $hours =  $min / 60;
        $days = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min = floor($min - ($days * 60 * 24) - ($hours * 60));
        $res['uptime'] = '';
        if ($days > 0) $res['uptime'] =  $days . '天';
        if ($hours > 0) $res['uptime'] .=  $hours . '小时';
        $res['uptime'] .=  $min . '分钟';

        //cpu
        if (false === ($str = @file('/proc/cpuinfo'))) return $res;
        $str = $this->str2array($str);
        $res['cpu_name'] = $str['model name'] ?? '';
        $res['cpu_num'] = $str['cpu cores'] ?? '';
        $res['cpu_process'] = $str['processor'] ? $str['processor'] : $str['cpu cores'];
        
		//内存
		if(false === ($str = @file('/proc/meminfo'))) return false;
        $str = $this->str2array($str);
		$res['mem_total'] = round($str['MemTotal'] / 1024, 2);
		$res['mem_free'] = round(($str['MemFree']) / 1024, 2);
        $res['mem_used'] = round($res['mem_total'] - $res['mem_free'], 2);
		$res['mem_percent'] = round($res['mem_used'] /  $res['mem_total'] * 100, 2);

		// cpu 利用率
		if (false === ($str = @file('/proc/loadavg'))) return false;
		$res['load_avg'] = round(explode(' ', $str[0])[0] * 100, 2);

        //系统负载
        $res['server_load'] = round($res['mem_percent'] * 0.5 + $res['load_avg'] * 0.5, 2);
        $res['server_loadtext'] = $this->loadText($res['server_load']);
        
        //磁盘使用
        $fp = popen('df -lm | grep -E "^(/)"', "r");
        $str = fread($fp, 1024);
        pclose($fp);
        $str = explode(' ', $this->strFormat($str));
        $res['disk_total'] = $str[1] ?? '';
        $res['disk_free'] = $str[3] ?? '';
        $res['disk_used'] = $str[2] ?? '';
        $res['disk_percent'] = trim($str[4] ?? '', '%');

        //网络
        if (false === ($str = @file('/proc/net/dev'))) return false;
        $str = array_values(array_filter(explode(' ', explode(':', $str[2])[1])));

        $res['net_uptotal'] = $str[2] ?? 0;
        $res['net_downtotal'] = $str[0] ?? 0;
        $res['net_up'] = $res['net_uptotal'] - (redis()->get('SYSTEM_NETWORD_UP') ?? 0);
        $res['net_down'] = $str[0] - (redis()->get('SYSTEM_NETWORD_DOWN') ?? 0);

        $diff = time() - redis()->get('SYSTEM_NETWORD_PREVTIME');

        redis()->set('SYSTEM_NETWORD_UP', $res['net_uptotal']);
        redis()->set('SYSTEM_NETWORD_DOWN', $res['net_downtotal']);
        redis()->set('SYSTEM_NETWORD_PREVTIME', time());

        $res['net_up'] = round($res['net_up']/(1024) / $diff, 2);
        $res['net_down'] = round($res['net_down']/(1024) / $diff, 2);
        $res['net_uptotal'] = round($res['net_uptotal']/(1024*1024), 2);
        $res['net_downtotal'] = round($res['net_downtotal']/(1024*1024), 2);

 		return $res;
    }

    protected function str2array($str)
    {
        $data = [];
        foreach ($str as $key => $value) {
            $value = explode(':', $value);
            $temp = $value[0];
            unset($value[0]);
            $data[$this->strFormat($temp)] = $this->strFormat(implode(':', $value));
        }
        return $data;
    }

    public function get_key($keyName) 
    {
    	return do_command('sysctl', '-n $keyName');
    }

    public function find_command($commandName) 
    {
    	$path = array('/bin', '/sbin', '/usr/bin', '/usr/sbin', '/usr/local/bin', '/usr/local/sbin');
    	foreach ($path as $p) {
    		if (@is_executable("$p/$commandName")) return "$p/$commandName";
    	}
    	return false;
    }

    public function strFormat($str)
    {
    	return trim(str_replace(' kB', '', preg_replace('/\s+/', ' ',$str)));
    }

    public function sysWindows() 
    {
    	if (PHP_VERSION < 5) return false;
    	$res = [];
    	//系统版本名称
    	$out = [];
		$info = exec('wmic os get Caption, Version', $out);
    	$res['os_name'] = $this->strFormat(mb_convert_encoding($out[1], 'utf-8', 'GBK'));

    	//运行时间
    	$out = [];
    	exec('wmic os get lastBootUpTime', $out);
    	$sys_ticks = time() - strtotime(substr($out[1], 0, 14));
    	$min =  $sys_ticks / 60;
    	$hours =  $min / 60;
    	$days = floor($hours / 24);
    	$hours = floor($hours - ($days * 24));
    	$min = floor($min - ($days * 60 * 24) - ($hours * 60));
    	$res['uptime'] = '';
    	if ($days > 0) $res['uptime'] =  $days . "天";
    	if ($hours > 0) $res['uptime'] .=  $hours . "小时";
    	$res['uptime'] .= $min . "分钟";

    	// 磁盘使用
    	$out = [];
    	exec('wmic logicaldisk get FreeSpace,size /format:list', $out);
    	$out = array_filter($out);
    	$res['disk_used'] = 0;
    	$res['disk_free'] = 0;
    	$res['disk_total'] = 0;
    	foreach ($out as $value) {
    		if (strpos($value, 'FreeSpace=') !== false) {
    			$res['disk_free'] += explode('FreeSpace=', $value)[1];
    		} elseif (strpos($value, 'Size=') !== false) {
    			$res['disk_total'] += explode('Size=', $value)[1];
    		}
    	}
    	$res['disk_total'] = round($res['disk_total'] / 1024 / 1024, 2);
    	$res['disk_free']  = round($res['disk_free'] / 1024 / 1024, 2);
    	$res['disk_used']  = round($res['disk_total'] - $res['disk_free'], 2);
    	$res['disk_percent'] = round($res['disk_used'] /  $res['disk_total'] * 100, 2);

    	//内存
    	$out = [];
    	exec('wmic os get TotalVisibleMemorySize,FreePhysicalMemory', $out);
    	$res['mem_used'] = 0;
    	$res['mem_free'] = 0;
    	$res['mem_total'] = 0;
    	$out = array_filter(explode(' ', $this->strFormat($out[1])));
    	$res['mem_total'] = round($out[1] / 1024, 2);
    	$res['mem_free']  = round($out[0] / 1024, 2);
    	$res['mem_used'] = round($res['mem_total'] - $res['mem_free'], 2);
    	$res['mem_percent'] = round($res['mem_used'] /  $res['mem_total'] * 100, 2);

    	//CPU
    	$out = [];
    	exec('wmic cpu get Name, NumberOfCores, NumberOfLogicalProcessors', $out);
    	$out = array_values(array_filter(explode('  ', $out[1])));
    	$res['cpu_name'] = $out[0] ?? '';
    	$res['cpu_num'] = $out[1] ? $out[1].' 核' : '';
    	$res['cpu_process'] = $out[2] ? $out[2].' 线程' : '';
    	$obj_locator = new \COM('WbemScripting.SWbemLocator');
    	$loadinfo = $this->getWMI($obj_locator->ConnectServer(), 'Win32_Processor', ['LoadPercentage']);
    	$res['load_avg'] = $loadinfo[0]['LoadPercentage'];

    	$out = [];
		exec('netstat -e', $out);
		$out = array_values(array_filter(explode('  ', $out[4])));
		$res['net_uptotal'] = $out[1] ?? 0;
		$res['net_downtotal'] = $out[2] ?? 0;
		$res['net_up'] = $res['net_uptotal'] - (redis()->get('SYSTEM_NETWORD_UP') ?? 0);
		$res['net_down'] = $out[2] - (redis()->get('SYSTEM_NETWORD_DOWN') ?? 0);
        $diff = time() - redis()->get('SYSTEM_NETWORD_PREVTIME');

        redis()->set('SYSTEM_NETWORD_PREVTIME', time());
		redis()->set('SYSTEM_NETWORD_UP', $res['net_uptotal']);
		redis()->set('SYSTEM_NETWORD_DOWN', $res['net_downtotal']);

		$res['net_up'] = round($res['net_up']/(1024) / $diff, 2);
		$res['net_down'] = round($res['net_down']/(1024) / $diff, 2);
		$res['net_uptotal'] = round($res['net_uptotal']/(1024*1024), 2);
		$res['net_downtotal'] = round($res['net_downtotal']/(1024*1024), 2);

		//系统负载
		$res['server_load'] = round($res['mem_percent'] * 0.5 + $res['load_avg'] * 0.5, 2);
		$res['server_loadtext'] = $this->loadText($res['server_load']);
    	return $res;
    }

    protected function loadText($num)
    {
    	if ($num < 10) {
    		$text = '运行流畅';
    	} elseif($num < 30) {
    		$text = '轻微压力';
    	} elseif($num < 50) {
    		$text = '中度压力';
    	} elseif($num < 70) {
    		$text = '中高度压力';
    	} elseif($num < 90) {
    		$text = '高度压力';
    	} else {
    		$text = '危险压力';
    	}
    	return $text;
    }

    public function getWMI($wmi, $strClass, $strValue = []) 
    {
    	$arrData = [];
    	$objWEBM = $wmi->Get($strClass);
    	$arrProp = $objWEBM->Properties_;
    	$arrWEBMCol = $objWEBM->Instances_();
    	foreach ($arrWEBMCol as $objItem) {
    		$temp = [];
	    	foreach($arrProp as $propItem) {
	    		eval("\$value = \$objItem->".$propItem->Name . ';');
	    		if (empty($strValue)) {
	    			$temp[$propItem->Name] = trim($value);
	    		} else {
	    			if (in_array($propItem->Name, $strValue)) {
	    				$temp[$propItem->Name] = trim($value);
	    			}
	    		}
	    	}
	    	$arrData[] = $temp;
	    }
	    return $arrData;
    }
}