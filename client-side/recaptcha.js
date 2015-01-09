// load https://www.google.com/recaptcha/api.js?render=explicit

$(function () {
	$('.g-recaptcha').each(function () {
		var el = $(this);
		grecaptcha.render(el[0], {
			sitekey: el.attr('data-sitekey')
		});
	});
});

