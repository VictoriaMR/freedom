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
	  			'Refrash-Token': localStorage.getItem('refrash_token')
	  		},
		    success: function(data, textStatus, jqXHR){
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