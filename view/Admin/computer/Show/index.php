<?php $this->load('Common.baseHeader');?>
<div class="main-content bgf2">
	<div class="container-fluid">
		<div class="index-pos-box bgf margin-top-14 container-fluid">
            <div class="position color-6 left">
                <span class="ico-system ico-centos">系统:</span>
                <span id="info" class="margin-left-10"></span>
                <span class="margin-left-10">运行时间:</span>
                <span id="running"></span>
            </div>
			<a class="right color-green position menu_set" href="javascript:;">重启</a>
		</div>
		<div class="server bgf margin-top-14">
            <div class="title color-6 font-16 container-fluid">
                <span>系统负载情况</span>
            </div>
            <div class="server-circle container-fluid">
                <ul class="row" id="systemInfoList">
                    <li class="circle-box text-center loadbox">
                        <h3 class="color-9 font-14">负载状态</h3>
                        <div class="cicle">
                            <div class="bar bar-left">
                                <div class="bar-left-an bar-an"></div>
                            </div>
                            <div class="bar bar-right">
                                <div class="bar-right-an bar-an"></div>
                            </div>
                            <div class="occupy"></div>
                        </div>
                        <div class="text-box font-14 margin-top-8"></div>
                    </li>
                    <li class="circle-box text-center cpubox">
                        <h3 class="color-9 font-14">CPU使用率</h3>
                        <div class="cicle">
                            <div class="bar bar-left">
                                <div class="bar-left-an bar-an"></div>
                            </div>
                            <div class="bar bar-right">
                                <div class="bar-right-an bar-an"></div>
                            </div>
                            <div class="occupy"></div>
                        </div>
                        <div class="text-box font-14 margin-top-8"></div>
                    </li>
                    <li class="circle-box text-center membox">
                        <h3 class="color-9 font-14">内存使用率</h3>
                        <div class="cicle mem-release">
                            <div class="bar bar-left">
                                <div class="bar-left-an bar-an"></div>
                            </div>
                            <div class="bar bar-right">
                                <div class="bar-right-an bar-an"></div>
                            </div>
                            <div class="occupy"></div>
                        </div>
                        <div class="text-box font-14 margin-top-8"></div>
                    </li>
                	<li class="circle-box text-center diskbox">
                		<h3 class="color-9 font-14">磁盘使用率</h3>
                		<div class="cicle">
                			<div class="bar bar-left">
                				<div class="bar-left-an bar-an"></div>
                			</div>
                			<div class="bar bar-right">
                				<div class="bar-right-an bar-an" 
                				></div>
                			</div>
                			<div class="occupy"></div>
                		</div>
                		<div class="text-box font-14 margin-top-8"></div>
                	</li>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
        <div class="system-info bgf margin-top-14">
            <div class="title font-16 color-6 container-fluid">概览</div>
            <div class="system-info-con">
                <ul class="clearfix text-center">
                    <li class="sys-li-box">
                        <p class="name font-16 color-9">网站</p>
                        <div class="val"><a class="font-26 color-green" href="/site">0</a></div>
                    </li>
                    <li class="sys-li-box">
                        <p class="name font-16 color-9">网站</p>
                        <div class="val"><a class="font-26 color-green" href="/site">0</a></div>
                    </li>
                    <li class="sys-li-box">
                        <p class="name font-16 color-9">网站</p>
                        <div class="val"><a class="font-26 color-green" href="/site">0</a></div>
                    </li>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
        <div class="margin-top-14">
        	<div class="bgf width-50 left">
	            <div class="title color-6 font-16 container-fluid">流量</div>
	            <div class="bw-info">
	                <div class="item"><p class="c9"><span class="ico-up"></span>上行</p><a id="upSpeed">1.89 KB</a></div>
	                <div class="item"><p class="c9"><span class="ico-down"></span>下行</p><a id="downSpeed">0.75 KB</a></div>
	                <div class="item"><p class="c9">总发送</p><a id="upAll">344.23 MB</a></div>
	                <div class="item"><p class="c9">总接收</p><a id="downAll">476.31 MB</a></div>
	            </div>
	            <div id="NetImg" style="width: 100%; height: 370px; -webkit-tap-highlight-color: transparent; user-select: none; position: relative; background: transparent;" _echarts_instance_="ec_1599811111240"><div style="position: relative; overflow: hidden; width: 497px; height: 370px; padding: 0px; margin: 0px; border-width: 0px; cursor: default;"><canvas width="497" height="370" data-zr-dom-id="zr_0" style="position: absolute; left: 0px; top: 0px; width: 497px; height: 370px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px; margin: 0px; border-width: 0px;"></canvas></div><div style="position: absolute; display: none; border-style: solid; white-space: nowrap; z-index: 9999999; transition: left 0.4s cubic-bezier(0.23, 1, 0.32, 1) 0s, top 0.4s cubic-bezier(0.23, 1, 0.32, 1) 0s; background-color: rgba(50, 50, 50, 0.7); border-width: 0px; border-color: rgb(51, 51, 51); border-radius: 4px; color: rgb(255, 255, 255); font: 14px / 21px &quot;Microsoft YaHei&quot;; padding: 5px; left: 520px; top: 271px;">16:26:48<br><span style="display:inline-block;margin-right:5px;border-radius:10px;width:9px;height:9px;background-color:#f7b851"></span>上行 : 1.18<br><span style="display:inline-block;margin-right:5px;border-radius:10px;width:9px;height:9px;background-color:#52a9ff"></span>下行 : 0.58</div></div>
	        </div>
        </div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	INDEX.init();
})
</script>
<?php $this->load('Common.baseFooter');?>