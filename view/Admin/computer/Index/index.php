<?php $this->load('Common.baseHeader');?>
<div id="content" class="flex">
	<table width="100%" border="0">
		<tr>
			<td width="180" height="100%" valign="top">
				<div id="left">
					<div id="user-info">
						<div class="left avator">
							<img src="<?php echo $info['avatar'] ?? '';?>" alt="avatar">
						</div>
						<div class="left name color-white">
							<div class="ellipsis-1">
								<span class="font-14"><?php echo $info['name'] ?? '暂无';?></span>
								<a class="right" href="<?php echo url('login/logout');?>"><img src="<?php echo url('image/computer/icon/exit.png');?>"></a>
							</div>
							<div class="ellipsis-1 color-red"><?php echo $info['mobile'] ?? '暂无';?></div>
						</div>
						<div class="clear"></div>
					</div>
					<div id="controller-list" class="relative">
						<div id="left-one" class="left width-100">
							<div class="toggle close" data-title="菜单切换开关">
								<img src="<?php echo url('image/computer/icon/task.png');?>">
							</div>
							<?php if (!empty($list)) { ?>
							<?php foreach ($list as $value) { ?>
							<div id="feature-main-<?php echo $value['con_id'];?>" class="feature" data-feature-id="<?php echo $value['con_id'];?>" data-title="<?php echo $value['name'] ;?>">
					            <img src="<?php echo url('image/computer/icon/feature/'.$value['icon'].'.png');?>">
					            <p><?php echo $value['name'] ;?></p>
					        </div>
					        <?php } ?>
					    	<?php } ?>
						</div>
					</div>
				</div>
			</td>
			<td valign="top" id="iframe-content">
				
			</td>
		</tr>
	</table>
</div>
<script type="text/javascript">
$(document).ready(function(){
	INDEX.init({
		
	});
});
</script>
<?php $this->load('Common.baseFooter');?>