reCAPTCHA for Nette Framework
=============================

* Official documentation: https://developers.google.com/recaptcha/
* Forum (CZE): http://forum.nette.org/cs/21770-nova-recaptcha-pro-formulare


Installation
------------

```
composer require uestla/recaptcha-control
```

Don't forget to include the official JavaScript library:

```html
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
```

Usage
-----

**config.neon**

```
extensions:
	reCaptcha: ReCaptchaControl\DI\Extension

reCaptcha:
	siteKey: '<your_site_key>'
	secretKey: '<your_secret_key>'
	methodName: 'addReCaptcha' # optional
```


**Form**

```php
$form->addReCaptcha('captcha', NULL, "Please prove you're not a robot.");
```

NOTE: The validation rule is set automatically so no `addRule()` is required here.


**Template**

To render the reCAPTCHA element manually in Latte simply use

```latte
<form ...>
	<div n:name="captcha"></div>

	{* or *}

	{input captcha}
</form>
```

And that's it!
