<?php $this->load('Common.baseHeader');?>
<?php $count = 0;?>
<div class="container-fluid">
    <div class="bottom15">
        <button class="btn btn-success addroot" type="button"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;添加新系统功能</button>
        <button class="btn btn-info all-open" type="button"><span class="glyphicon glyphicon-list"></span>&nbsp;全部展开</button>
        <button class="btn btn-info all-close" type="button"><span class="glyphicon glyphicon-folder-close"></span>&nbsp;全部折叠</button>
    </div>
    <table class="table table-hover table-middle margin-top-14 table-border-bottom">
        <tr>
            <th class="col-md-1 col-1">ID</th>
            <th class="col-md-2 col-2">系统功能名称</th>
            <th class="col-md-1 col-1">控制器名称</th>
            <th class="col-md-1 col-1">状态</th>
            <th class="col-md-2 col-2">排序</th>
            <th class="col-md-3 col-3">操作</th>
        </tr>
        <?php if (!empty($list)) { ?>
        <?php foreach ($list as $key => $value){?>
            <tr
                data-con_id="<?php echo $value['con_id'];?>"
                data-parent_id="<?php echo $value['parent_id'];?>"
                data-name="<?php echo $value['name'];?>"
                data-name_en="<?php echo $value['name_en'];?>"
                data-status="<?php echo $value['status'];?>"
                data-icon="<?php echo $value['icon'];?>"
                data-sort="<?php echo $value['sort'];?>"
                class="parent"
            >
                <td class="col-md-1 col-1"><?php echo $value['con_id'];?></td>
                <td class="col-md-2 col-2 level1 font-600 font-14">
                    <span class="img">
                        <img src="<?php echo $value['icon_url'];?>">
                    </span>
                    <span><?php echo $value['name'];?></span>
                </td>
                <td class="col-md-1 col-1"><?php echo $value['name_en'];?></td>
                <td class="col-md-1 col-1">
                    <?php if($value['status']){?>
                        <div class="switch_botton status" data-status="1"><div class="switch_status on"></div></div>
                    <?php }else{ ?>
                        <div class="switch_botton status" data-status="0"><div class="switch_status off"></div></div>
                    <?php } ?>
                </td>
                <td class="col-md-2 col-2">
                    <div class="btn-group btn-group-sm">
                        <button class="btn sort" data-sort="start" title="移到开头"><i class="glyphicon glyphicon-arrow-up"></i></button>
                        <button class="btn sort" data-sort="up" title="上移一位"><i class="glyphicon glyphicon-chevron-up"></i></button>
                        <button class="btn sort" data-sort="down" title="下移一位"><i class="glyphicon glyphicon-chevron-down"></i></button>
                        <button class="btn sort" data-sort="end" title="移到结尾"><i class="glyphicon glyphicon-arrow-down"></i></button>
                    </div>
                </td>
                <td class="col-md-3 col-3">
                    <button class="btn btn-success btn-sm addroot" type="button" data-parent_id="<?php echo $value['con_id'];?>"><span class="glyphicon glyphicon-plus"></span>&nbsp;新增</button>
                    <button class="btn btn-primary btn-sm modify" type="button" ><span class="glyphicon glyphicon-edit"></span>&nbsp;修改</button>
                    <button class="btn btn-danger btn-sm delete-btn" type="button" ><span class="glyphicon glyphicon-trash"></span>&nbsp;删除</button>
                </td>
            </tr>
            <?php if (!empty($value['son'])) { ?>
            <?php foreach ($value['son'] as $k => $v) {$count++;?>
            <tr
                data-con_id="<?php echo $v['con_id'];?>"
                data-parent_id="<?php echo $v['parent_id'];?>"
                data-name="<?php echo $v['name'];?>"
                data-name_en="<?php echo $v['name_en'];?>"
                data-status="<?php echo $v['status'];?>"
                data-icon="<?php echo $v['icon'];?>"
                data-sort="<?php echo $v['sort'];?>"
                class="son"
            >
                <td class="col-md-1 col-1"><?php echo $v['con_id'];?></td>
                <td class="col-md-2 col-2 level2">
                    <span><?php echo $v['name'];?></span>
                </td>
                <td class="col-md-1 col-1"><?php echo $v['name_en'];?></td>
                <td class="col-md-1 col-1">
                    <?php if($v['status']){?>
                    <div class="switch_botton status" data-status="1"><div class="switch_status on"></div></div>
                    <?php }else{?>
                    <div class="switch_botton status" data-status="0"><div class="switch_status off"></div></div>
                    <?php }?>
                </td>
                <td class="col-md-2 col-2">
                    <div class="btn-group btn-group-sm">
                        <button class="btn sort" data-sort="start" title="移到开头"><i class="glyphicon glyphicon-arrow-up"></i></button>
                        <button class="btn sort" data-sort="up" title="上移一位"><i class="glyphicon glyphicon-chevron-up"></i></button>
                        <button class="btn sort" data-sort="down" title="下移一位"><i class="glyphicon glyphicon-chevron-down"></i></button>
                        <button class="btn sort" data-sort="end" title="移到结尾"><i class="glyphicon glyphicon-arrow-down"></i></button>
                    </div>
                </td>
                <td class="col-md-3 col-3">
                    <button class="btn btn-primary btn-sm modify" type="button" ><span class="glyphicon glyphicon-edit"></span>&nbsp;修改</button>
                    <button class="btn btn-danger btn-sm delete-btn" type="button" ><span class="glyphicon glyphicon-trash"></span>&nbsp;删除</button>
                </td>
            </tr>
            <?php } ?>
            <?php } ?>
        <?php } ?>
        <?php } else { ?>
        <tr>
            <td colspan="7" class="text-center"><span style="color: orange;">暂无数据</span></td>
        </tr>
        <?php } ?>
    </table>
    <p>合计<?php echo count($list ?? []);?>个功能分组, <?php echo $count;?>个子功能</p>
</div>
<div id="dealbox" style="display: none;">
    <form class="form-horizontal">
        <input type="hidden" class="form-control" name="con_id" value="0">
        <input type="hidden" class="form-control" name="parent_id" value="0">
        <button type="button" class="close" aria-hidden="true">&times;</button>
        <h3 class="margin-bottom-14 font-600 font-16">新增功能</h3>
        <div class="input-group">
            <div class="input-group-addon"><span>系统功能名称：</span></div>
            <input type="text" class="form-control" name="name" required="required" maxlength="30" value="">
        </div>
        <div class="input-group">
            <div class="input-group-addon"><span>控制器名称：</span></div>
            <input type="text" class="form-control" name="name_en" required="required" maxlength="30" value="">
        </div>
        <div class="input-group">
            <div class="input-group-addon"><span>状态：</span></div>
            <div class="form-control">
                <div class="switch_botton status margin-top-6" data-status="1">
                    <div class="switch_status on"></div>
                </div>
                <input type="hidden" name="status" value="1">
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-addon"><span>图标：</span></div>
            <select class="form-control" name="icon">
                <option value="">请选择</option>
                <?php if (!empty($iconList)) { ?>
                <?php foreach ($iconList as $key => $value) { ?>
                <option value="<?php echo $value['name'];?>">
                    <?php echo $value['name'];?>
                </option>
                <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="input-group">
            <div class="input-group-addon"><span>排序：</span></div>
            <input type="text" class="form-control" name="sort" maxlength="2" value="">
        </div>
        <button type="button" class="btn btn-primary btn-lg btn-block save" data-loading-text="loading..">确认</button>
    </form>
</div>
<script type="text/javascript">
$(function(){
    FEATURE.init();
});
</script>
<?php $this->load('Common.baseFooter');?>