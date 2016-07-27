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
	});
});
