var LOGIN = {
	init: function()
	{
		var _this = this;
		_this.$ele = $('#verify-wrap');
		_this.slideBtn = $('#verify-wrap .drag-btn');
		_this.slideProEle = $('#verify-wrap .dragProgress');
        _this.slideSucMsgEle = $('#verify-wrap .sucMsg');
        _this.slideFixTipsEle = $('#verify-wrap .fixTips');
		_this.maxSlideWid = _this.calSlideWidth();
		_this._mousedown();
		_this.formInit();
	},
	formInit: function()
	{
		$('#login-btn').on('click', function() {
			var msg = '';
			$('#login-error').hide();
			var tempobj = $(this);
			tempobj.parent('form').find('input:visible').each(function(){
				var name = $(this).attr('name');
				if (!VERIFY[name]($(this).val())) {
					switch (name) {
						case 'phone':
							msg = '手机号码格式不正确';
							break;
						case 'password':
							msg = '密码格式不正确';
							break;
						default:
							msg = '输入错误';
							break;
					}
					return false;
				}
			});

			if (msg != '') {
				$('#login-error').show().find('#login-error-msg').text(msg);
				return false;
			}
			if (!$('#verify-wrap .drag-btn').hasClass('suc-drag-btn')) {
				$('#login-error').show().find('#login-error-msg').text('拖动模块验证');
				return false;
			}
			// tempobj.button('loading');
			API.post(URI+'login/login', tempobj.parent('form').serializeArray(), function(res) {
				if (res.code == 200) {
					window.location.href = URI;
				} else {
					$('#login-error').show().find('#login-error-msg').text(res.message);
				}
				tempobj.button('reset');
			});
		});

		document.onkeydown = function(e)
		{
	        var ev = document.all ? window.event : e;
	        if(ev.keyCode == 13) {
	            $('#login-btn').trigger('click');
	        }
	    }
	},
	_mousedown: function()
	{
    	var _this = this;
    	var ifThisMousedown = false;
    	_this.slideBtn.on('mousedown', function(e){
    		e.preventDefault();
    		if(_this.slideFinishState || _this.isAnimated())
				return false;

    		var distenceX = e.pageX;
    		ifThisMousedown = true;
    		$(document).mousemove(function(e){
    			if(!ifThisMousedown)
                    return false;

				var curX = e.pageX - distenceX;
				if(curX >= _this.maxSlideWid){
					_this.setDragBtnSty(_this.maxSlideWid);
					_this.setDragProgressSty(_this.maxSlideWid);
					_this.cancelMouseMove();
					_this.slideFinishState = true;
					_this.successSty();
				}else if(curX <= 0){
					_this.setDragBtnSty('0');
					_this.setDragProgressSty('0');
				}else{
					_this.setDragBtnSty(curX);
					_this.setDragProgressSty(curX);
				}
			})
			$(document).mouseup(function(){
				if(!ifThisMousedown){
                    return false;
                }
                ifThisMousedown = false;
                if(_this.slideFinishState){
					_this.cancelMouseMove();
					return false;
				}else{
					_this.failAnimate();
			      	_this.cancelMouseMove();
				}
		   });
    	});	
    },
    isAnimated:function()
    {
    	//判断 是否动画状态
    	return this.slideBtn.is(':animated');
    },
    getDragBtnWid:function()
    {
    	//获取滑块的宽度，
    	return parseInt(this.slideBtn.width());
    },
    getDragWrapWid:function()
    {
    	//获取本容器的的宽度，以防万一
    	return parseFloat(this.$ele.outerWidth());
    },
    calSlideWidth:function()
    {
    	return this.getDragWrapWid() - this.getDragBtnWid();
    },
    setDragBtnSty:function(left)
    {
    	this.slideBtn.css({
			'left':left
		});
    },
    setDragProgressSty:function(wid)
    {
    	this.slideProEle.css({
			'width':wid
		});
    },
    cancelMouseMove:function()
    {
    	$(document).off('mousemove');
    },
    successSty:function()
    {
    	this.slideSucMsgEle.show();
		this.slideBtn.addClass('suc-drag-btn');
		this.slideFixTipsEle.hide();
    },
    failAnimate:function()
    {
    	this.slideBtn.animate({
			'left':'-1px'
		},200);
		this.slideProEle.animate({
			'width':0
		},200)
    },
};