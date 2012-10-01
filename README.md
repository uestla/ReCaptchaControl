reCAPTCHA for Nette Framework
=============================

Forum: http://forum.nette.org/cs/11914-recaptcha-pro-nette-forms


Usage
-----

**config.neon**

```
recaptcha:
	publicKey: '<your_public_key>'
	privateKey: '<your_private_key>'
	methodName: 'addRecaptcha' # optional
```


**Form**

```php
$form->addReCaptcha('captcha')
  	->addRule( Form::VALID, 'Incorrect text code.' );
```

It's that simple !
