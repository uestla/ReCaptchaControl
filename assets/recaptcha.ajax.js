/**
 * This file is part of the ReCaptchaControl package
 * AJAX with invisible recaptcha support thanks to https://gist.github.com/tvaliasek/83a166e5c773cece3b5c7d49a964e030
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

;
(function (window, $, useInvisible) {

	if (!$ || typeof $.nette === 'undefined') {
		console.error('nette.ajax.js library is required, please load it.');
	}

	// map of <htmlID>: <widget/client ID>
	var clientIDs = {};

	// renders all recaptcha elements on page
	var renderAll = function () {
		$('.g-recaptcha').each(function () {
			var el = $(this);
			if (el.children().length) { // already rendered -> skip
				return;
			}

			var params = {};
			if (useInvisible) {
				// https://developers.google.com/recaptcha/docs/invisible#render_param
				params = {
					size: 'invisible',
					// badge: 'inline',
					callback: function (token) {
//						el.closest('form').off('submit.recaptcha').trigger('submit');
						// submit form using first button
						el.closest('form').off('submit.recaptcha').find('input[type="submit"]:first').trigger('click');
					}
				};
			}
			clientIDs[this.id] = grecaptcha.render(this, params, true);
		});

		if (useInvisible) {
			$(function () {
				$('form').on('submit.recaptcha', function (event) {
					event.preventDefault();

					var form = $(this);
					if (Nette.validateForm(this, true)) {
						// execute only reCAPTCHAs in submitted form
						$('.g-recaptcha', form).each(function () {
							grecaptcha.execute(clientIDs[this.id]);
						});
					}
				});
			});
		}
	};

	// set global onload callback (used in library loading below)
	var callbackName = 'g_onRecaptchaLoad';

	window[callbackName] = function () {
		renderAll();

		$.nette.ext('recaptcha', {
			load: function () {
				renderAll();
			},
			before: function (jqXHR, settings) {
				if ((settings.hasOwnProperty('data')) && (settings.data instanceof FormData)) {
					var formData = settings.data;
					var formDataKeys = formData.keys();
					var iterationDone = false;
					var formDataKeysArray = [];
					while (!iterationDone) {
						try {
							var keyItem = formDataKeys.next();
							iterationDone = keyItem.done;
							if (!iterationDone) {
								formDataKeysArray.push(keyItem.value);
							}
						} catch (error) {
							iterationDone = true
						}
					}
					for (var index in formDataKeysArray) {
						if (formDataKeysArray[index] === 'g-recaptcha-response') {
							var formDataValue = formData.get(formDataKeysArray[index]);
							if (formDataValue === '' || formDataValue === undefined) {
								var formEl = settings.nette.form;
								formEl.trigger('submit.recaptcha')
								return false;
							}
						}
					}
				}
			// },
			// complete: function () {
			// 	renderAll();
			}
		});
	};

	// load official library with explicit rendering
	$('<script />', {
		src: 'https://www.google.com/recaptcha/api.js'
				+ '?onload=' + callbackName
				+ '&render=explicit'
				+ '&hl=' + ($('html').attr('lang') || 'en'),
		async: true,
		defer: true

	}).insertBefore('script:first');

})(window, window.jQuery || false, useInvisible = true);
