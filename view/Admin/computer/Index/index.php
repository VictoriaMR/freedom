<?php $this->load('Common.baseHeader');?>
<div id="content" class="flex">
	<table width="100%" border="0" height="100%">
		<tr>
			<td width="180" height="100%" valign="top" style="width: 40px;">
				<div id="left">
					<div id="user-info" class="item">
						<div class="left avator" data-title="<?php echo $info['nickname'];?>">
							<img src="<?php echo $info['avatar'] ?? '';?>" alt="">
						</div>
						<div class="left name color-white open" style="display: none;">
							<div class="ellipsis-1">
								<span class="font-14 color-f"><?php echo $info['nickname'] ?? '';?></span>
								<a class="right" href="<?php echo url('login/logout');?>">
									<img src="<?php echo url('image/computer/icon/exit.png');?>">
								</a>
							</div>
							<div class="ellipsis-1 color-f"><?php echo $info['mobile'] ?? '';?></div>
						</div>
						<div class="clear"></div>
					</div>
					<div id="controller-list" class="relative">
						<div id="left-one" class="left width-100">
							<div class="item toggle close-left" data-title="菜单切换开关">
								<img src="<?php echo url('image/computer/icon/task.png');?>">
							</div>
							<?php if (!empty($list)) {?>
							<?php foreach ($list as $value) { ?>
							<div class="feature item" data-feature-id="<?php echo $value['con_id'];?>" data-title="<?php echo $value['name'];?>" data-url="<?php echo $value['url'];?>">
					            <img src="<?php echo $value['icon_url'];?>">
					            <p class="open" style="display: none;"><?php echo $value['name'];?></p>
					        </div>
					        <?php } ?>
					    	<?php } ?>
						</div>
					</div>
				</div>
			</td>
			<td valign="top" id="iframe-content"></td>
		</tr>
	</table>
</div>
<script type="text/javascript">
$(document).ready(function(){
	INDEX.init();
});
</script>
<?php $this->load('Common.baseFooter');?>