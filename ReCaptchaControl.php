<?php

require_once __DIR__ . '/recaptchalib/recaptchalib.php';


/**
 * Nette\Forms reCAPTCHA implementation
 *
 * @author kesspess
 */
class ReCaptchaControl extends Nette\Forms\Controls\BaseControl
{
	/** @var Nette\Http\Request */
	protected $httpRequest;

	/** @var string */
	protected static $privateKey;

	/** @var string */
	protected static $publicKey;



	/**
	 * @param  Nette\Http\Request
	 * @param  string
	 */
	function __construct(Nette\Http\Request $httpRequest, $caption = NULL)
	{
		parent::__construct($caption);
		$this->httpRequest = $httpRequest;
	}



	/** @return Nette\Utils\Html */
	function getControl()
	{
		return Nette\Utils\Html::el(NULL)->setHtml( recaptcha_get_html( static::$publicKey ) );
	}



	/** @return bool */
	static function validateValid(Nette\Forms\IControl $control)
	{
		$response = recaptcha_check_answer(
			static::$privateKey,
			$control->httpRequest->remoteAddress,
			$control->httpRequest->getPost('recaptcha_challenge_field'),
			$control->httpRequest->getPost('recaptcha_response_field')
		);

		return $response->is_valid;
	}



	/** @return Nette\Http\Request */
	function getHttpRequest()
	{
		return $this->httpRequest;
	}



	/**
	 * @param  Nette\Http\Request
	 * @param  string
	 * @param  string
	 * @return void
	 */
	static function register(Nette\Http\Request $httpRequest, $publicKey, $privateKey)
	{
		static::$publicKey = $publicKey;
		static::$privateKey = $privateKey;

		$class = __CLASS__;
		Nette\Forms\Container::extensionMethod('addReCaptcha', function ($container, $name, $label = NULL) use ($class, $httpRequest) {
			return $container[$name] = new $class( $httpRequest, $label );
		});
	}
}
