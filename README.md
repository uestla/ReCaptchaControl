reCAPTCHA for Nette Framework
=============================

Forum (CZE): http://forum.nette.org/cs/21770-nova-recaptcha-pro-formulare


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


**Template**

It is necessary to load the reCAPTCHA API file. I would highly recommend you to load it with `onload` parameter
since you may want to have more than just one reCAPTCHA on your page (in multiple forms). For more info please
see [official documentation](https://developers.google.com/recaptcha/docs/display#config).

```html
<script src="https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoad&amp;render=explicit"></script>
```

Now it is perfectly simple to render all reCAPTCHA's in a single loop:

```javascript
// this example uses jQuery
function onRecaptchaLoad() {
	$('.g-recaptcha').each(function () {
		var el = $(this);
		grecaptcha.render(el[0], {
			sitekey: el.attr('data-sitekey')
		});
	});
}
```


**Form**

```php
$form->addReCaptcha('captcha')
		->addRule(Form::VALID, 'Incorrect text code.');
```

And that's it!
