<?php

use Nette\Http;
use Nette\Forms;
use Nette\Utils\Html;

require_once __DIR__ . '/recaptchalib/recaptchalib.php';


/**
 * Nette\Forms reCAPTCHA implementation
 *
 * @author kesspess
 */
class ReCaptchaControl extends Forms\Controls\BaseControl
{
	/** @var Http\Request */
	protected $httpRequest;

	/** @var string */
	protected static $privateKey;

	/** @var string */
	protected static $publicKey;



	/**
	 * @param  Http\Request
	 * @param  string
	 */
	function __construct(Http\Request $httpRequest, $caption = NULL)
	{
		parent::__construct($caption);
		$this->httpRequest = $httpRequest;
	}



	/** @return Html */
	function getControl()
	{
		return Html::el(NULL)->setHtml( recaptcha_get_html( static::$publicKey ) );
	}



	/** @return bool */
	static function validateValid(Forms\IControl $control)
	{
		$response = recaptcha_check_answer(
			static::$privateKey,
			$control->httpRequest->remoteAddress,
			$control->httpRequest->getPost('recaptcha_challenge_field'),
			$control->httpRequest->getPost('recaptcha_response_field')
		);

		return $response->is_valid;
	}



	/** @return Http\Request */
	function getHttpRequest()
	{
		return $this->httpRequest;
	}



	/**
	 * @param  Http\Request
	 * @param  string
	 * @param  string
	 * @return void
	 */
	static function register(Http\Request $httpRequest, $publicKey, $privateKey)
	{
		static::$publicKey = $publicKey;
		static::$privateKey = $privateKey;

		$class = __CLASS__;
		Forms\Container::extensionMethod('addReCaptcha', function ($container, $name, $label = NULL) use ($class, $httpRequest) {
			return $container[$name] = new $class( $httpRequest, $label );
		});
	}
}
