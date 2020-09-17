<?php $this->load('Common.baseHeader');?>
<div class="container-fluid">
    <div class="sel-box">
        <form action="<?php echo url('adminer');?>" method="get" class="form-inline">
            <div class="col-md-12 padding-left-0">
                <div class="form-group operator_input">
                    <input type="text" class="form-control " name="keyword" value="<?php echo $keyword;?>" placeholder="名称/昵称/手机" style="width:138px;" autocomplete="off">
                </div>
                <div class="form-group margin-right-6">
                    <select class="form-control" name="status" style="width:138px;">
                        <option value="">请选择状态</option>
                        <option value="0" <?php if($status!=='' && $status==0){echo 'selected';}?>>停用</option>
                        <option value="1" <?php if($status==1){echo 'selected';}?>>启用</option>
                    </select>
                </div>
                <div class="form-group margin-right-6">
                    <button class="btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-search"></i> 查询</button>
                </div>
                <div class="form-group right">
                    <button class="btn btn-success add-btn btn-sm" type="button"><i class="glyphicon glyphicon-plus"></i> 添加人员</button>
                </div>
            </div>
        </form>
    </div>
    <div class="clear"></div>
    <div class="margin-top-10">
        <table class="table table-hover table-middle table-border-bottom font-14">
            <tr>
                <th class="col-md-1">人员ID</th>
                <th class="col-md-1">名称/昵称</th>
                <th class="col-md-1">手机号码</th>
                <th class="col-md-1">状态</th>
                <th class="col-md-2">权限</th>
                <th class="col-md-1">时间</th>
                <th class="col-md-2">操作</th>
            </tr>
            <?php if (!empty($list)) { ?>
            <?php foreach ($list as $key=>$value) { ?>
            <tr>
                <td class="col-md-1"><?php echo $value['mem_id'];?></td>
                <td class="col-md-1">
                    <a href="javascript:;" class="avatar left"><img src="<?php echo $value['avatar'];?>"></a>
                    <span class="block left margin-left-4"><?php echo $value['name'];?><br /><?php echo $value['nickname'];?></span>
                </td>
                <td class="col-md-1"><?php echo $value['mobile'];?></td>
                <td class="col-md-1">
                    <?php if($value['status']){?>
                        <div class="switch_botton status" data-status="1"><div class="switch_status on"></div></div>
                    <?php }else{ ?>
                        <div class="switch_botton status" data-status="0"><div class="switch_status off"></div></div>
                    <?php } ?>
                </td>
                <td class="col-md-2"><?php echo $value['rule_text'];?></td>
                <td class="col-md-1"><?php echo $value['create_at'];?></td>
                <td class="col-md-2">
                    <button class="btn btn-primary btn-sm modify" type="button" ><i class="glyphicon glyphicon-edit"></i> 修改</button>
                    <button class="btn btn-danger btn-sm delete" type="button" ><i class="glyphicon glyphicon-trash"></i>  删除</button>
                </td>
            </tr>
        	<?php } ?>
            <?php } else { ?>
            <tr>
            	<td colspan="8"><div class="text-center orange">暂无数据</div></td>
            </tr>
        	<?php } ?>
        </table>
    </div>
    <?php echo $paginator;?>
</div>
<style type="text/css">
.avatar {
    width: 50px;
    height: 50px;
    display: block;
    border-radius: 50%;
    overflow: hidden;
}
</style>

<div id="dealbox" style="display: none;">
    <form class="form-horizontal" method="post" action="<?php echo url('Admin.UserGroup');?>">
        <button type="button" class="close" aria-hidden="true">&times;</button>
        <h3 style="margin-top: 0px;">用户管理</h3>
        <input type="hidden" id="user_id" name="user_id" value="0">
        <input type="hidden" id="user_operation" name="opn" value="0">
        <div class="input-group">
            <div class="input-group-addon"><span>用户ID：</span></div>
            <input type="text" class="form-control" id="in_user_id" name="uid">
        </div>
        <div class="input-group">
            <div class="input-group-addon"><span>姓名：</span></div>
            <input type="text" class="form-control" id="user_name" name="name">
        </div>
        <button type="submit" class="btn btn-primary btn-lg btn-block" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Processing">确认</button>
    </form>
</div>
<?php $this->load('Common.baseFooter');?>