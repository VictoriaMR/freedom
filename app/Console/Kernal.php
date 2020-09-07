<?php

namespace App\Console;

class Kernal 
{
    const COMMON_LIST = [
        ['App/Services/MemberService', 'test', '* * * 2'],
        ['App/Services/MemberService', 'test1', '* * * 2'],
        ['App/Services/MemberService', 'test2', '* * * 2'],
    ];
    private $common = null;

    public function run()
    {
        if (empty(self::COMMON_LIST)) return false;

        $date = strtr(date('m-d H:i'), ['-'=>' ', ':' => ' ']);
        $data = ['month', 'day', 'hour', 'minute'];
        $date = array_combine($data, explode(' ', $date));
        print_r($date);
        foreach (self::COMMON_LIST as $key => $value) {
            if ($this->matchTime(array_combine($data, explode(' ', $value[2] ?? '')), $date)) {
                \frame\Hook::async([make($value[0]), $value[1]]);
            }
        }

        return true;
        dd();
    }

    public function matchTime($setTime, $nowTime)
    {
        if (empty($setTime) || empty($nowTime)) return false;
        foreach ($setTime as $key => $value) {
            if ($value != '*') {
                return $nowTime[$key] % $value == 0;
            }
        }
        return false;
    }
}
