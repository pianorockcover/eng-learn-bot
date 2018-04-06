/* Classic forms */
$(function() {
	$('.btn-submit').on('click', function(e) {
		e.preventDefault();

		var valls = [];
		var inputs = $(this).parents('form').find('.required-field');
		var noErrors = true;

		$.each(inputs, function(i, input) {
			if ($(input).val()) {
				valls[$(input).attr('name')] = $(input).val();
				$(input).parents('.form-group').addClass('has-success');
				$(input).parents('.form-group').removeClass('has-error');

				return;
			}

			noErrors = false;
			$(input).parents('.form-group').addClass('has-error');
		});

		if (noErrors) {
			$(this).parents('form').submit();
		}
	});

	$('.form-control.required-field').on('change', function(e) {
		if ($(this).val()) {
			$(this).parents('.form-group').addClass('has-success');
			$(this).parents('.form-group').removeClass('has-error');

			return;
		}

		$(this).parents('.form-group').removeClass('has-success');
		$(this).parents('.form-group').addClass('has-error');

		return;
	});
});
