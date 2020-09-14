<?php $this->load('Common.baseHeader');?>
<div class="container-fluid button-update">
    <div class="margin-top-10">
        <button class="btn btn-primary" data-type="css" data-loading-text="loading..." type="button">压缩样式css文件</button>
    </div>
    <div class="margin-top-10">
        <button class="btn btn-primary" data-type="js" data-loading-text="loading..." type="button">压缩渲染js文件</button>
    </div>
</div>
<script type="text/javascript">
$(function(){
	SITE.init();
})
</script>
<?php $this->load('Common.baseFooter');?>