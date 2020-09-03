var API = {
	get: function(url, param, callback) {
		var returnData = {};
		$.ajax( {
		    url: url,
		    async: false,
		    type: 'GET',
		    dataType: 'json',
			data: param,
		    headers: {
	  			'Access-Token': localStorage.getItem('access_token'), 
	  			'Refrash-Token': localStorage.getItem('refresh_token')
	  		},
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
		$.ajax( {
		    url: url,
		    async: false,
		    type: 'POST',
		    dataType: 'json',
			data: param,
		    headers: {
	  			'Access-Token': localStorage.getItem('access_token'), 
	  			'Refrash-Token': localStorage.getItem('refrash_token')
	  		},
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
};