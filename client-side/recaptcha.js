// load this after you've loaded jQuery

;(function (window, $) {

	// register callback
	var callback = 'g_ReCaptchaOnLoad';

	window[callback] = function () {
		$('.g-recaptcha').each(function () {
			var el = $(this);
			grecaptcha.render(el[0], {
				sitekey: el.attr('data-sitekey')
			});
		});
	};

	// load reCAPTCHA api.js
	$('script:first').before($('<script>', {
		type: 'text/javascript',
		async: true,
		src: 'https://www.google.com/recaptcha/api.js?onload=' + callback + '&render=explicit'
	}));

})(window, window.jQuery);
