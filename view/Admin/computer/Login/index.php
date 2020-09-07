<?php $this->load('Common.baseHeader');?>
<div class="main">
    <div class="login">
        <div class="account">
            <form class="loginform" method="post" action="<?php echo url('');?>" onsubmit="return false;">
                <div class="rlogo">登录</div>
                <div class="line"><input class="inputtxt" value="" name="username" datatype="*" nullmsg="请填写账号" errormsg="格式不对" placeholder="账号" type="text"><div class="Validform_checktip"></div></div>
                <div class="line"><input class="inputtxt" name="password" value="" datatype="*" nullmsg="请填写密码" errormsg="请填写密码" placeholder="密码" type="password"><div class="Validform_checktip"></div></div>
                <div style="color: red;position: relative;top: -14px;" id="errorStr"></div>
                <div class="line yzm" style="top: -5px; display:none;">
                	<input type="text" class="inputtxt" name="code" nullmsg="请填写4位验证码" errormsg="验证码不对" datatype="*" placeholder="请填写验证码" style="width: 220px;">
                    <div class="Validform_checktip"></div>
                    <img width="100" height="40" class="passcode" onclick="this.src=this.src.split('?')[0] + '?'+new Date().getTime()" src="./宝塔Linux面板_files/code" style="border: 1px solid #ccc; float: right;" title="点击换一张">
                </div>
                <div class="login_btn"><input id="login-button" value="登录" type="submit"></div>
                <p class="pwinfo" style="display:none">3次以上登录错误将会出现验证码</p>
            </form>
        </div>
        <div class="scanCode" style="display: none;">
            <div class="titles"><span>堡塔APP/小程序扫码登录</span></div>
            <div class="qrCode" id="qrcode"><canvas width="150" height="150"></canvas></div>
            <div class="scanTip">
                <div class="list_scan">
                    <span>打开<a href="https://www.bt.cn/bbs/thread-47408-1-1.html" target="_blank" class="btlink">堡塔APP</a>或<a href="javascript:;" class="btlink"> 宝塔小程序<div class="weChatSamll"><img src="./宝塔Linux面板_files/app.png"><em></em></div></a>
                    </span>
                    <div class="scan_tips"> <img src="./宝塔Linux面板_files/sCan.png"><span>扫一扫</span></div>
                </div>
            </div>
        </div>
        <div class="entrance" style="">
            <div class="bg_img"></div>
            <div class="tips">
                <span><img src="./宝塔Linux面板_files/scan_ico.png"><span>切换扫码登录</span></span>
                <em></em>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
.main .login {
    background-color: #fff;
    border-radius: 4px;
    height: 290px;
    left: 50%;
    margin-left: -220px;
    margin-top: -180px;
    padding: 35px 40px 50px;
    position: absolute;
    top: 50%;
    width: 360px;
}
.main .login .rlogo {
    text-align: center;
    font-size: 26px;
    color: #444;
    cursor: pointer;
    height: 40px;
    margin-bottom: 40px;
    overflow: hidden;
}
.main .login .rlogo {
    margin-top: 20px;
    margin-bottom: 25px;
    padding: 0 25px;
}
.tips {
    position: absolute;
    top: 20px;
    right: 60px;
    color: rgb(32, 165, 58);
    background: #dff0d8;
    padding: 5px 10px;
    text-align: center;
    border-radius: 4px;
}
.tips em {
    position: absolute;
    border: 6px solid #dff0d8;
    border-color: transparent transparent transparent #dff0d8;
    width: 0;
    height: 0;
    right: -11px;
    top: 8px;
    margin-left: -6px;
}
.tips span>span{
    vertical-align: middle;
}
.tips img {
    height: 16px;
    width: 16px;
    vertical-align: middle;
    margin-top: -1px;
    margin-right: 4px;
}
</style>
<?php $this->load('Common.baseFooter');?>