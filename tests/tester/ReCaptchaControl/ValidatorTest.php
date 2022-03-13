<?php

declare(strict_types = 1);

use Tester\Assert;
use Nette\Forms\Form;
use ReCaptchaControl\Control;
use ReCaptchaControl\Renderer;
use ReCaptchaControl\Validator;
use Tests\Mocks\ErrorRequester;
use Tests\Mocks\RequestFactory;
use ReCaptchaControl\Http\RequestDataProvider;
use ReCaptchaControl\Http\Requester\CurlRequester;

require_once __DIR__ . '/../bootstrap.php';


// multiple addRule() error
(static function (): void {

	$httpRequest = RequestFactory::create();
	$requestDataProvider = new RequestDataProvider($httpRequest);

	$requester = new CurlRequester;

	$validator = new Validator($requestDataProvider, $requester, 'RECAPTCHA_SECRETKEY');
	$renderer = new Renderer('RECAPTCHA_SITEKEY');

	Control::register($validator, $renderer);

	$form = new Form;
	$control = $form->addReCaptcha('recaptcha');

	Assert::error(static function () use ($control): void {
		$control->addRule([Control::class, 'validateValid']);

	}, E_USER_DEPRECATED, 'ReCaptchaControl is required by default and thus calling addRule() is deprecated. Please remove it to prevent multiple validation.');

	Assert::error(static function () use ($control): void {
		$control->addRule(Control::class . '::validateValid');

	}, E_USER_DEPRECATED, 'ReCaptchaControl is required by default and thus calling addRule() is deprecated. Please remove it to prevent multiple validation.');

})();


// onError event
(static function (): void {

	$httpRequest = RequestFactory::create();
	$requestDataProvider = new RequestDataProvider($httpRequest);
	$requester = new ErrorRequester;

	$validator = new Validator($requestDataProvider, $requester, 'RECAPTCHA_SECRETKEY');

	$bool = false;
	$validator->onError[] = static function (\Exception $e) use (& $bool): void {
		$bool = true;
		Assert::match('Cannot fetch URL "https://www.google.com/recaptcha/api/siteverify?secret=RECAPTCHA_SECRETKEY&response=%a%": Error occurred!', $e->getMessage());
	};

	Assert::false($validator->validate());
	Assert::true($bool); // changed by onError event

})();
