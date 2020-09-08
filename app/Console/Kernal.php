<?php

namespace App\Console;

class Kernal 
{
    const COMMON_LIST = [
        ['App/Services/MemberService', 'test', '1'],
        ['App/Services/MemberService', 'test2', '1'],
    ];
    private $common = null;

    public function run()
    {
        if (empty(self::COMMON_LIST)) return false;

        $argv = $_SERVER['argv'];
        array_shift($argv);
        if (!empty($argv)) {
            if (env('APP_DEBUG'))
                \App::Log();
            call_user_func_array([make($argv[0]), $argv[1]], []);
            exit();
        }

        $date = strtr(date('m-d H:i'), ['-'=>' ', ':' => ' ']);
        $data = ['month', 'day', 'hour', 'minute'];
        $date = array_combine($data, explode(' ', $date));
        foreach (self::COMMON_LIST as $key => $value) {
            if (empty($value[2])) continue;
            $temp = explode(':', $value[2]);
            $temp = array_merge(array_fill(0, 4 - count($temp), 0), $temp);
            if ($this->matchTime(array_combine($data, $temp), $date)) {
                $cmd = 'nohup php '.ROOT_PATH.'artisan '.$value[0].' '.$value[1];
                $this->execCommand($cmd);
            }
        }
        return true;
    }

    public function execCommand($cmd)
    {
        if (substr(php_uname(), 0, 7) == 'Windows') {
            pclose(popen('start /B '. ltrim($cmd, 'nohup'), 'r')); 
        } else {
            exec($cmd . ' > /dev/null &');
        }
    }

    public function matchTime($setTime, $nowTime)
    {
        if (empty($setTime) || empty($nowTime)) return false;
        if (!empty($setTime['minute']) && count(array_filter($setTime)) == 1) {
            $check = 0;
            foreach ($setTime as $key => $value) {
                if ($value == 0 || $nowTime[$key] % $value == 0) {
                    $check ++;
                }
            }
        } else {
            $check = 0;
            foreach ($setTime as $key => $value) {
                if ($value == $nowTime[$key]) {
                    $check ++;
                }
            }
        }
        return $check == count($setTime);
    }
}
