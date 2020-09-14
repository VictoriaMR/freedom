<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>管理后台</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="shortcut icon" href="favicon.ico">
    <?php foreach (\frame\Html::getCss() as $value) { ?>
    <link rel="stylesheet" type="text/css" href="<?php echo $value;?>">
    <?php }?>
    <?php foreach (\frame\Html::getJs() as $value) { ?>
    <script type="text/javascript" src="<?php echo $value;?>"></script>
    <?php }?>
</head>
<body>
<script type="text/javascript">
var URI = "<?php echo APP_DOMAIN;?>";
</script>
<?php if (!empty($navArr)) { ?>
<div id="header-nav" class="container-fluid margin-top-10">
    <div class="nav" data-id="0"> 
        <span class="left"><?php echo implode(' > ', $navArr);?></span>
        <a title="新窗口中打开" class="extralink" target="_blank" href="">
            <img src="<?php echo url('image/computer/icon/extralink.png');?>">
        </a>
        <a title="刷新当前页面" class="extralink" href="">
            <img src="<?php echo url('image/computer/icon/refresh.png');?>">
        </a>
     </div>
</div>
<?php } ?>
<?php if (!empty($tabs)) { ?>
<div class="container-fluid" style="margin: 15px 0;">
    <ul class="nav nav-tabs common-tabs">
        <?php foreach ($tabs as $key => $val) {?>
        <li <?php if ($val['name_en'] == $func){?>class="active"<?php } ?>>
            <a href="<?php echo url($path.'/'.$key);?>"><?php echo $val['name'];?></a>
        </li>
        <?php } ?>
    </ul>
</div>
<?php } ?>