(function (b) {
	b.fn.WsAjax = function (a) {
		a = b.extend({
			type: "POST", contentType: "application/x-www-form-urlencoded", url: "",
			data: {}, dataType: "json", crossDomain: !1, beforeSend: function () { },
			cache: !1, timeout: 3E4, success: function () { },
			error: function () { }
		}, a);
		b.ajax({
			type: a.type,
			contentType: a.contentType,
			url: a.url,
			data: a.data,
			dataType: a.dataType,
			beforeSend:
			a.beforeSend,
			cache: a.cache,
			timeout:
			a.timeout,
			success: a.success,
			error: a.error
		})
	}
})(jQuery);
