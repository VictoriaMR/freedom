var CHAT = {
	init: function(data)
	{
		this.data = data;
		this.key = localStorage.getItem('group_key');
		var res = API.post(URI+'index/checktoken', {});
		if (res.code == 200) {
	    	localStorage.setItem('access_token', res.data.access_token);
	    	this.startContact();
	    } else if(res.code == 301) {
	    	this.startContact();
	    } else {
	    	this.error();
	    }
	},
	error: function()
	{
		$('#content').html('<div class="middle"><img src="'+this.data.error_img+'"></div>');
	},
	startContact: function()
	{
		if (!this.key) this.error();
		var res = API.post(URI+'chat/getUserInfo', {});
		this.user_info = res.data;
		res = API.post(URI+'chat/create', {key: this.key});
		if (res.code == 200) {
			$('#content').html('<div class="content container bg-f"></div>\
				<div class="footer bg-f8">\
					<div class="container flex">\
						<div class="item">\
							<i class="iconfont icon-jianpan font-30 keyboard-btn"></i>\
						</div>\
						<div class="item input-item">\
							<div class="container-10 margin-right-10">\
								<input type="input" name="value" class="input" maxlength="100">\
							</div>\
						</div>\
						<div class="item buttom-item margin-right-10">\
							<span href="javascript:;" class="btn btn-green send-btn">发送</span>\
						</div>\
						<div class="item">\
							<i class="iconfont icon-add font-30 other-btn"></i>\
						</div>\
					</div>\
				</div>');
			var html = this.createHtml(res.data);
			$('#content .content').html(html);
			this.toBottom();
			this.scroller();
			this.bindInit();
			this.startConnect();
		}
	},
	bindInit: function()
	{
		var _this = this;
		$('#content').on('click', '.send-btn', function(){
			var val = $(this).parents('.footer').find('input').val();
			if (!val) {
				$(this).parents('.footer').find('input').focus();
				return false;
			}
			$(this).parents('.footer').find('input').val('');
			_this.user_info['content'] = val;
			var html = _this.sendMessage(_this.user_info, true);
			html = $(html);
			$('#content .content').append(html);
			_this.toBottom();
			var res = API.post(URI+'chat/send', {key: _this.key, content: val});
			if (res.code == 200) {
				html.find('.send-loading').remove();
			} else {
				html.find('.send-loading img').src(_this.data.send_err_img);
			}
		});
	},
	startConnect: function()
	{
		var _this = this;
		ws = new WebSocket('ws://127.0.0.1:8282', [API.header('access_token')]);
		ws.onopen = function(evt) { 
		};

		ws.onmessage = function(e){
		    // json数据转换成js对象
			var data = eval('(' +e.data + ')');
		    var type = data.type || '';
		    switch(type){
		        case 'init':
		            API.post(URI+'chat/bind', {client_id: data.client_id, key: _this.key});
		            break;
		        case 'message':
		        	var html = _this.sendMessage(data);
		        	$('#content .content').append(html);
					_this.toBottom();
		        	break;
		    }
		};
	},
	createHtml: function(data)
	{
		var html = '';
		for (var i in data) {
			if (data[i].tips) {
				html += '<div class="tips">\
				            <span>'+data[i].tips+'</span>\
				        </div>';
			}
			if (data[i].is_self) {
				html += this.sendMessage(data[i]);
			} else {
				html += '<div class="row flex other">\
				        	<div class="table avatar">\
					        	<div class="table-cell">\
					        		<img src="'+data[i].avatar+'">\
					        	</div>\
				        	</div>\
				        	<div class="content-box">\
						        <div class="name '+(data[i].is_special ? 'color-red': '')+'">'+data[i].nickname+'</div>\
						        <div class="bubble you">'+data[i].content+'</div>\
				        	</div>\
				        </div>';
			}
		}
		return html;
	},
	sendMessage: function(data, loading)
	{
		html = '<div class="row flex self">\
		        	<div class="content-box">\
				        <div class="name text-right '+(data.is_special ? 'color-red': '')+'">'+data.nickname+'</div>\
				        <div class="bubble me">'+data.content;
				        if (loading) {
				       		html += '<div class="send-loading loading"><img src="'+this.data.loading_img+'"></div>';
				       	}
				       	html += '</div>';
		  html += '</div>\
		        	<div class="table avatar">\
			        	<div class="table-cell">\
			        		<img src="'+data.avatar+'">\
			        	</div>\
		        	</div>\
				</div>';
		return html;
	},
	toBottom: function()
	{
		$('#content .content').scrollTop($('#content .content').prop('scrollHeight'));
		this.end_status = 1;
	},
	scroller: function()
	{
		var _this = this;
		var height = $('#content .content').prop('scrollHeight');
		$('#content .content').on('scroll', function() {
			if (height - $('#content .content').scrollTop() > 100) {
				_this.end_status = 0;
			} else {
				_this.end_status = 1;
			}
		});
	}
};