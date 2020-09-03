<?php $this->load('Common.header');?>
<div id="message">
	<div style="text-align: center; margin-top: 40%;">
		<img class="turn" src="<?php echo url('image/loading.png');?>">
	</div>
	<div style="text-align: center;margin-top: 0.2rem;">
		<span style="font-size: 0.16rem;">登录中...</span>
	</div>
	<style type="text/css">
    .turn{
		width:1rem;
		height:1rem;
		background: aqua;
		animation:turn 1s linear infinite;      
    }
    @keyframes turn{
		0%{-webkit-transform:rotate(0deg);}
		25%{-webkit-transform:rotate(90deg);}
		50%{-webkit-transform:rotate(180deg);}
		75%{-webkit-transform:rotate(270deg);}
		100%{-webkit-transform:rotate(360deg);}
    }
	</style>
</div>
<script type="text/javascript">
$(function(){
	var code = '<?php echo iget('code');?>';
	if (!code) {
	    var res = API.post(URI+'index/checktoken', {});
	    if (res.code == 200) {
	    	localStorage.setItem('access_token', res.data.access_token);
	    	localStorage.setItem('retry_count', 0);
	        window.location.href = res.data.url;
	    } else if(res.code == 301) {
	    	localStorage.setItem('retry_count', 0);
	    	window.location.href = res.data.url;
	    } else {
	    	$('#message').text(res.message);
	    }
	} else {
		var res = API.post(URI+'index/loginByCode', {'code': code});
		if (res.code == 200) {
	    	localStorage.setItem('access_token', res.data.access_token);
	    	localStorage.setItem('refrash_token', res.data.refrash_token);
	        window.location.href = res.data.url;
	    } else {
	    	$('#message').text(res.message);
	    }
	}
});
</script>
<?php $this->load('Common.footer');?>