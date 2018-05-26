<?php

use Tester\Assert;
use Tester\TestCase;
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
require_once __DIR__ . '/CustomRequester.php';


class ExtensionTest extends TestCase
{

	public function testMissingFields()
	{
		Assert::exception(function () {
			$this->createContainer(__DIR__ . '/config/config.missing.siteKey.neon');
		}, AssertionException::class, "The item 'siteKey' in array expects to be string, NULL given.");

		Assert::exception(function () {
			$this->createContainer(__DIR__ . '/config/config.missing.secretKey.neon');
		}, AssertionException::class, "The item 'secretKey' in array expects to be string, NULL given.");
	}


	public function testDefaultServices()
	{
		$container = $this->createContainer(__DIR__ . '/config/config.simple.neon');
		Assert::type(Renderer::class, $container->getByType(Renderer::class));
		Assert::type(Validator::class, $container->getByType(Validator::class));
		Assert::type(CurlRequester::class, $container->getByType(IRequester::class));
	}


	public function testSimpleRequester()
	{
		$container = $this->createContainer(__DIR__ . '/config/config.simpleRequester.neon');
		Assert::type(SimpleRequester::class, $container->getByType(IRequester::class));
	}


	public function testCurlRequester()
	{
		$container = $this->createContainer(__DIR__ . '/config/config.curlRequester.neon');

		$requester = $container->getByType(IRequester::class);
		Assert::type(CurlRequester::class, $requester);
		Assert::false($requester->post('https://google.com')); // check also the invalid certificate options right away
	}


	public function testGuzzleRequester()
	{
		$container = $this->createContainer(__DIR__ . '/config/config.guzzleRequester.neon');
		Assert::type(GuzzleRequester::class, $container->getByType(IRequester::class));
	}


	public function testCustomRequester()
	{
		$container = $this->createContainer(__DIR__ . '/config/config.customRequester.neon');

		$requester = $container->getByType(IRequester::class);
		Assert::type(CustomRequester::class, $requester);

		$response = $requester->post('https://google.com');
		Assert::true(stripos($response, 'elgooG') !== FALSE);
	}


	public function testMethodName()
	{
		$this->createContainer(__DIR__ . '/config/config.methodName.neon');

		$form = new Form;
		$form->addTheSweetRecaptcha('recaptcha');
		Assert::type(Control::class, $form['recaptcha']);
	}


	private function createContainer($config = NULL)
	{
		$configurator = new Nette\Configurator;
		$configurator->setTempDirectory(__DIR__ . '/temp');

		if ($config) {
			$configurator->addConfig($config);
		}

		return $configurator->createContainer();
	}

}


(new ExtensionTest())->run();
