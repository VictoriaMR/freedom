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
	 		case 'FreeBSD':
			 	$info = $this->sysFreebsd();
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
    	if (!is_file('/proc/cpuinfo')) return false;
    	$fp = fopen('/proc/cpuinfo', 'r');
		$str = fread($fp, 9999);
		fclose($fp);
	    @preg_match_all('/model\s+name\s{0,}\:+\s{0,}([\w\s\)\(\@.-]+)([\r\n]+)/s', $str, $model);
		@preg_match_all('/cpu\s+MHz\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/', $str, $mhz);
		@preg_match_all('/cache\s+size\s{0,}\:+\s{0,}([\d\.]+\s{0,}[A-Z]+[\r\n]+)/', $str, $cache);
		@preg_match_all('/bogomips\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/', $str, $bogomips);
		if (is_array($model[1])) {
			$res['cpu']['num'] = sizeof($model[1]);
			$res['cpu']['num_text'] = str_replace(array(1, 2, 4, 8, 16), array('单', '双', '四', '八', '十六'), $res['cpu']['num']) . '核';
			$x1 = ($res['cpu']['num'] == 1) ? '' : ' ×' . $res['cpu']['num'];
			$mhz[1][0] = ' | 频率:' . $mhz[1][0];
			$cache[1][0] = ' | 二级缓存:' . $cache[1][0];
			$bogomips[1][0] = ' | Bogomips(运算速度):' . $bogomips[1][0];
			$res['cpu']['model'][] = $model[1][0] . $mhz[1][0] . $cache[1][0] . $bogomips[1][0] . $x1;
			if (is_array($res['cpu']['model'])) $res['cpu']['model'] = implode('<br />',  $res['cpu']['model']);
		}
		if (false === ($str = @file('/proc/uptime'))) return false;
		$str = explode(' ', implode(' ',  $str));
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
		// MEMORY
		if(false === ($str = @file('/proc/meminfo'))) return false;
		$str = implode('',  $str);
		preg_match_all('/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?Cached\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s',  $str,  $buf);
		preg_match_all('/Buffers\s{0,}\:+\s{0,}([\d\.]+)/s',  $str,  $buffers);
		$res['mem_total'] = round($buf[1][0] / 1024, 2);
		$res['mem_free'] = round($buf[2][0] / 1024, 2);
		$res['mem_buffers'] = round($buffers[1][0] / 1024, 2);
		$res['mem_cached'] = round($buf[3][0] / 1024, 2);
		$res['mem_used'] =  round($res['mem_total'] - $res['mem_free'], 2);
		$res['mem_percent'] = (floatval($res['mem_total']) != 0) ? round($res['mem_used'] / $res['mem_total'] * 100, 2) : 0;
		$res['mem_real_used'] = $res['mem_total'] -  $res['mem_free'] -  $res['mem_cached'] -  $res['mem_buffers'];
		//真实内存使用
		$res['mem_real_free'] =  $res['mem_total'] -  $res['mem_real_used'];
		//真实空闲
		$res['mem_real_percent'] = (floatval($res['mem_total']) != 0) ? round($res['mem_real_used'] / $res['mem_total'] * 100, 2) : 0;
		//真实内存使用率
		$res['mem_cached_percent'] = (floatval($res['mem_cached']) != 0) ? round($res['mem_cached'] / $res['mem_total'] * 100, 2) : 0;
		//Cached内存使用率
		$res['swap_total'] = round($buf[4][0] / 1024, 2);
		$res['swap_free'] = round($buf[5][0] / 1024, 2);
		$res['swap_used'] = round($res['swap_total'] - $res['swap_free'], 2);
		$res['swap_percent'] = (floatval($res['swap_total']) != 0) ? round($res['swap_used'] / $res['swap_total'] * 100, 2) : 0;
		// LOAD AVG
		if (false === ($str = @file('/proc/loadavg'))) return false;
		$str = explode(' ', implode('',  $str));
		$str = array_chunk($str, 4);
		$res['load_avg'] = implode(' ',  $str[0]);
 		return $res;
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
    	return trim(preg_replace('/\s+/', ' ',$str));
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

		redis()->set('SYSTEM_NETWORD_UP', $res['net_uptotal']);
		redis()->set('SYSTEM_NETWORD_DOWN', $res['net_downtotal']);

		$res['net_up'] = round($res['net_up']/(1024) / 3, 2);
		$res['net_down'] = round($res['net_down']/(1024) / 3, 2);
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