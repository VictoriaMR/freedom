var API = {
	get: function(url, param, callback) {
		var returnData = {};
		$.ajax( {
		    url: url,
		    async: true,
		    type: 'GET',
		    dataType: 'json',
			data: param,
			headers: {
	          "Content-Type": "application/json"
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
		    async: true,
		    type: 'POST',
		    dataType: 'json',
			data: param,
			headers: {
	          "Content-Type": "application/json"
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
var VERIFY = {
	phone: function (phone) {
		var reg = /^1[3456789]\d{9}$/;
		return VERIFY.check(phone, reg);
	},
	email: function (email) {
		var reg = /^([\.a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/;
		return VERIFY.check(email, reg);
	},
	password: function (password) {
		var reg = /^[0-9A-Za-z]{6,}/;
		return VERIFY.check(password, reg);
	},
	code: function(code) {
		var reg = /^\d{4,}/;
		return VERIFY.check(code, reg);
	},
	check: function(input, reg) {
		input = input.trim();
		if (input == '') return false;
		return reg.test(input);
	}
};