<?php

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
	/** @var Http\Request */
	protected $httpRequest;

	/** @var ReCaptcha\ReCaptcha */
	protected $reCaptcha;

	/** @var string */
	protected static $privateKey;

	/** @var string */
	protected static $publicKey;

	/** @var string|NULL */
	protected static $error;

	/** @var bool */
	protected static $secured;



	/**
	 * @param  Http\Request
	 * @param  string
	 */
	function __construct(Http\Request $httpRequest, $caption = NULL)
	{
		parent::__construct($caption);
		$this->httpRequest = $httpRequest;
		$this->reCaptcha = new ReCaptcha\ReCaptcha( static::$publicKey, static::$privateKey, static::$error, static::$secured );
	}



	/** @return Html */
	function getControl()
	{
		return $this->reCaptcha->getHtml();
	}



	/** @return bool */
	static function validateValid(Forms\IControl $control)
	{
		$httpRequest = $control->httpRequest;
		return $control->reCaptcha->validate( $httpRequest->remoteAddress , $httpRequest->getPost() )->isValid();
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
	 * @param  Http\Request
	 * @param  string
	 * @param  string
	 * @return void
	 */
	static function register(Http\Request $httpRequest, $publicKey, $privateKey, $error = NULL, $secured = FALSE)
	{
		static::$publicKey = $publicKey;
		static::$privateKey = $privateKey;
		static::$error = $error;
		static::$secured = $secured;

		$static = __CLASS__;
		Forms\Container::extensionMethod('addReCaptcha', function ($container, $name, $label = NULL) use ($static, $httpRequest) {
			return $container[$name] = new $static( $httpRequest, $label );
		});
	}
}
