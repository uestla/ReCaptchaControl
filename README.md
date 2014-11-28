reCAPTCHA for Nette Framework
=============================

Forum: http://forum.nette.org/cs/11914-recaptcha-pro-nette-forms


Usage
-----

**bootstrap.php**
```php
ReCaptchaControl\ReCaptchaExtension::register($configurator);
```


**config.neon**

```
recaptcha:
	publicKey: '<your_public_key>'
	privateKey: '<your_private_key>'
	methodName: 'addRecaptcha' # optional
	secured: false # defaults - change to true if you use https
```


**Form**

```php
$form->addReCaptcha('captcha')
		->addRule(Form::VALID, 'Incorrect text code.');
```

It's that simple !
