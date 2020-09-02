var API = {
	get: function(url, param, callback) {
		var returnData = {};
		$.ajaxSetup({
	  		async: false
	  	});
	  	param.token = localStorage.getItem('token');
		$.get(url, param, function(res) {
			if (callback) callback(res);
			else returnData = res;
		}, 'json');
		return returnData;
	},
	post: function(url, param, callback) {
		var returnData = {};
		$.ajaxSetup({
	  		async: false
	  	});
	  	param.token = localStorage.getItem('token');
		$.post(url, param, function(res) {
			if (callback) callback(res);
			else returnData = res;
		}, 'json');
		return returnData;
	},
};