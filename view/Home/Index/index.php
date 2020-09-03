<?php $this->load('Common.header');?>
<div id="message">
	<div style="text-align: center; margin-top: calc(50vh - 0.68rem);">
		<img class="turn" src="<?php echo url('image/loading.png');?>">
	</div>
	<div style="text-align: center;margin-top: 0.2rem;">
		<span style="font-size: 0.16rem;">登录中...</span>
	</div>
</div>
<script type="text/javascript">
$(function(){
	var code = '<?php echo iget('code');?>';
	INDEX.init(code);
});
</script>
<?php $this->load('Common.footer');?>