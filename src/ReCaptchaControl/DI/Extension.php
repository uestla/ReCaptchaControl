<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

declare(strict_types = 1);

namespace ReCaptchaControl\DI;

use Nette\Utils\Strings;
use Nette\Schema\Schema;
use Nette\Schema\Expect;
use ReCaptchaControl\Control;
use ReCaptchaControl\Renderer;
use Nette\DI\CompilerExtension;
use ReCaptchaControl\Validator;
use Nette\PhpGenerator\ClassType;
use ReCaptchaControl\Configuration;
use Nette\DI\Definitions\Statement;
use ReCaptchaControl\Http\RequestDataProvider;
use ReCaptchaControl\Http\IRequestDataProvider;
use ReCaptchaControl\Http\Requester\CurlRequester;


/**
 * Nette\Forms reCAPTCHA compiler extension
 *
 * @author vojtech-dobes (https://github.com/vojtech-dobes)
 */
class Extension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'siteKey' => Expect::anyOf(Expect::string(), Expect::type(Statement::class))->dynamic()->required(),
			'secretKey' => Expect::anyOf(Expect::string(), Expect::type(Statement::class))->dynamic()->required(),
			'methodName' => Expect::string('addReCaptcha'),
			'requester' => Expect::string(CurlRequester::class),
		]);
	}


	public function loadConfiguration(): void
	{
		assert($this->config instanceof \stdClass);

		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('requestDataProvider'))
				->setFactory(RequestDataProvider::class);

		if (Strings::startsWith($this->config->requester, '@')) {
			$requesterService = $this->config->requester;

		} else {
			$builder->addDefinition($this->prefix('requester'))
				->setFactory($this->config->requester);

			$requesterService = '@' . $this->prefix('requester');
		}

		$builder->addDefinition($this->prefix('validator'))
				->setFactory(Validator::class, ['@' . IRequestDataProvider::class, $requesterService, $this->config->secretKey]);

		$builder->addDefinition($this->prefix('renderer'))
				->setFactory(Renderer::class, [$this->config->siteKey]);
	}


	public function afterCompile(ClassType $class): void
	{
		assert($this->config instanceof \stdClass);
		$initialize = $class->getMethod('initialize');

		$initialize->addBody(Control::class . '::register($this->getService(?), $this->getService(?), ?);',
				[$this->prefix('validator'), $this->prefix('renderer'), $this->config->methodName]);
	}

}
