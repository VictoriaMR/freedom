<?php $this->load('Common.header');?>
<div id='content'>
	<div id="message">
		<div class="middle">
			<img class="loading" style="width: 0.68rem;height: 0.68rem;" src="<?php echo url('image/loading.png');?>">
		</div>
		<div class="text-center margin-top-10">
			<span>加载中...</span>
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