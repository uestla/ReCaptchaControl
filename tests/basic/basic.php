<?php

use Tracy\Debugger;
use Nette\Forms\Form;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../../src/loader.php';

Debugger::enable(Debugger::DEVELOPMENT, FALSE);


$siteKey = 'YOUR_SITE_KEY';
$secretKey = 'YOUR_SECRET_KEY';


ReCaptchaControl\ReCaptchaControl::register(
		(new Nette\Http\RequestFactory)->createHttpRequest(),
		new ReCaptchaControl\ReCaptcha($siteKey, $secretKey)
);


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
