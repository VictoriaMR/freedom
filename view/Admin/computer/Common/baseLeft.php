<link rel="stylesheet" type="text/css" href="<?php echo url('css/computer/common_left.css');?>">
<div class="sidebar-scroll left">
    <div class="sidebar-auto">
        <div id="task" class="task color-f" onclick="messagebox()">0</div>
        <h3 class="mypcip">
            <span class="f14 color-f"><?php echo \frame\Session::get('admin_nickname');?></span>
        </h3>
        <ul class="menu">
            <li <?php if (\frame\Router::$_route['path'] == 'Index') { ?>class="current"<?php } ?>> 
                <a class="menu_home" href="<?php echo url('');?>">首页</a>
            </li>
            <li>
                <a class="menu_web" href="http://106.52.173.193:8000/site">网站</a></li>
            <li>
                <a class="menu_ftp" href="http://106.52.173.193:8000/ftp">FTP</a></li>
            <li>
                <a class="menu_data" href="http://106.52.173.193:8000/database">数据库</a></li>
            <li>
                <a class="menu_control" href="http://106.52.173.193:8000/control">监控</a></li>
            <li>
                <a class="menu_firewall" href="http://106.52.173.193:8000/firewall">安全</a></li>
            <li>
                <a class="menu_folder" href="http://106.52.173.193:8000/files">文件</a></li>
            <li>
                <a class="menu_xterm" href="http://106.52.173.193:8000/xterm">终端</a></li>
            <li>
                <a class="menu_day" href="http://106.52.173.193:8000/crontab">计划任务</a></li>
            <li>
                <a class="menu_soft" href="http://106.52.173.193:8000/soft">软件商店</a></li>
            <li>
                <a class="menu_set" href="http://106.52.173.193:8000/config">面板设置</a></li>
            <li>
                <a class="menu_exit" href="http://106.52.173.193:8000/login?dologin=True">退出</a></li>
        </ul>
    </div>
</div>