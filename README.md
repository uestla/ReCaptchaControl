reCAPTCHA for Nette Framework
=============================

* Official documentation: https://developers.google.com/recaptcha/
* Forum (CZE): http://forum.nette.org/cs/21770-nova-recaptcha-pro-formulare


Installation
------------

```
composer require "uestla/recaptcha-control" 2.*
```


Usage
-----

**config.neon**

```
extensions:
	reCaptcha: ReCaptchaControl\ReCaptchaExtension

reCaptcha:
	siteKey: '<your_site_key>'
	secretKey: '<your_secret_key>'
	methodName: 'addReCaptcha' # optional
```


**Form**

```php
$form->addReCaptcha('captcha')
		->addRule($form::VALID, 'Incorrect text code.');
```


**Template**

Load the API script:

```html
<script src="https://www.google.com/recaptcha/api.js?render=explicit"></script>
```

Render reCAPTCHA(s) after page has loaded

```javascript
$(function () {
	$('.g-recaptcha').each(function () {
		var el = $(this);
		grecaptcha.render(el[0], {
			sitekey: el.attr('data-sitekey')
		});
	});
});
```

And that's it!
