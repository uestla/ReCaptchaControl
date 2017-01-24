<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

namespace ReCaptchaControl\DI;

use Nette;
use ReCaptchaControl\Control;
use ReCaptchaControl\Renderer;
use ReCaptchaControl\Validator;
use ReCaptchaControl\Configuration;


/**
 * Nette\Forms reCAPTCHA compiler extension
 *
 * @author vojtech-dobes (https://github.com/vojtech-dobes)
 */
class Extension extends Nette\DI\CompilerExtension
{

	/** @var string[] */
	protected $defaults = [
		'methodName' => 'addReCaptcha',
	];

	/** @var string */
	protected $prefix;


	/** @return void */
	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$container->addDefinition($this->prefix('validator'))
				->setClass(Validator::class, ['@' . Nette\Http\IRequest::class, $config['secretKey']]);

		$container->addDefinition($this->prefix('renderer'))
				->setClass(Renderer::class, [$config['siteKey']]);
	}


	/**
	 * @param  Nette\PhpGenerator\ClassType $class
	 * @return void
	 */
	public function afterCompile(Nette\PhpGenerator\ClassType $class)
	{
		$initialize = $class->getMethod('initialize');
		$config = $this->getConfig($this->defaults);

		$initialize->addBody(Control::class . '::register($this->getService(?), $this->getService(?), ?);',
				[$this->prefix('validator'), $this->prefix('renderer'), $config['methodName']]);
	}

}
