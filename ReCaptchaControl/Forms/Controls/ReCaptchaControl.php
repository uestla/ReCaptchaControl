<?php

/**
 * This file is part of the ReCaptchaExtension package
 *
 * Copyright (c) 2013 Petr Kessler (http://kesspess.1991.cz)
 *
 * @license  MIT
 * @link     https://github.com/uestla/ReCaptchaControl
 */


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
	 * @param  Http\Request
	 * @param  string
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



	/** @return bool */
	static function validateValid(Forms\IControl $control)
	{
		$httpRequest = $control->httpRequest;
		return $control->reCaptcha->validate( $httpRequest->remoteAddress , $httpRequest->post )->isValid();
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



	/** @return void */
	static function register(Http\Request $httpRequest, ReCaptcha\ReCaptcha $reCaptcha, $method = 'addRecaptcha')
	{
		Nette\Forms\Container::extensionMethod( $method, function ($container, $name, $label = NULL) use ($httpRequest, $reCaptcha) {
			return $container[$name] = new ReCaptchaControl( $reCaptcha, $httpRequest, $label );
		} );
	}
}
