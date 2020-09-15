<?php

namespace frame;

class Paginator
{
    public static $_instance = null;

    // 显示文案模版配置
    protected $config = [
        // 分页显示的文案内容
        'text' => [
            'first' => '首页', //首页
            'last'  => '尾页',  //尾页
            'prev'  => '前一页', // 前一页
            'next'  => '后一页', // 后一页
            'total_count' => '总数',
            'page' => '页',
            'every' => '每页',
            'line' => '条',
            'no' => '第',
        ],
    // 显示顺序模版 默认bootstrip风格, {total} 总记录条数, {listRows} 每页显示条数
    // {currentPage} 当前页码, {totalPages} 总计多少页, {first} 首页按钮, {prev} 前一页按钮, {paging} 当前分页序列信息, {next} 下一页,{ last} 尾页
        'template' => [
            'global' => '<nav><ul class="pagination"><li  class="disabled"><span>{total_count} {total} {line}, {every} {listRows} {line}, {no} {currentPage} {page}</span></li>{first}{prev}{paging}{next}{last}</ul></nav>',
            'first' => [ // 可以区分是否可用分别定义模版， 如果直接定义模版，表示不区分可用状态
                'enabled' => '<li><a href="{url}">{text}</a></li>',
                'disabled' => '<li  class="disabled"><span>{text}</span></li>', // 定义为具体模版， 或者false表示不显示
                ],
            'prev' => [
                'enabled' => '<li><a href="{url}">{text}</a></li>',
                'disabled' => '<li  class="disabled"><span>{text}</span></li>',
            ],
            'next' => [
                'enabled' => '<li><a href="{url}">{text}</a></li>',
                'disabled' => '<li  class="disabled"><span>{text}</span></li>',
            ],
            'last' => [
                'enabled' => '<li><a href="{url}">{text}</a></li>',
                'disabled' => '<li  class="disabled"><span>{text}</span></li>',
            ],
            'paging' => '<li><a href="{url}">{text}</a></li>',
            'current' => '<li class="active"><span>{text}</span></li>',
            ]
        ];
    protected $check=true;
    protected $total=0; // 总记录数量
    protected $listRows=50; // 每页数量
    protected $currentPage=0; // 当前页
    protected $range=3; // 默认当前页码前后显示的页码数量

    // 分页参数名称 GET
    protected $pageParam = 'page';

    // 页码显示样式 false只显示页码数字， true 显示每一页的起至记录数
    protected $pageNumStyle = false;

    // 是否生成Ajax请求, false为普通连接， Ajax方式时， 设置为具体JS函数名称
    protected $isAjax = false;

    // url参数
    protected $urlParam = [
        'controller' =>'',
        'param' =>''
    ];

    // 支持场景化配置， 在继承的子类里定义好场景， 类初始化时候带上场景名称参数即可
    protected $scenes =[];

    // 构造函数
    public function __construct($config='')
    {
        if(!empty($config)) {
            if(is_array($config)){
                $this->config =  array_replace_recursive($this->config, $config);
            } elseif(is_string($config) && isset($this->scenes[$config])){
                $this->config = array_replace_recursive($this->config, $this->scenes[$config]);
            }
        }
        // 默认自动填充当前页码
        $this->setPage(true);
        // 默认自动设置Url信息
        $this->setUrlParam(true);
    }

    public static function getInstance() 
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 设置配置
     * @param  array $config
     * @return $this
     */
    public function setConfig($config)
    {
        $this->config = array_replace_recursive($this->config,$config);
        return $this;
    }

    /**
     * 设置场景
     * @param string $scenesName
     * @return $this
     */
    public function setScenes($scenesName)
    {
        if (isset($this->scenes[$scenesName])) {
            $this->config = array_replace_recursive($this->config, $this->scenes[$scenesName]);
        }
        return $this;
    }

    // 设置分页显示文本
    public function setText($first, $last, $prev, $next)
    {
        if(!empty($first)) {
            $this->config['text']['first'] = $first;
        }
        if(!empty($last)) {
            $this->config['text']['last'] = $last;
        }
        if(!empty($prev)) {
            $this->config['text']['prev'] = $prev;
        }
        if(!empty($next)) {
            $this->config['text']['next'] = $next;
        }
        return $this;
    }
    // 设置记录总数
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }
    // 设置每页显示的记录条数
    public function setListRows($listRows)
    {
        $this->listRows = $listRows;
        return $this;
    }
    // 设置当前页码左右显示的页码数量
    public function setRange($range)
    {
        $this->range = $range;
        return $this;
    }
    // 设置Ajax方式
    public function setAjax($ajaxAction=false)
    {
        $this->isAjax = $ajaxAction;
        return $this;
    }
    // 设置分页传参变量名称
    public function setPageParam($pageParam)
    {
        $this->pageParam = $pageParam;
        return $this;
    }
    // 设置当前分页， true时候自动检测
    public function setPage($page = true)
    {
        if ($page===true) {
            $this->currentPage = iget('page', 1);
        } else {
            $page = (int)$page;
            $this->currentPage = $page>0?$page:1;
        }
        return $this;
    }
    // 设置URL参数信息, $controller = true , 自动检测
    public function setUrlParam($controller = true, $param='')
    {
        if($controller===true){
            $this->urlParam['controller'] = \frame\Router::$_route['path'];
            $this->urlParam['param'] = iget();
            array_shift($this->urlParam['param']);
        } else {
            $this->urlParam['param'] =$param;
        }
        if(isset($this->urlParam['param'][$this->pageParam])){
            unset($this->urlParam['param'][$this->pageParam]);
        }
        return $this;
    }

    /** 生成分页html代码段
     * @param $listRows
     * @param null $currentPage
     * @param null $total
     * @param array|ArrayObject $resultSet
     * @return  string
     */
    public function make($listRows =null, $total=null, $currentPage = null, $resultSet = null)
    {
        if(!is_null($listRows)) {
            $this->setListRows($listRows);
        }
        if(!is_null($currentPage)){
            $this->setPage($currentPage);
        }
        if(!is_null($total)){
            $this->setTotal($total);
        }
        if($this->total>0){
            $totalPage = ceil($this->total/$listRows);
        } else {
            $totalPage =0;
        }

        if($totalPage>0 && $this->currentPage>$totalPage){
            $this->currentPage = $totalPage;
        }

        // 开始生成html， 首先处理first, last, prev, next, paging, current 几个子单元
        if($this->currentPage==1){
            $first = strtr($this->config['template']['first']['disabled'],['{url}'=>$this->url(1),'{text}'=>$this->config['text']['first']]);
            $prev = strtr($this->config['template']['prev']['disabled'],['{url}'=>$this->url($this->currentPage-1),'{text}'=>$this->config['text']['prev']]);

        } else {
            $first = strtr($this->config['template']['first']['enabled'],['{url}'=>$this->url(1),'{text}'=>$this->config['text']['first']]);
            $prev = strtr($this->config['template']['prev']['enabled'],['{url}'=>$this->url($this->currentPage-1),'{text}'=>$this->config['text']['prev']]);
        }

        if($totalPage==0){
            $last = '';
            if(!is_null($resultSet) && count($resultSet)<$this->listRows){
                $totalPage = $this->currentPage;
                $next = strtr($this->config['template']['next']['disabled'],['{url}'=>$this->url($this->currentPage+1),'{text}'=>$this->config['text']['next']]);
            } else {
                $next = $next = strtr($this->config['template']['next']['disabled'],['{url}'=>$this->url($this->currentPage+1),'{text}'=>$this->config['text']['next']]);
                $last = strtr($this->config['template']['last']['disabled'],['{url}'=>$this->url($totalPage),'{text}'=>$this->config['text']['last']]);
            }
        } else {
            if($this->currentPage<$totalPage){
                $next = strtr($this->config['template']['next']['enabled'],['{url}'=>$this->url($this->currentPage+1),'{text}'=>$this->config['text']['next']]);
                $last = strtr($this->config['template']['last']['enabled'],['{url}'=>$this->url($totalPage),'{text}'=>$this->config['text']['last']]);
            } else {
                $next = strtr($this->config['template']['next']['disabled'],['{url}'=>$this->url($this->currentPage+1),'{text}'=>$this->config['text']['next']]);
                $last = strtr($this->config['template']['last']['disabled'],['{url}'=>$this->url($totalPage),'{text}'=>$this->config['text']['last']]);
            }
        }

        $start = $this->currentPage-$this->range;
        if($start<1) {
            $start =1;
        }
        $end = $this->currentPage+$this->range;
        if($totalPage>0 && $end>$totalPage){
            $end = $totalPage;
        }
        $pageStr='';
        if($this->total>0) {
            for ($i = $start; $i <= $end; $i++) {
                if ($i == $this->currentPage) {
                    $pageStr .= strtr($this->config['template']['current'], [
                        '{url}' => $this->url($i),
                        '{text}' => $this->pageNumStyle ? (($i - 1) * $this->listRows . "-" . $i * $this->listRows) : $i
                    ]);
                } else {
                    $pageStr .= strtr($this->config['template']['paging'], [
                        '{url}' => $this->url($i),
                        '{text}' => $this->pageNumStyle ? (($i - 1) * $this->listRows . "-" . $i * $this->listRows) : $i
                    ]);
                }
            }
        }
        $replace = [
            '{total}' => $this->total,
            '{listRows}' => $this->listRows,
            '{currentPage}' => $this->currentPage,
            '{totalPages}' => $totalPage,
            '{first}' => $first,
            '{prev}' => $prev,
            '{paging}' => $pageStr,
            '{next}' => $next,
            '{last}' => $last,
            '{total_count}' => $this->config['text']['total_count'],
            '{page}' => $this->config['text']['page'],
            '{every}' => $this->config['text']['every'],
            '{line}' => $this->config['text']['line'],
            '{no}' => $this->config['text']['no'],
        ];

        $result = strtr($this->config['template']['global'],$replace);
        return $result;
    }

    // 生成每页的URL
    public function url($page)
    {
        if($page==false){ //部分禁用页面无URL
            return '';
        }

        if($page<1){
            $page=1;
        }
        if(is_string($this->isAjax)){
            return "javascript:".$this->isAjax."(".$page.")";
        } else {
            if($page>1){
                return url($this->urlParam['controller'], array_merge($this->urlParam['param'],[$this->pageParam => $page]));
            } else {
                return url($this->urlParam['controller'], $this->urlParam['param']);
            }
        }
    }

    public static function simple($listRows =null, $total=null, $currentPage = null, $resultSet = null)
    {
        $page = new static();
        return $page->make($listRows, $total, $currentPage, $resultSet);
    }
}