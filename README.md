reCAPTCHA for Nette Framework
=============================

Forum: http://forum.nette.org/cs/11914-recaptcha-pro-nette-forms


Usage
-----

**config.neon**

```
extensions:
	reCaptcha: ReCaptchaControl\ReCaptchaExtension

reCaptcha:
	siteKey: '<your_site_key>'
	secretKey: '<your_secret_key>'
	methodName: 'addRecaptcha' # optional
```


**Template**

It is necessary to put this javascript before the end of your `<head />` tag:

```html
<script src="https://www.google.com/recaptcha/api.js"></script>
```


**Form**

```php
$form->addReCaptcha('captcha')
		->addRule(Form::VALID, 'Incorrect text code.');
```

And that's it!
