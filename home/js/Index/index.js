var INDEX = {
	init: function(code, error_img)
	{
		if (!code) {
		    var res = API.post(URI+'index/checktoken', {});
		    if (res.code == 200) {
		    	localStorage.setItem('access_token', res.data.access_token);
		    	localStorage.setItem('retry_count', 0);
		        window.location.href = res.data.url;
		    } else if(res.code == 301) {
		    	localStorage.setItem('retry_count', 0);
		    	window.location.href = res.data.url;
		    } else {
		    	this.error(error_img);
		    }
		} else {
			var res = API.post(URI+'index/loginByCode', {'code': code});
			if (res.code == 200) {
		    	localStorage.setItem('access_token', res.data.access_token);
		    	localStorage.setItem('refrash_token', res.data.refrash_token);
		        window.location.href = res.data.url;
		    } else {
		    	this.error(error_img);
		    }
		}
	},
	error: function(error_img)
	{
		$('#message').html('<div class="middle"><img src="'+error_img+'"></div>');
	},
};