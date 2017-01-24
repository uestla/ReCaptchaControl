<?php

use Tracy\Debugger;
use Nette\Forms\Form;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/loader.php';
require_once __DIR__ . '/../keys.php';

Debugger::enable(Debugger::DEVELOPMENT, FALSE);


$renderer = new ReCaptchaControl\Renderer(RECAPTCHA_SITEKEY);
$validator = new ReCaptchaControl\Validator((new Nette\Http\RequestFactory())->createHttpRequest(), RECAPTCHA_SECRETKEY);

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
