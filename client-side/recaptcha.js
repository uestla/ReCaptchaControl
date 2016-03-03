/**
 * This file is part of the ReCaptchaControl package
 *
 * Copyright (c) 2016 Petr Kessler (http://kesspess.1991.cz)
 *
 * @license  MIT
 * @link     https://github.com/uestla/ReCaptchaControl
 */

;(function (window) {

	var document = window.document;
	var callback = 'g_ReCaptchaOnLoad';

	window[callback] = function () {
		[].forEach.call(document.querySelectorAll('.g-recaptcha'), function (recaptcha) {
			if (recaptcha.children.length) {
				return ;
			}

			grecaptcha.render(recaptcha, {
				sitekey: recaptcha.getAttribute('data-sitekey')
			});
		});
	};

	var script = document.createElement('script');
	script.async = true;
	script.type = 'text/javascript';
	script.src = 'https://www.google.com/recaptcha/api.js?onload=' + callback + '&render=explicit';

	var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(script, s);

})(window);
