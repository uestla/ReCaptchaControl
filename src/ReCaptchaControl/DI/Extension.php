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

use Nette\DI\Statement;
use Nette\Utils\Strings;
use Nette\Utils\Validators;
use ReCaptchaControl\Control;
use ReCaptchaControl\Renderer;
use Nette\DI\CompilerExtension;
use ReCaptchaControl\Validator;
use Nette\PhpGenerator\ClassType;
use ReCaptchaControl\Configuration;
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

	/** @var array */
	protected $defaults = [
		'siteKey' => null,
		'secretKey' => null,
		'methodName' => 'addReCaptcha',
		'requester' => CurlRequester::class,
	];

	/** @var string */
	protected $prefix;


	public function loadConfiguration(): void
	{
		$config = $this->validateConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		Validators::assertField($config, 'siteKey', sprintf('string|%s', Statement::class));
		Validators::assertField($config, 'secretKey', sprintf('string|%s', Statement::class));
		Validators::assertField($config, 'methodName', 'string');
		Validators::assertField($config, 'requester', 'string');

		$builder->addDefinition($this->prefix('requestDataProvider'))
				->setFactory(RequestDataProvider::class);

		if (Strings::startsWith($config['requester'], '@')) {
			$requesterService = $config['requester'];

		} else {
			$builder->addDefinition($this->prefix('requester'))
				->setFactory($config['requester']);

			$requesterService = '@' . $this->prefix('requester');
		}

		$builder->addDefinition($this->prefix('validator'))
				->setFactory(Validator::class, ['@' . IRequestDataProvider::class, $requesterService, $config['secretKey']]);

		$builder->addDefinition($this->prefix('renderer'))
				->setFactory(Renderer::class, [$config['siteKey']]);
	}


	public function afterCompile(ClassType $class): void
	{
		$initialize = $class->getMethod('initialize');
		$config = $this->validateConfig($this->defaults);

		$initialize->addBody(Control::class . '::register($this->getService(?), $this->getService(?), ?);',
				[$this->prefix('validator'), $this->prefix('renderer'), $config['methodName']]);
	}

}
