<?php $this->load('Common.baseHeader');?>
<div class="container-fluid">
    <div class="sel-box">
        <form action="<?php echo url('adminer');?>" method="get" class="form-inline">
            <div class="col-md-12">
                <div class="form-group operator_input">
                    <input type="text" class="form-control " name="keyword" value="<?php echo $keyword;?>" placeholder="名称/昵称/手机" style="width:138px;">
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
    <div id="showtable">
        <table class="table table-hover table-middle">
            <tr>
                <th>人员ID</th>
                <th>姓名</th>
                <th>用户组</th>
                <th>加入时间</th>
                <th>操作</th>
            </tr>
            <?php if (!empty($list)) { ?>
            <?php foreach ($list as $key=>$val){ ?>
            <tr 
                
                
            >
                
                <td>
                    <button class="btn btn-primary btn-sm modify" type="button" ><i class="fa fa-edit"></i> 修改</button>
                    <button class="btn btn-danger btn-sm delete" type="button" ><i class="fa fa-trash"></i>  删除</button>
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