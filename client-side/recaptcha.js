// load https://www.google.com/recaptcha/api.js?onload=g_OnReCaptchaLoad&render=explicit

function g_OnReCaptchaLoad() {
	$('.g-recaptcha').each(function () {
		var el = $(this);
		grecaptcha.render(el[0], {
			sitekey: el.attr('data-sitekey')
		});
	});
}
