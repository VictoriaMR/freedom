var FEATURE = {
	init: function() 
	{
		var _this = this;
		_this.sortInit();
		$('#dealbox').offsetCenter();
		$('.modify').on('click', function(event){
			event.stopPropagation();
			_this.initShow($(this).parents('tr').data());
		});
		//弹窗保存
		$('#dealbox button.save').on('click', function(event){
			event.stopPropagation();
	    	var check = true;
	    	$(this).parents('form').find('[required=required]').each(function(){
	    		var val = $(this).val();
	    		if (val == '') {
	    			check = false;
	    			var name = $(this).prev().text();
	    			errorTips('请将'+name.slice(0, -1)+'填写完整');
	    			$(this).focus();
	    			return false;
	    		}
	    	});
	    	if (!check) return false;
	    	$(this).button('loading');
	    	_this.save();
	    	$(this).button('reset');
	    });
	    //状态
	    $('table .switch_botton.status .switch_status').on('click', function(event) {
	    	var _thisobj = $(this);
	    	var con_id = _thisobj.parents('tr').data('con_id');
	    	var status = _thisobj.hasClass('on') ? 0 : 1;
	    	API.post(URI + 'set/index', {con_id: con_id, status: status, opt: 'edit'}, function(res) {
    			if (res.code == 200) {
    				successTips(res.message);
    				switch_status(_thisobj, status);
    				_thisobj.parents('tr').data('status', status);
    				if (status == 0 && _thisobj.parents('tr').hasClass('parent')) {
    					switch_status(_thisobj.parents('tr').nextUntil('.parent').data('status', status).find('.switch_status'), status);
    				}
    			} else {
    				errorTips(res.message);
    			}
    		});
    		event.stopPropagation();
	    });
	    //编辑框开关切换
	    $('#dealbox .switch_status').on('click', function(){
	    	var _thisobj = $(this);
	    	var status = _thisobj.hasClass('on') ? 0 : 1;
	    	switch_status(_thisobj, status);
	    	_thisobj.parents('.form-control').find('input').val(status);
	    });
	    //删除
	    $('.delete-btn').on('click', function(event){
	    	event.stopPropagation();
	    	var _thisobj = $(this);
	    	confirm('确定删除吗?', function(){
	    		var con_id = _thisobj.parents('tr').data('con_id');
	    		API.post(URI+'set/index', { con_id: con_id, opt: 'delete'}, function(res) {
	    			if (res.code == 200) {
	    				successTips(res.message);
	    				window.location.reload();
	    			} else {
	    				errorTips(res.message);
	    			}
	    		});
	    	});
	    });
	    //新增
	    $('.addroot').on('click', function(event) {
	    	event.stopPropagation();
	    	_this.initShow($(this).data());
	    });
	    //点击全部展开/收起
	    $('.all-open').on('click', function(){
	    	$('tr').show();
	    });
	    $('.all-close').on('click', function(){
	    	$('tr.son').hide();
	    });
	    //排序
	    $('.sort-btn .btn').on('click', function(){
	    	var type = $(this).data('sort');
	    	var className = $(this).attr('className');
	    	if (type == 'down') {
	    		$(this).parents('tr').nextUntil('.'+className).after($(this).parents('tr'));
	    	}

	    	_this.sortInit();
	    });
	},
	sortInit:function()
	{
		$('.sort-btn .btn').attr('disabled', false);
		$('tr.parent').eq(0).find('[data-sort="start"], [data-sort="up"]').attr('disabled', 'disabled');
		$('tr.parent:last, tr:last').find('[data-sort="end"], [data-sort="down"]').attr('disabled', 'disabled');
		$('tr.parent').each(function(){
			if ($(this).prev().hasClass('son')) {
				$(this).prev().find('[data-sort="end"], [data-sort="down"]').attr('disabled', 'disabled');
			}

			if ($(this).next().hasClass('son')) {
				$(this).next().find('[data-sort="start"], [data-sort="up"]').attr('disabled', 'disabled');
			}
		})
	},
	initShow:function (data)
	{	
		$('#dealbox .form-control').each(function(){
			var name = $(this).attr('name');
			if (name != 'opt') {
				if (typeof data[name] == 'undefined') {
					$('#dealbox [name="'+name+'"]').val('');
				} else {
					$('#dealbox [name="'+name+'"]').val(data[name]);
				}
			}
		});
		if (typeof data.status != 'undefined') {
			if (data.status == 0) {
				$('#dealbox [name="status"]').val(0).parents('.input-group').find('.switch_status').removeClass('on').addClass('off');
			} else {
				$('#dealbox [name="status"]').val(1).parents('.input-group').find('.switch_status').removeClass('off').addClass('on');
			}
		}
		if (data.parent_id) {
			$('#dealbox [name="icon"]').parents('.input-group').hide();
		} else {
			$('#dealbox [name="icon"]').parents('.input-group').show();
		}
		$('#dealbox').show();
	},
	save: function ()
	{
    	API.post(URI + 'set/index', $('#dealbox form').serializeArray(), function(res){
    		if (res.code == 200) {
    			successTips(res.message);
    			window.location.reload();
    		} else {
    			errorTips(res.message);
    		}
    	});
	}
};