# reCAPTCHA for Nette Framework

Adds the reCAPTCHA control to Nette Framework forms.

* [Official documentation](https://developers.google.com/recaptcha/)
* [Online demo](https://kesspess.cz/recaptcha/)
* [Nette forum thread (CZE)](http://forum.nette.org/cs/21770-nova-recaptcha-pro-formulare)


## Documentation

1. [Installation](#installation)
2. [Configuration](#configuration)
3. [Usage](#usage)
4. [Requester](#requester)
5. [AJAX](#ajax)
6. [Invisible reCAPTCHA](#invisible-recaptcha)
7. [Testing](#testing)


### Installation

For easy installation use [Composer](https://getcomposer.org/):

```
composer require uestla/recaptcha-control
```

Also don't forget to include the official JavaScript library:

```html
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
```

Are you using AJAX? Then you may want to use library asset instead - [see more](#ajax).


### Configuration

To be able to use the reCAPTCHA control in your forms just register the DI extension in your `config.neon`:

```neon
extensions:
	recaptcha: ReCaptchaControl\DI\Extension

recaptcha:
	# required
	siteKey: '<your_site_key>'
	secretKey: '<your_secret_key>'

	# optional
	methodName: 'addReCaptcha'
	requester: ReCaptchaControl\Http\Requester\CurlRequester
```

**Parameters:**

| Parameter    | Type                               | Default value                                                            | Required | Meaning                                                                                                                                                                                              |
|--------------|------------------------------------|--------------------------------------------------------------------------|----------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `siteKey`    | string &#124; `Nette\DI\Statement` | ~                                                                        | YES      | The site key you obtain in your [Google Account](https://www.google.com/recaptcha/admin)                                                                                                             |
| `secretKey`  | string &#124; `Nette\DI\Statement` | ~                                                                        | YES      | The secret key you obtain in your [Google Account](https://www.google.com/recaptcha/admin)                                                                                                           |
| `methodName` | string                             | "addReCaptcha"                                                           | NO       | Extension method name you'll be calling upon your forms to add the control, e.g. `$form->addReCaptcha(...)`                                                                                          |
| `requester`  | string                             | ["CurlRequester"](src/ReCaptchaControl/Http/Requester/CurlRequester.php) | NO       | Name of the class or service which sends requests to the Google validation API. The default `CurlRequester` needs PHP cURL extension to run properly. [Read more about requesters here](#requester). |


### Usage


#### Form

To actually add reCAPTCHA to your form just call

```php
$form->addReCaptcha(
	'captcha', // control name
	'reCAPTCHA for you', // label
	"Please prove you're not a robot." // error message
);
```

Please note that the validation rule is added automatically so you don't need to call any `addRule()` at all.


#### Template

You can then render the control in your Latte template using both macro and `n:attr` approach:

```latte
<form ...>
	{* n:attr *}
	<div n:name="captcha"></div>

	{* or macro *}
	{input captcha}

	{* don't forget to render potential errors *}
	{$form['captcha']->getError()}
</form>
```

And there she goes! :-)

![reCAPTCHA](http://i.imgur.com/s6MDqmV.png)


### Requester

Requester is a layer for sending HTTP requests. It comes handy when your production environment does not meet the default requirements (cURL extension etc.).

You can change the default requester by setting the `requester` key in [configuration](#configuration). The value can be either a class name or a name of another service (see details below).

1.  #### CurlRequester

	This is the default one since `requester` value is optional in configuration.

	It uses PHP cURL extension. If you want to set any [CURLOPT_*](http://php.net/manual/en/function.curl-setopt.php) value for the requests you have to create the service aside and pass these options to the constructor:

	```neon
	recaptcha:
		...
		requester: @curlRequester

	services:
		curlRequester:
			class: ReCaptchaControl\Http\Requester\CurlRequester
			arguments:
				-
					CURLOPT_CAINFO: %appDir%/res/cacert.pem
					CURLOPT_USERAGENT: 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.104 Safari/537.36'
	```

	As you can see in the example it is possible to use all CURLOPT_* constants as string keys (they are [converted internally](src/ReCaptchaControl/Http/Requester/CurlRequester.php#L30)).


2. #### SimpleRequester

	Calls `file_get_contents()` with stream context.

	```neon
	recaptcha:
		...
		requester: ReCaptchaControl\Http\Requester\SimpleRequester
	```


3. #### GuzzleRequester

	If you're already using the [Guzzle HTTP Client](https://github.com/guzzle/guzzle/) in your application, this requester may come handy:

	```neon
	recaptcha:
		...
		requester: ReCaptchaControl\Http\Requester\GuzzleRequester

	services:
		- Guzzle\Http\Client # will be autowired to the GuzzleRequester constructor
	```

	If you're using multiple clients it is better to define the requester as a service aside:

	```neon
	recaptcha:
		...
		requester: @guzzleRequester

	services:
		guzzleRequester: ReCaptchaControl\Http\Requester\GuzzleRequester(@primaryGuzzleHttpClient)

		primaryGuzzleHttpClient: Guzzle\Http\Client
		secondaryGuzzleHttpClient: Guzzle\Http\Client
	```


4. #### Custom requester

	You can also implement your own requester. Just make sure it implements the [ReCaptchaControl\Http\Requester\IRequester](src/ReCaptchaControl/Http/Requester/IRequester.php) interface.

	It basically requires a single `public function post(string $url, array $values = []): string` method which takes URL, performs a HTTP POST request with given `$values` and returns body of the response as a string. In case of a failure, `ReCaptchaControl\Http\Requester\RequestException` should be thrown.

	You can then use it the same way as above:

	```neon
	recaptcha:
		requester: MyRequesterClass
	```

	or when you have some dependencies:

	```neon
	recaptcha:
		requester: @myRequester

	services:
		myRequester:
			factory: MyRequesterClass( ... )
			...
	```


### AJAX

When a snippet containing reCAPTCHA control gets updated, the reCAPTCHA itself needs to be re-rendered.

If you're using the [nette.ajax.js](https://github.com/vojtech-dobes/nette.ajax.js), you may want to use the [assets/recaptcha.ajax.js](assets/recaptcha.ajax.js) script.

You can install it via `bower`:

```
bower install
```

IMPORTANT: The [recaptcha.ajax.js](assets/recaptcha.ajax.js) script loads the official JavaScript library because it needs to render the reCATPCHAs [explicitely](https://developers.google.com/recaptcha/docs/display#explicit_render). So please be careful not to loaded by yourself as well.


### Invisible reCAPTCHA

You can also use this library for [Invisible reCAPTCHA](https://developers.google.com/recaptcha/docs/invisible). The backend part stays the same so it only needs proper configuration in the frontend. To see it in action you can visit https://kesspess.cz/recaptcha/invisible and the code in [tests](tests/manual/app/presenters/templates/Test/invisible.latte). 


## Tests & CI

### Automated tests

This library uses [Nette Tester](https://tester.nette.org/) for automated testing and [PHPStan](https://github.com/phpstan/phpstan) for static analysis. For running them yourself simply run

```
# runs test suite & static analysis
composer ci
```

### Manual testing

You may have noticed the [tests/manual](tests/manual) directory. Its content is actualy live at https://kesspess.cz/recaptcha.

To get it to work on your local machine, do following:

1. copy `config/local.neon.template` to `config/local.neon`
2. fill reCAPTCHA keys properly in `config/local.neon`
3. run `composer install`
4. run `bower install`

After that you should be able to run it via your local web server.

The form definitions are in [TestPresenter](tests/manual/app/presenters/TestPresenter.php).

Individual example templates are located in [presenters/templates](tests/manual/app/presenters/templates) directory.
