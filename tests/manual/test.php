<?php

use Tracy\Debugger;
use Nette\Forms\Form;
use Guzzle\Http\Client;
use ReCaptchaControl\Http\RequestDataProvider;
use ReCaptchaControl\Http\Requester\CurlRequester;
use ReCaptchaControl\Http\Requester\GuzzleRequester;
use ReCaptchaControl\Http\Requester\SimpleRequester;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/loader.php';
require_once __DIR__ . '/../keys.php';

Debugger::enable(Debugger::DEVELOPMENT, FALSE);

$httpRequest = (new Nette\Http\RequestFactory())->createHttpRequest();
$requestDataProvider = new RequestDataProvider($httpRequest);

// 1. cURL request
$requester = new CurlRequester;

// 2. file_get_contents
// $requester = new SimpleRequester;

// 3. Guzzle HTTP client
// $guzzleClient = new Client;
// $requester = new GuzzleRequester($guzzleClient);

$renderer = new ReCaptchaControl\Renderer(RECAPTCHA_SITEKEY);
$validator = new ReCaptchaControl\Validator($requestDataProvider, $requester, RECAPTCHA_SECRETKEY);
ReCaptchaControl\Control::register($validator, $renderer);


$form = new Form;
$form->addReCaptcha('recaptcha', NULL, "Please prove you're not a robot.");
$form->addSubmit('send');

$form->onSuccess[] = function () {
	echo 'Passed!'; die();
};

$form->fireEvents();

$latte = new Latte\Engine;
Nette\Bridges\FormsLatte\FormMacros::install($latte->getCompiler());

$latte->render(__DIR__ . '/template.latte', [
	'form' => $form,
]);
