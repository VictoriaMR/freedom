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
		var res = API.post(URI+'chat/create', {key: this.key});
		if (res.code == 200) {
			var html = this.createHtml(res.data);
			$('#content .content').html(html);
			this.toBottom();
			this.scroller();
		}
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
	sendMessage: function(data)
	{
		return '<div class="row flex self">\
		        	<div class="content-box">\
				        <div class="name text-right '+(data.is_special ? 'color-red': '')+'">'+data.nickname+'</div>\
				        <div class="bubble me">'+data.content+'</div>\
		        	</div>\
		        	<div class="table avatar">\
			        	<div class="table-cell">\
			        		<img src="'+data.avatar+'">\
			        	</div>\
		        	</div>\
		        </div>';
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
			console.log($('#content .content').scrollTop())
			if (height - $('#content .content').scrollTop() > 100) {
				_this.end_status = 0;
			} else {
				_this.end_status = 1;
			}
		});
	}
};