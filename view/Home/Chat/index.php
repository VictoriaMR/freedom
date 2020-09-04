<?php $this->load('Common.header');?>
<div id='content' class="bg-f">
	<div id="message" class="hide">
		<div class="middle">
			<img class="loading" style="width: 0.68rem;height: 0.68rem;" src="<?php echo url('image/loading.png');?>">
		</div>
		<div class="text-center margin-top-10">
			<span>加载中...</span>
		</div>
	</div>
	<div class="content container"></div>
	<div class="footer bg-f8">
		<div class="container flex">
			<div class="item">
				<i class="iconfont icon-jianpan font-30 keyboard-btn"></i>
			</div>
			<div class="item input-item">
				<div class="container-10 margin-right-10">
					<input type="input" name="value" class="input" maxlength="100">
				</div>
			</div>
			<div class="item buttom-item margin-right-10">
				<span href="javascript:;" class="btn btn-green send-btn">发送</span>
			</div>
			<div class="item">
				<i class="iconfont icon-add font-30 other-btn"></i>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	CHAT.init({
		error_img: '<?php echo url('image/error.png');?>',
	});
});
</script>
<?php $this->load('Common.footer');?>