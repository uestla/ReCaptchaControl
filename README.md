reCAPTCHA for Nette Framework
=============================

Usage
-----

**bootstrap.php**

```php
$reCaptcha = new ReCaptcha\ReCaptcha( '<your_public_key>', '<your_private_key>' );

$controlFactory = new ReCaptchaControlFactory( $reCaptcha, $container->httpRequest );
$controlFactory->register();
```

**Form**

```php
$form->addReCaptcha('captcha')
  	->addRule( Form::VALID, 'Incorrect text code.' );
```

It's that simple !
