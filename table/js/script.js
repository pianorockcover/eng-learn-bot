$(function() {
	$.each($('[data-fill]'), function(i, elem) {
		// console.log($(elem));

		if ($(elem).data('fill').replace(' ', '') != '0.0') {
			console.log(parseFloat($(elem).data('fill')));
			var percent = 331 / 100 * parseFloat($(elem).data('fill'));
			$(elem).css('width', percent + 'px');
		} else {
			$(elem).addClass('no-bg');
		}

	});

});