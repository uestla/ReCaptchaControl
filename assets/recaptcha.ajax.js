/**
 * This file is part of the ReCaptchaControl package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

;(function (window, $) {

	if (!$ || typeof $.nette === 'undefined') {
		console.error('nette.ajax.js library is required, please load it.');
	}

	// renders all recaptcha elements on page
	var renderAll = function () {
		$('.g-recaptcha').each(function () {
			var el = $(this);
			if (el.children().length) { // already rendered -> skip
				return ;
			}

			grecaptcha.render(this, {}, true);
		});
	};

	// set global onload callback (used in library loading below)
	var callbackName = 'g_onRecaptchaLoad';

	window[callbackName] = function () {
		renderAll();

		$.nette.ext('recaptcha', {
			load: function () {
				renderAll();
			}
		});
	};

	// load official library with explicit rendering
	$('<script />', {
		src: 'https://www.google.com/recaptcha/api.js'
			+ '?onload=' + callbackName
			+ '&render=explicit'
			+ '&hl=' + ($('html').attr('lang') || 'en')

	}).insertBefore('script:first');

})(window, window.jQuery || false);
