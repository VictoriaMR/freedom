<?php $this->load('Common.header');?>
<div id="message">
	<div class="middle">
		<img class="loading" style="width: 0.50rem;height: 0.50rem;" src="<?php echo url('image/loading.png');?>">
	</div>
	<div class="text-center margin-top-10">
		<span>登录中...</span>
	</div>
</div>
<script type="text/javascript">
$(function(){
	<?php if (!empty($key)) { ?>
	localStorage.setItem('group_key', '<?php echo $key;?>');
	<?php } ?>
	var code = '<?php echo iget('code');?>';
	INDEX.init(code, '<?php echo url('image/error.png');?>');
});
</script>
<?php $this->load('Common.footer');?>