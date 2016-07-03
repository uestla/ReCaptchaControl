reCAPTCHA for Nette Framework
=============================

* Official documentation: https://developers.google.com/recaptcha/
* Forum (CZE): http://forum.nette.org/cs/21770-nova-recaptcha-pro-formulare


Installation
------------

```
composer require uestla/recaptcha-control
```

For frontend javascript that activates reCAPTCHA(s), use

```
bower install
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
$form->addReCaptcha('captcha', NULL, "Please prove you're not a robot.");
```


**Template**

The most robust way to render reCAPTCHA is to do it by hand. Take a look
at [recaptcha.js](assets/recaptcha.js) where this is done using native
javascript DOM manipulation supported by all modern browsers.

And that's it!
