<?php

use Tester\Assert;
use Nette\Forms\Form;
use ReCaptchaControl\Control;
use ReCaptchaControl\Renderer;
use ReCaptchaControl\Validator;

require_once __DIR__ . '/../bootstrap.php';


test(function () {

	$configurator = new Nette\Configurator;
	$configurator->setTempDirectory(__DIR__ . '/temp');
	$configurator->addConfig(__DIR__ . '/config.neon');

	$container = $configurator->createContainer();

	// services successfully registered?
	Assert::type(Renderer::class, $container->getByType(Renderer::class));
	Assert::type(Validator::class, $container->getByType(Validator::class));


	// extensionMethod registered?
	$form = new Form;
	$form->addTheSweetRecaptcha('recaptcha');
	Assert::type(Control::class, $form['recaptcha']);

});
