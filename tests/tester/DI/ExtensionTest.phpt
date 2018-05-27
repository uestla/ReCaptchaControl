<?php

use Tester\Assert;
use Nette\Forms\Form;
use ReCaptchaControl\Control;
use ReCaptchaControl\Renderer;
use ReCaptchaControl\Validator;
use Nette\Utils\AssertionException;
use ReCaptchaControl\Http\Requester\IRequester;
use ReCaptchaControl\Http\Requester\CurlRequester;
use ReCaptchaControl\Http\Requester\GuzzleRequester;
use ReCaptchaControl\Http\Requester\SimpleRequester;

require_once __DIR__ . '/../bootstrap.php';


// missing fields
(function () {

	Assert::exception(function () {
		createContainer(__DIR__ . '/config/config.missing.siteKey.neon');
	}, AssertionException::class, "The item 'siteKey' in array expects to be string, NULL given.");

	Assert::exception(function () {
		createContainer(__DIR__ . '/config/config.missing.secretKey.neon');
	}, AssertionException::class, "The item 'secretKey' in array expects to be string, NULL given.");

})();


// default services
(function () {

	$container = createContainer(__DIR__ . '/config/config.simple.neon');
	Assert::type(Renderer::class, $container->getByType(Renderer::class));
	Assert::type(Validator::class, $container->getByType(Validator::class));
	Assert::type(CurlRequester::class, $container->getByType(IRequester::class));

})();


// SimpleRequester
(function () {

	$container = createContainer(__DIR__ . '/config/config.simpleRequester.neon');
	Assert::type(SimpleRequester::class, $container->getByType(IRequester::class));

})();


// CurlRequester
(function () {

	$container = createContainer(__DIR__ . '/config/config.curlRequester.neon');

	$requester = $container->getByType(IRequester::class);
	Assert::type(CurlRequester::class, $requester);
	Assert::false($requester->post('https://google.com')); // check also the invalid certificate options right away

})();


// GuzzleRequester
(function () {

	$container = createContainer(__DIR__ . '/config/config.guzzleRequester.neon');
	Assert::type(GuzzleRequester::class, $container->getByType(IRequester::class));

})();


// custom requester
(function () {

	$container = createContainer(__DIR__ . '/config/config.customRequester.neon');

	$requester = $container->getByType(IRequester::class);
	Assert::type(CustomRequester::class, $requester);

	$response = $requester->post('https://google.com');
	Assert::true(stripos($response, 'elgooG') !== false);

})();


// method name
(function () {

	createContainer(__DIR__ . '/config/config.methodName.neon');

	$form = new Form;
	$form->addTheSweetRecaptcha('recaptcha');
	Assert::type(Control::class, $form['recaptcha']);

})();
