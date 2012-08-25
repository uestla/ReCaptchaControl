reCAPTCHA for Nette Framework
=============================

Usage
-----

**bootstrap.php**

```php
ReCaptchaControl::register( $container->httpRequest, '<your_public_key>', '<your_private_key>' );
```

**Form**

```php
$form->addReCaptcha('captcha')
  	->addRule( Form::VALID, 'Incorrect text code.' );
```

It's that simple !
