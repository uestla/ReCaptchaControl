<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

namespace ReCaptchaControl\DI;

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


/**
 * Nette\Forms reCAPTCHA compiler extension
 *
 * @author vojtech-dobes (https://github.com/vojtech-dobes)
 */
class Extension extends CompilerExtension
{

	/** @var string[] */
	protected $defaults = [
		'siteKey' => NULL,
		'secretKey' => NULL,
		'methodName' => 'addReCaptcha',
	];

	/** @var string */
	protected $prefix;


	/** @return void */
	public function loadConfiguration()
	{
		$config = $this->validateConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		Validators::assertField($config, 'siteKey', 'string');
		Validators::assertField($config, 'secretKey', 'string');
		Validators::assertField($config, 'methodName', 'string');

		$builder->addDefinition($this->prefix('requestDataProvider'))
				->setClass(RequestDataProvider::class);

		$builder->addDefinition($this->prefix('validator'))
				->setClass(Validator::class, ['@' . IRequestDataProvider::class, $config['secretKey']]);

		$builder->addDefinition($this->prefix('renderer'))
				->setClass(Renderer::class, [$config['siteKey']]);
	}


	/**
	 * @param  ClassType $class
	 * @return void
	 */
	public function afterCompile(ClassType $class)
	{
		$initialize = $class->getMethod('initialize');
		$config = $this->validateConfig($this->defaults);

		$initialize->addBody(Control::class . '::register($this->getService(?), $this->getService(?), ?);',
				[$this->prefix('validator'), $this->prefix('renderer'), $config['methodName']]);
	}

}
