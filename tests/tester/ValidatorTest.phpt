<?php

use Tester\Assert;
use Nette\Forms\Form;
use ReCaptchaControl\Control;
use ReCaptchaControl\Renderer;
use Nette\Http\RequestFactory;
use ReCaptchaControl\Validator;
use ReCaptchaControl\Http\RequestDataProvider;
use ReCaptchaControl\Http\Requester\CurlRequester;

require_once __DIR__ . '/bootstrap.php';


// multiple addRule() error
(function () {

	$httpRequest = (new RequestFactory())->createHttpRequest();
	$requestDataProvider = new RequestDataProvider($httpRequest);

	$requester = new CurlRequester;

	$validator = new Validator($requestDataProvider, $requester, 'RECAPTCHA_SECRETKEY');
	$renderer = new Renderer('RECAPTCHA_SITEKEY');

	Control::register($validator, $renderer);

	$form = new Form;
	$control = $form->addRecaptcha('recaptcha');

	Assert::error(function () use ($control) {
		$control->addRule([Control::class, 'validateValid']);

	}, E_USER_DEPRECATED);

	Assert::error(function () use ($control) {
		$control->addRule(Control::class . '::validateValid');

	}, E_USER_DEPRECATED);

})();
