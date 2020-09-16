<?php include_once 'D:/www/freedom/view/Admin/computer/Common/baseHeader.php';?>
<div id="login-bg"></div>
<div class="login-box login-password">
    <div class="poptip-content">扫码登录更安全</div>
    <div class="title text-center margin-top-20">
        <a class="font-24 font-600" href="javascript:void(0);">登录</a>
    </div>
    <div class="clear"></div>
    <form class="relative margin-top-40">
        <div id="login-error" style="display: none;">
            <div id="login-error-msg" class="left margin-left-4"></div>
        </div>
        <input type="hidden" name="verify_code" value="<?php echo $login_code;?>">
        <div class="margin-bottom-20">
            <input type="input" class="input" name="phone" placeholder="账号" autocomplete="off">
        </div>
        <div class="margin-bottom-20">
            <input type="password" class="input" name="password" placeholder="密码" autocomplete="off">
        </div>
        <div class="margin-bottom-20">
            <div class="verify-wrap" id="verify-wrap">
                <div class="drag-progress dragProgress"></div>
                <span class="drag-btn"></span>
                <span class="fix-tips fixTips">拖动滑块验证</span>
                <span class="verify-msg sucMsg">验证通过</span>
            </div>
        </div>
        <button id="login-btn" type="button" class="btn btn-primary btn-lg btn-block" data-loading-text="Loading...">登录</button>
    </form>
</div>
<script type="text/javascript">
$(function(){
    LOGIN.init();
});
</script>
<?php include_once load('Common.baseFooter');?>