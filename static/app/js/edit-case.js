$(function() {
	$(document).on('click', '.sticker', function(e) {
		var color = $('input[name=color]:checked').val();
		var that = $(this);
		var target = that;
		if (e.altKey) {
			target = that.parent().find('.sticker');
		}
		if (e.shiftKey) {
			target = that.parent().parent().find('.sticker');
		}
		target.data('sticker', color);
		target.attr('data-sticker', color);
	}).on('click', '#save', function(e) {
		var faces = 'urfdlb'.split('');
		var stickerStr = '';
		for (var i = 0; i < faces.length; i++) {
			var face = faces[i];
			var stickers = $('[data-face=' + face + ']').find('.sticker');
			stickers.each(function(idx, sticker) {
				stickerStr += $(sticker).data('sticker');
			});
		};
		var caseId = $('#case').data('caseId');
		var params = {
				id: caseId,
				stickers: stickerStr
		};
		$.post({
			url: '/cases/update',
			data: params,
			beforeSend: function() {
				$('#save').attr('disabled', true);
			}
		}).then(function(data, textStatus, xhr) {
			var filter = $.Deferred();
			if (!data || !data.success) {
				var message = (data && data.message) || undefined;
				filter.reject(message);
			} else {
				var message = data.message || undefined;
				filter.resolve(data.data, message);
			}
			return filter.promise();
		}).done(function(data, message) {
			var id = data.id;
			if (id) {
				$('#case').data('caseId', id).attr('data-case-id', id);
				history.replaceState(null, null, '?id=' + id);
			}
			$('img').each(function() {
				var src = this.src;
				this.src = src.replace(/fd=[urfdlbn]+/, 'fd=' + stickerStr);
			});
			message = message || 'Success';
			var errMsg = $('#err-msg');
			errMsg.find('span').text(message);
			errMsg.removeClass('alert-success alert-danger')
				.addClass('alert-success')
				.slideDown();
		}).fail(function(message) {
			message = (typeof message == 'string' && message) ? message : 'Save failed!';
			var errMsg = $('#err-msg');
			errMsg.find('span').text(message);
			errMsg.removeClass('alert-success alert-danger')
				.addClass('alert-danger')
				.slideDown();
		}).always(function() {
			$('#save').removeAttr('disabled');
		});
	}).on('click', '#err-msg button', function() {
		$('#err-msg').slideUp();
	}).ajaxSend(function(event, xhr, s) {
		if (s.type.toUpperCase() == 'POST') {
			var csrfToken = $('meta[name=csrf-token]').attr('content');
			xhr.setRequestHeader('X-CSRF-Token', csrfToken);
		}
	});
});
