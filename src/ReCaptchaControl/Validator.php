<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

namespace ReCaptchaControl;

use Nette\Http;
use Nette\Utils;


class Validator
{

	/** @var Http\Request */
	private $httpRequest;

	/** @var string */
	private $secretKey;


	const RESPONSE_KEY = 'g-recaptcha-response';
	const VERIFICATION_URL = 'https://www.google.com/recaptcha/api/siteverify';


	/**
	 * @param  Http\Request $httpRequest
	 * @param  string $secretKey
	 */
	public function __construct(Http\Request $httpRequest, $secretKey)
	{
		$this->secretKey = $secretKey;
		$this->httpRequest = $httpRequest;
	}


	/** @return bool */
	public function validate()
	{
		$post = $this->httpRequest->getPost();

		if (!isset($post[self::RESPONSE_KEY])) {
			return FALSE;
		}

		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL => self::VERIFICATION_URL . '?' . http_build_query([
				'secret' => $this->secretKey,
				'response' => $post[self::RESPONSE_KEY],
				'remoteip' => $this->httpRequest->getRemoteAddress(),
			], '', '&'),

			CURLOPT_RETURNTRANSFER => TRUE,
		]);

		$response = curl_exec($ch);
		if (curl_errno($ch) !== 0) {
			return FALSE;
		}

		try {
			$json = Utils\Json::decode($response);
			return isset($json->success) && $json->success;

		} catch (Utils\JsonException $e) {
			return FALSE;
		}
	}

}
