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

use Nette\Http;
use Nette\Forms;
use Nette\Utils\Html;


/**
 * Nette\Forms reCAPTCHA implementation
 *
 * @author kesspess
 */
class ReCaptchaControl extends Forms\Controls\BaseControl
{

	/** @var ReCaptcha */
	private $reCaptcha;

	/** @var Http\Request */
	private $httpRequest;

	/** @var bool */
	private $initialized = FALSE;


	/**
	 * @param  ReCaptcha $reCaptcha
	 * @param  Http\Request $httpRequest
	 * @param  string $caption
	 * @param  string $message
	 */
	public function __construct(ReCaptcha $reCaptcha, Http\Request $httpRequest, $caption = NULL, $message = NULL)
	{
		parent::__construct($caption);

		$this->setOmitted();
		$this->reCaptcha = $reCaptcha;
		$this->httpRequest = $httpRequest;
		$this->control = $reCaptcha->getHtml();
		$this->setRequired($message)->addRule(__CLASS__ . '::validateValid', $message);
		$this->initialized = TRUE;
	}


	/** @inheritdoc */
	public function addRule($validator, $message = NULL, $arg = NULL)
	{
		if ($this->initialized && ($validator === [__CLASS__, 'validateValid'] || $validator === __CLASS__ . '::validateValid')) {
			trigger_error('addRule() is deprecated at RecaptchaControl. Please remove it to prevent multiple validation.', E_USER_DEPRECATED);
		}

		return parent::addRule($validator, $message, $arg);
	}


	/** @return Html */
	public function getControl()
	{
		$this->setOption('rendered', TRUE);
		$control = clone $this->control;
		$control->id = $this->getHtmlId();
		return $control;
	}


	/** @return bool */
	public function isFilled()
	{
		return TRUE;
	}


	/**
	 * @param  ReCaptchaControl $control
	 * @return bool
	 */
	public static function validateValid(ReCaptchaControl $control)
	{
		$httpRequest = $control->getHttpRequest();
		return $control->getReCaptcha()->validate($httpRequest->getRemoteAddress(), $httpRequest->getPost());
	}


	/** @return Http\Request */
	public function getHttpRequest()
	{
		return $this->httpRequest;
	}


	/** @return ReCaptcha */
	public function getReCaptcha()
	{
		return $this->reCaptcha;
	}


	/**
	 * @param  Http\Request $httpRequest
	 * @param  ReCaptcha $reCaptcha
	 * @param  string $method
	 * @return void
	 */
	public static function register(Http\Request $httpRequest, ReCaptcha $reCaptcha, $method = 'addRecaptcha')
	{
		Forms\Container::extensionMethod($method, function ($container, $name, $label = NULL, $message = NULL) use ($httpRequest, $reCaptcha) {
			return $container[$name] = new ReCaptchaControl($reCaptcha, $httpRequest, $label, $message);
		});
	}

}
