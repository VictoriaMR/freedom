var API = {
	get: function(url, param, callback) {
		var returnData = {};
		var header = this.header();
		$.ajax( {
		    url: url,
		    async: false,
		    type: 'GET',
		    dataType: 'json',
			data: param,
		    headers: header,
		    success: function(data, textStatus, jqXHR){
		        if (data.code == 201) {
		        	localStorage.setItem('access_token', '');
		        	localStorage.setItem('refresh_token', '');
		        	var retryCount = localStorage.getItem('retry_count');
		        	retryCount = retryCount ? retryCount : 0;
		        	if (retryCount < 10) {
			        	localStorage.setItem('retry_count', parseInt(retryCount) + 1);
			        	window.location.href = URI;
		    		}
		        }
		        if (callback) callback(data);
				returnData = data;
		    } ,
		    error: function(jqXHR, textStatus, errorMsg){
		        console.log(errorMsg);
		    }
		});
		return returnData;
	},
	post: function(url, param, callback) {
		var returnData = {};
		var header = this.header();
		$.ajax( {
		    url: url,
		    async: false,
		    type: 'POST',
		    dataType: 'json',
			data: param,
		    headers: header,
		    success: function(data, textStatus, jqXHR){
		    	if (data.code == 201) {
		    		localStorage.setItem('access_token', '');
		        	localStorage.setItem('refresh_token', '');
		    		var retryCount = localStorage.getItem('retry_count');
		    		retryCount = retryCount ? retryCount : 0;
		    		if (retryCount < 10) {
			        	localStorage.setItem('retry_count', parseInt(retryCount) + 1);
			        	window.location.href = URI;
		    		}
		        }
		        if (callback) callback(data);
				returnData = data;
		    } ,
		    error: function(jqXHR, textStatus, errorMsg){
		        console.log(errorMsg);
		    }
		});
		return returnData;
	},
	header: function(name)
	{
		if (name)
			return localStorage.getItem(name);
		var data = [];
		var token = localStorage.getItem('access_token');
		if (token)
			data['Access-Token'] = token;
		token = localStorage.getItem('refresh_token');
		if (token)
			data['Refresh-Token'] = token;
		return data;
	}
};