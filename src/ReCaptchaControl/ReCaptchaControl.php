<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * Copyright (c) 2013 Petr Kessler (http://kesspess.1991.cz)
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

	/** @var ReCaptcha\ReCaptcha */
	protected $reCaptcha;

	/** @var Http\Request */
	protected $httpRequest;


	/**
	 * @param  ReCaptcha\ReCaptcha $reCaptcha
	 * @param  Http\Request $httpRequest
	 * @param  string $caption
	 */
	function __construct(ReCaptcha\ReCaptcha $reCaptcha, Http\Request $httpRequest, $caption = NULL)
	{
		parent::__construct($caption);

		$this->reCaptcha = $reCaptcha;
		$this->httpRequest = $httpRequest;
	}


	/** @return Html */
	function getControl()
	{
		$this->setOption('rendered', TRUE);
		return $this->reCaptcha->getHtml();
	}


	/**
	 * @param  Forms\IControl $control
	 * @return bool
	 */
	static function validateValid(Forms\IControl $control)
	{
		$httpRequest = $control->httpRequest;
		return $control->reCaptcha->validate($httpRequest->remoteAddress, $httpRequest->post)->isValid();
	}


	/** @return Http\Request */
	function getHttpRequest()
	{
		return $this->httpRequest;
	}


	/** @return ReCaptcha\ReCaptcha */
	function getReCaptcha()
	{
		return $this->reCaptcha;
	}


	/**
	 * @param  Http\Request $httpRequest
	 * @param  ReCaptcha\ReCaptcha $reCaptcha
	 * @param  string
	 * @return void
	 */
	static function register(Http\Request $httpRequest, ReCaptcha\ReCaptcha $reCaptcha, $method = 'addRecaptcha')
	{
		Forms\Container::extensionMethod($method, function ($container, $name, $label = NULL) use ($httpRequest, $reCaptcha) {
			return $container[$name] = new ReCaptchaControl($reCaptcha, $httpRequest, $label);
		});
	}

}
