<?php $this->load('Common.header');?>
<?php if (empty($message)) { ?>
<div>...跳转中</div>
<script type="text/javascript">
var URI = "<?php echo env('APP_DOMAIN');?>";
$(function(){
    var res = API.post(URI+'index/checktoken', {});
    if (res.code != 200) {
        window.location.href = res.data.url;
    }
})
</script>
<?php } else { ?>
<div><?php echo $message;?>12312</div>
<?php } ?>
<?php $this->load('Common.footer');?>