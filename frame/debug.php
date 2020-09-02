<?php

namespace frame;

class Debug
{
	public static function debugInit()
	{
        // 获取基本信息
        $runtime = number_format(microtime(true) - APP_TIME_START, 10, '.', '');
        $reqs    = $runtime > 0 ? number_format(1 / $runtime, 2) : '∞';
        $mem     = number_format((memory_get_usage() - APP_MEMORY_START) / 1024, 2);
        $uri = implode(' ', [
            $_SERVER['SERVER_PROTOCOL'],
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
        ]);
        $info = get_included_files();
        $fileMem = 0;
        foreach ($info as $key => $file) {
            $temp = number_format(filesize($file) / 1024, 2);
            $fileMem += $temp;
            $info[$key] .= ' ( ' . $temp . ' KB )';
        }
        $base = [
            '请求信息' => date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']) . ' ' . $uri,
            '运行时间' => number_format((float) $runtime, 6) . 's [ 吞吐率：' . $reqs . ' req/s ] 内存消耗：' . $mem . ' KB 文件加载：' . count($info),
            '查询信息' => '',
            '缓存信息' => '',
            '文件总值' => $fileMem . ' KB',
        ];
        $config = [
            'file' => '',
            'tabs' => ['base' => '基本', 'file' => '文件', 'info' => '流程', 'notice|error' => '错误', 'sql' => 'SQL'],
        ];
        $trace = [];
        foreach ($config['tabs'] as $name => $title) {
            $name = strtolower($name);
            switch ($name) {
                case 'base': // 基本信息
                    $trace[$title] = $base;
                    break;
                case 'file': // 文件信息
                    $trace[$title] = $info;
                    break;
                case 'sql': // 文件信息
                    $trace[$title] = $GLOBALS['exec_sql'] ?? '';
                    break;
                default: // 调试信息
                    if (strpos($name, '|')) {
                        // 多组信息
                        $names  = explode('|', $name);
                        $result = [];
                        foreach ($names as $item) {
                            $result = array_merge($result, $log[$item] ?? []);
                        }
                        $trace[$title] = $result;
                    } else {
                        $trace[$title] = $log[$name] ?? '';
                    }
                    break;
            }
        }
        assign('trace', $trace);
        assign('runtime', $runtime);
        echo view('frame/pagetrace');
	}
}