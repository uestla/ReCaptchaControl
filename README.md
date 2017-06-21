# reCAPTCHA for Nette Framework

Adds the reCAPTCHA control to Nette Framework forms.

* [Official documentation](https://developers.google.com/recaptcha/)
* [Nette forum thread (CZE)](http://forum.nette.org/cs/21770-nova-recaptcha-pro-formulare)


## Documentation

1. [Installation](#installation)
2. [Configuration](#configuration)
3. [Usage](#usage)
4. [Requester](#requester)
5. [AJAX](#ajax)


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

| Parameter    | Type   | Default value                                                            | Required | Meaning                                                                                                                                                                                              |
|--------------|--------|--------------------------------------------------------------------------|----------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `siteKey`    | string | ~                                                                        | YES      | The site key you obtain in your [Google Account](https://www.google.com/recaptcha/admin)                                                                                                             |
| `secretKey`  | string | ~                                                                        | YES      | The secret key you obtain in your [Google Account](https://www.google.com/recaptcha/admin)                                                                                                           |
| `methodName` | string | "addReCaptcha"                                                           | NO       | Extension method name you'll be calling upon your forms to add the control, e.g. `$form->addReCaptcha(...)`                                                                                          |
| `requester`  | string | ["CurlRequester"](src/ReCaptchaControl/Http/Requester/CurlRequester.php) | NO       | Name of the class or service which sends requests to the Google validation API. The default `CurlRequester` needs PHP cURL extension to run properly. [Read more about requesters here](#requester). |


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

	It basically requires a single `public function post($url, array $values = [])` method which takes the URL as a string, performs a HTTP POST request with given `$values` and returns body of the response as a string or FALSE on failure.

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

