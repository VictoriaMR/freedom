var INDEX = {
	init: function()
	{
		//左侧列表缩进
		$('#left-one .toggle').on('click', function() {
			if ($(this).hasClass('close-left')) {
				$(this).removeClass('close-left');
				$(this).parents('td').css({'width': '180px'});
				$(this).parents('td').find('.open').show();
				localStorage.setItem('toggle', 'open');
			} else {
				$(this).addClass('close-left');
				localStorage.setItem('toggle', 'close-left');
				$(this).parents('td').css({'width': '40px'});
				$(this).parents('td').find('.open').hide();
			}
		});
		//左侧列表显示标题
		$('#left [data-title]').on('mouseover', function(){
			if (!$('#left-one .toggle').hasClass('close-left')) return false;
			var offTop = $(this).position().top;
			var oh = $(this).height();
			var diff = (oh - 24) / 2;
			diff = diff > 0 ? diff : 0;
			$(this).parent().find('.tooltips').remove();
			$(this).parent().append('<div class="tooltips" style="top:'+(parseInt(offTop)+diff)+'px">'+$(this).data('title')+'</div>');
		}).on('mouseleave', function(){
			$(this).parent().find('.tooltips').remove();
		});
		//切换iframe
		$('#left-one .feature').on('click', function(){
			$(this).addClass('selected').siblings().removeClass('selected');
			var url = $(this).data('url');
			var id = $(this).data('feature-id');
			localStorage.setItem('controller', id);
			var html = '<iframe src="'+url+'" frameborder="0" width="100%" height="100%"></iframe>';
			$('#iframe-content').html(html);
		});
		this.frameInit();
	},
	frameInit: function()
	{
		var temp = localStorage.getItem('controller');
		if (temp)
			$('[data-feature-id='+temp+']').trigger('click');
		else
			$('.feature').eq(0).trigger('click');

		temp = localStorage.getItem('toggle');
		if (temp != 'close-left')
			$('#left-one .toggle').trigger('click');

	},
};