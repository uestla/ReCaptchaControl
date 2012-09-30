<?php


/**
 * Nette\Forms reCAPTCHA control factory
 *
 * @author kesspess
 */
class ReCaptchaControlFactory extends Nette\Object
{
	/** @var Closure */
	protected $registerCb;



	function __construct(ReCaptcha\ReCaptcha $reCaptcha, Nette\Http\Request $httpRequest)
	{
		$this->registerCb = function ($container, $name, $label = NULL) use ($reCaptcha, $httpRequest) {
			return $container[$name] = new ReCaptchaControl( $reCaptcha, $httpRequest, $label );
		};
	}



	function register($methodName = 'addReCaptcha')
	{
		Nette\Forms\Container::extensionMethod($methodName, $this->registerCb);
	}
}
