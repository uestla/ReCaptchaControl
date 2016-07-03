<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * Copyright (c) 2016 Petr Kessler (http://kesspess.1991.cz)
 *
 * @license  MIT
 * @link     https://github.com/uestla/ReCaptchaControl
 */

namespace ReCaptchaControl;

use Nette;


/**
 * Nette\Forms reCAPTCHA compiler extension
 *
 * @author vojtech-dobes (https://github.com/vojtech-dobes)
 */
class ReCaptchaExtension extends Nette\DI\CompilerExtension
{

	/** @var string[] */
	protected $defaults = array(
		'methodName' => 'addReCaptcha',
	);

	/** @var string */
	protected $prefix;


	/** @return void */
	public function loadConfiguration()
	{
		$container = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$container->addDefinition($this->prefix('recaptcha'))
				->setClass('ReCaptchaControl\ReCaptcha', array($config['siteKey'], $config['secretKey']));
	}


	/**
	 * @param  Nette\PhpGenerator\ClassType $class
	 * @return void
	 */
	public function afterCompile(Nette\PhpGenerator\ClassType $class)
	{
		$initialize = $class->getMethod('initialize');
		$config = $this->getConfig($this->defaults);

		$initialize->addBody('$context = $this;');
		$initialize->addBody('ReCaptchaControl\ReCaptchaControl::register($context->getByType(\'Nette\Http\IRequest\'), $context->getService(?), ?);',
				array($this->prefix('recaptcha'), $config['methodName']));
	}

}
