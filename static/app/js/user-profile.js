$(function() {
	$('.oauth').authchoice({
		triggerSelector: 'a.auth'
	});
	$(document).on('click', '.oauth a.revoke', function (e) {
		e.preventDefault();
		var that = $(this);
		var source = that.data('source');

		$.post({
			url: '/oauth/revoke',
			data: {source: source},
		}).then(function (data, textStatus, xhr) {
			var filter = $.Deferred();
			if (!data || !data.success) {
				var message = (data && data.message) || undefined;
				filter.reject(message);
			} else {
				filter.resolve(data.data);
			}
			return filter.promise();
		}).done(function (data) {
			location.reload();
		}).fail(function (message) {
			message = (typeof message == 'string' && message) ? message : 'Disconnect failed';
			alert(message);
		})
	}).ajaxSend(function (event, xhr, s) {
		if (s.type.toUpperCase() == 'POST') {
			var csrfToken = $('meta[name=csrf-token]').attr('content');
			xhr.setRequestHeader('X-CSRF-Token', csrfToken);
		}
	});
});
