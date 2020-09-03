var INDEX = {
	init: function(code) {
		if (!code) {
		    var res = API.post(URI+'index/checktoken', {});
		    if (res.code == 200) {
		    	localStorage.setItem('access_token', res.data.access_token);
		    	localStorage.setItem('retry_count', 0);
		        // window.location.href = res.data.url;
		    } else if(res.code == 301) {
		    	localStorage.setItem('retry_count', 0);
		    	// window.location.href = res.data.url;
		    } else {
		    	$('#message').text(res.message);
		    }
		} else {
			var res = API.post(URI+'index/loginByCode', {'code': code});
			if (res.code == 200) {
		    	localStorage.setItem('access_token', res.data.access_token);
		    	localStorage.setItem('refrash_token', res.data.refrash_token);
		        window.location.href = res.data.url;
		    } else {
		    	$('#message').text(res.message);
		    }
		}
	}
};