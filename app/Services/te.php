<?php
class ClassName extends AnotherClass
{

    public function getInfo()
    {
        switch (PHP_OS) {
  case "Linux":
     $sysReShow  =  (($sys_info  =  sys_linux())  !==  false)  ?  "show"  :  "none";
    break;
  case "FreeBSD":
     $sysReShow  =  (($sys_info  =  sys_freebsd())  !==  false)  ?  "show"  :  "none";
    break;
  case "WINNT":
     $sysReShow  =  (($sys_info  =  sys_windows())  !==  false)  ?  "show"  :  "none";
    break;
  default:
    break;
        }
    }

    function sys_linux() {
            // CPU
            if (false  ===  ($str  =  @baifile("/proc/cpuinfo"))) return false;
             $str  =  implode("",   $str);
            @preg_match_all("/model\s+name\s{0,}\:+\s{0,}([\w\s\)\(\@.-]+)([\r\n]+)/s",   $str,   $model);
            @preg_match_all("/cpu\s+MHz\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/",   $str,   $mhz);
            @preg_match_all("/cache\s+size\s{0,}\:+\s{0,}([\d\.]+\s{0,}[A-Z]+[\r\n]+)/",   $str,   $cache);
            @preg_match_all("/bogomips\s{0,}\:+\s{0,}([\d\.]+)[\r\n]+/",   $str,   $bogomips);
            if (false  !==  is_array($model[1]))    {
                 $res['cpu']['num']       =  sizeof($model[1]);
                 $res['cpu']['num_text']  =  str_replace(array(1, 2, 4, 8, 16),  array('单', '双', '四du', '八', '十六'),   $res['cpu']['num']) . '核';
                    /*
                    for($i = 0; $i < $res['cpu']['num']; $i++) {
                        $res['cpu']['model'][] = $model[1][$i].'&nbsp;('.$mhz[1][$i].')';
                        $res['cpu']['mhz'][] = $mhz[1][$i];
                        $res['cpu']['cache'][] = $cache[1][$i];
                        $res['cpu']['bogomips'][] = $bogomips[1][$i];
                    }*/
                 $x1                                                                                     =  ($res['cpu']['num'] == 1)  ?  ''  :  ' ×' . $res['cpu']['num'];
                     $mhz[1][0]                                                                      =  ' | 频率:' . $mhz[1][0];
                     $cache[1][0]                                                                    =  ' | 二级缓存zhi:' . $cache[1][0];
                     $bogomips[1][0]                                                                 =  ' | Bogomips:' . $bogomips[1][0];
                     $res['cpu']['model'][]                                                          =   $model[1][0] . $mhz[1][0] . $cache[1][0] . $bogomips[1][0] . $x1;
                    if (false  !==  is_array($res['cpu']['model']))  $res['cpu']['model']        =  implode("<br />",   $res['cpu']['model']);
                    if (false  !==  is_array($res['cpu']['mhz']))  $res['cpu']['mhz']            =  implode("<br />",   $res['cpu']['mhz']);
                    if (false  !==  is_array($res['cpu']['cache']))  $res['cpu']['cache']        =  implode("<br />",   $res['cpu']['cache']);
                    if (false  !==  is_array($res['cpu']['bogomips']))  $res['cpu']['bogomips']  =  implode("<br />",   $res['cpu']['bogomips']);
                }
            // NETWORK
            // UPTIME
            if (false  ===  ($str  =  @file("/proc/uptime"))) return false;
             $str                                   =  explode(' ',  implode("",   $str));
             $str                                   =  trim($str[0]);
             $min                                   =   $str  /  60;
             $hours                                 =   $min  /  60;
             $days                                  =  floor($hours  /  24);
             $hours                                 =  floor($hours  -  ($days  *  24));
             $min                                   =  floor($min  -  ($days  *  60  *  24)  -  ($hours  *  60));
            if ($days  !==  0)  $res['uptime']  =   $days . "天";
            if ($hours  !==  0)  $res['uptime']  .=   $hours . "小时dao";
             $res['uptime']  .=   $min . "分钟";
            // MEMORY
            if(false  ===  ($str  =  @file("/proc/meminfo"))) return false;
             $str  =  implode("",   $str);
            preg_match_all("/MemTotal\s{0,}\:+\s{0,}([\d\.]+).+?MemFree\s{0,}\:+\s{0,}([\d\.]+).+?Cached\s{0,}\:+\s{0,}([\d\.]+).+?SwapTotal\s{0,}\:+\s{0,}([\d\.]+).+?SwapFree\s{0,}\:+\s{0,}([\d\.]+)/s",   $str,   $buf);
              preg_match_all("/Buffers\s{0,}\:+\s{0,}([\d\.]+)/s",   $str,   $buffers);
             $res['mem_total']       =  round($buf[1][0] / 1024,  2);
             $res['mem_free']        =  round($buf[2][0] / 1024,  2);
             $res['mem_buffers']     =  round($buffers[1][0] / 1024,  2);
               $res['mem_cached']  =  round($buf[3][0] / 1024,  2);
             $res['mem_used']        =   $res['mem_total'] - $res['mem_free'];
             $res['mem_percent']     =  (floatval($res['mem_total']) != 0) ? round($res['mem_used'] / $res['mem_total'] * 100, 2) : 0;
             $res['mem_real_used']   =   $res['mem_total']  -   $res['mem_free']  -   $res['mem_cached']  -   $res['mem_buffers'];
         //真实内存使用
               $res['mem_real_free']  =   $res['mem_total']  -   $res['mem_real_used'];
         //真实空闲
             $res['mem_real_percent']  =  (floatval($res['mem_total']) != 0) ? round($res['mem_real_used'] / $res['mem_total'] * 100, 2) : 0;
         //真实内存使用率
               $res['mem_cached_percent']  =  (floatval($res['mem_cached']) != 0) ? round($res['mem_cached'] / $res['mem_total'] * 100, 2) : 0;
         //Cached内存使用率
             $res['swap_total']    =  round($buf[4][0] / 1024,  2);
             $res['swap_free']     =  round($buf[5][0] / 1024,  2);
             $res['swap_used']     =  round($res['swap_total'] - $res['swap_free'],  2);
             $res['swap_percent']  =  (floatval($res['swap_total']) != 0) ? round($res['swap_used'] / $res['swap_total'] * 100, 2) : 0;
            // LOAD AVG
            if (false  ===  ($str  =  @file("/proc/loadavg"))) return false;
             $str              =  explode(' ',  implode("",   $str));
             $str              =  array_chunk($str,  4);
             $res['load_avg']  =  implode(' ',   $str[0]);
            return  $res;
    }

//FreeBSD系统探测
    function sys_freebsd() {
            //CPU
            if (false  ===  ($res['cpu']['num']  =  get_key("hw.ncpu"))) return false;
           $res['cpu']['num_text']   =  str_replace(array(1, 2, 4, 8, 16),  array('单', '双', '四', '八', '十六'),   $res['cpu']['num']) . '核';
             $res['cpu']['model']  =  get_key("hw.model");
            //LOAD AVG
            if (false  ===  ($res['load_avg']  =  get_key("vm.loadavg"))) return false;
            //UPTIME
            if (false  ===  ($buf  =  get_key("kern.boottime"))) return false;
           $buf                                   =  explode(' ',   $buf);
           $sys_ticks                             =  time()  -  intval($buf[3]);
           $min                                   =   $sys_ticks  /  60;
           $hours                                 =   $min  /  60;
           $days                                  =  floor($hours  /  24);
           $hours                                 =  floor($hours  -  ($days  *  24));
           $min                                   =  floor($min  -  ($days  *  60  *  24)  -  ($hours  *  60));
          if ($days  !==  0)  $res['uptime']  =   $days . "天";
          if ($hours  !==  0)  $res['uptime']  .=   $hours . "小时";
           $res['uptime']  .=   $min . "分钟";
         //MEMORY
          if (false  ===  ($buf  =  get_key("hw.physmem"))) return false;
           $res['mem_total']  =  round($buf / 1024 / 1024,  2);
           $str               =  get_key("vm.vmtotal");
          preg_match_all("/\nVirtual Memory[\:\s]*\(Total[\:\s]*([\d]+)K[\,\s]*Active[\:\s]*([\d]+)K\)\n/i",   $str,   $buff,  PREG_SET_ORDER);
          preg_match_all("/\nReal Memory[\:\s]*\(Total[\:\s]*([\d]+)K[\,\s]*Active[\:\s]*([\d]+)K\)\n/i",   $str,   $buf,  PREG_SET_ORDER);
           $res['mem_real_used']     =  round($buf[0][2] / 1024,  2);
           $res['mem_cached']        =  round($buff[0][2] / 1024,  2);
           $res['mem_used']          =  round($buf[0][1] / 1024,  2)  +   $res['mem_cached'];
           $res['mem_free']          =   $res['mem_total']  -   $res['mem_used'];
           $res['mem_percent']       =  (floatval($res['mem_total']) != 0) ? round($res['mem_used'] / $res['mem_total'] * 100, 2) : 0;
           $res['mem_real_percent']  =  (floatval($res['mem_total']) != 0) ? round($res['mem_real_used'] / $res['mem_total'] * 100, 2) : 0;
          return  $res;
    }

//取得参数值 FreeBSD
    function get_key($keyName) {
          return do_command('sysctl',  "-n $keyName");
    }

//确定执行文件位置 FreeBSD
    function find_command($commandName) {
           $path  =  array('/bin',  '/sbin',  '/usr/bin',  '/usr/sbin',  '/usr/local/bin',  '/usr/local/sbin');
            foreach($path as $p) {
                    if (@is_executable("$p/$commandName")) return "$p/$commandName";
                }
            return false;
    }

//windows系统探测
    function sys_windows() {
            if(PHP_VERSION  >=  5) {
                     $obj_locator  =  new COM("WbemScripting.SWbemLocator");
                     $wmi          = &  $obj_locator->ConnectServer();
                } else {
                    return false;
                }
            //CPU
             $cpuinfo            =  GetWMI(&$wmi,  "Win32_Processor",  array("Name",  "L2CacheSize",  "NumberOfCores"));
             $res['cpu']['num']  =   $cpuinfo[0]['NumberOfCores'];
            if (null  ==   $res['cpu']['num']) {
                     $res['cpu']['num']  =  1;
                }
           $res['cpu']['num_text']  =  str_replace(array(1, 2, 4, 8, 16),  array('单', '双', '四', '八', '十六'),   $res['cpu']['num']) . '核';
          /*
            for ($i=0;$i<$res['cpu']['num'];$i++) {

                $res['cpu']['model'] .= $cpuinfo[0]['Name']."<br />";

                $res['cpu']['cache'] .= $cpuinfo[0]['L2CacheSize']."<br />";

            }*/
             $cpuinfo[0]['L2CacheSize']  =  ' (' . $cpuinfo[0]['L2CacheSize'] . ')';
           $x1                             =  ($res['cpu']['num'] == 1)  ?  ''  :  ' ×' . $res['cpu']['num'];
             $res['cpu']['model']        =   $cpuinfo[0]['Name'] . $cpuinfo[0]['L2CacheSize'] . $x1;
            //SYSINFO
             $sysinfo                 =  GetWMI(&$wmi,  "Win32_OperatingSystem",  array('LastBootUpTime', 'TotalVisibleMemorySize', 'FreePhysicalMemory', 'Caption', 'CSDVersion', 'SerialNumber', 'InstallDate'));
             $sysinfo[0]['Caption']    = iconv('GBK',  'UTF-8', $sysinfo[0]['Caption']);
             $sysinfo[0]['CSDVersion'] = iconv('GBK',  'UTF-8', $sysinfo[0]['CSDVersion']);
             $res['win_n']            =   $sysinfo[0]['Caption'] . ' ' . $sysinfo[0]['CSDVersion'] . " 序列号:{$sysinfo[0]['SerialNumber']} 于" . date('Y年m月d日H:i:s', strtotime(substr($sysinfo[0]['InstallDate'], 0, 14))) . "安装";
            //UPTIME
             $res['uptime']                         =   $sysinfo[0]['LastBootUpTime'];
             $sys_ticks                             =  time()  -  strtotime(substr($res['uptime'],  0,  14));
             $min                                   =   $sys_ticks  /  60;
             $hours                                 =   $min  /  60;
             $days                                  =  floor($hours  /  24);
             $hours                                 =  floor($hours  -  ($days  *  24));
             $min                                   =  floor($min  -  ($days  *  60  *  24)  -  ($hours  *  60));
            if ($days  !==  0)  $res['uptime']  =   $days . "天";
            if ($hours  !==  0)  $res['uptime']  .=   $hours . "小时";
             $res['uptime']  .=   $min . "分钟";
            //MEMORY
             $res['mem_total']  =  round($sysinfo[0]['TotalVisibleMemorySize'] / 1024, 2);
             $res['mem_free']   =  round($sysinfo[0]['FreePhysicalMemory'] / 1024, 2);
             $res['mem_used']   =   $res['mem_total'] - $res['mem_free'];
            //上面两行已经除以1024,这行不用再除了
             $res['mem_percent']  =  round($res['mem_used']  /   $res['mem_total'] * 100, 2);
            //LoadPercentage
             $loadinfo         =  GetWMI(&$wmi,  "Win32_Processor",  array("LoadPercentage"));
             $res['load_avg']  =   $loadinfo[0]['LoadPercentage'];
            return  $res;
    }
    function GetWMI(&$wmi,   $strClass,   $strValue  =  array()) {
             $arrData     =  array();
             $objWEBM     =   $wmi->Get($strClass);
             $arrProp     =   $objWEBM->Properties_;
             $arrWEBMCol  =   $objWEBM->Instances_();
            foreach($arrWEBMCol as $objItem) {
                    @reset($arrProp);
                     $arrInstance  =  array();
                    foreach($arrProp as $propItem) {
                            eval("\$value = \$objItem->"  .   $propItem->Name  .  ";");
                            if (empty($strValue)) {
                                     $arrInstance[$propItem->Name]  =  trim($value);
                                } else {
                                    if (in_array($propItem->Name,   $strValue)) {
                                             $arrInstance[$propItem->Name]  =  trim($value);
                                        }
                                }
                        }
                     $arrData[]  =   $arrInstance;
                }
            return  $arrData;
    }
}
