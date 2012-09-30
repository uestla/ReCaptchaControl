reCAPTCHA for Nette Framework
=============================

Forum: http://forum.nette.org/cs/11914-recaptcha-pro-nette-forms


Usage
-----

**config.neon**

```
services:
	reCaptcha: ReCaptcha\ReCaptcha( '<your_public_key>', '<your_private_key>' )

	reCaptchaControlFactory:
		factory: ReCaptchaControlFactory( ..., ... )
		setup:
			- register()
		run: TRUE
```


**Form**

```php
$form->addReCaptcha('captcha')
  	->addRule( Form::VALID, 'Incorrect text code.' );
```

It's that simple !
