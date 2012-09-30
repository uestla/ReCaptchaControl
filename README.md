reCAPTCHA for Nette Framework
=============================

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
