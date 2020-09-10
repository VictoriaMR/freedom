<?php $this->load('Common.baseHeader');?>
<div id="login-bg"></div>
<div class="login-box">
    <div class="poptip-content hidden">扫码登录更安全</div>
    <div class="title">
        <a class="font-16 font-800 select" href="javascript:void(0);">密码登录</a>
        <a class="font-16 font-800 hidden" href="javascript:void(0);">短信登录</a>
    </div>
    <div class="clear"></div>
    <form class="relative">
        <div id="login-error" class="hidden">
            <i class="iconfont icon-warning left"></i>
            <div id="login-error-msg" class="left margin-left-4">请输入帐户名</div>
        </div>
        <div class="margin-bottom-20">
            <input type="input" class="input" name="phone" placeholder="请输入手机号码" autocomplete="off">
        </div>
        <div class="margin-bottom-20">
            <input type="password" class="input" name="password" placeholder="请输入密码" autocomplete="off">
        </div>
        <button id="login-btn" type="button" class="btn btn-primary btn-lg btn-block" data-loading-text="Loading...">登录</button>
    </form>
</div>
<?php $this->load('Common.baseFooter');?>