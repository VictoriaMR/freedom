var SITE = {
	init: function()
	{
		$('.button-update .btn').on('click', function(){
			var _this = $(this);
			_this.button('loading');
			var type = _this.data('type');
			API.post(URI+'set/site', {opt: 'compress', type: type}, function(res) {
				console.log(res)
			})
		});
	},
};