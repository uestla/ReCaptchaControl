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

	/** @var boolean */
	private $useCurlSSL;


	const RESPONSE_KEY = 'g-recaptcha-response';
	const VERIFICATION_URL = 'https://www.google.com/recaptcha/api/siteverify';
	const DEFAULT_USE_CURL_SSL = true;


	/**
	 * @param  Http\Request $httpRequest
	 * @param  string $secretKey
	 */
	public function __construct(Http\Request $httpRequest, $secretKey, $useCurlSSL = DEFAULT_USE_CURL_SSL)
	{
		$this->secretKey = $secretKey;
		$this->httpRequest = $httpRequest;
		$this->useCurlSSL = $useCurlSSL;
	}


	/** @return bool */
	public function validate()
	{
		$post = $this->httpRequest->getPost();

		if (!isset($post[self::RESPONSE_KEY])) {
			return FALSE;
		}

		$ch = curl_init();

		$options = [
			CURLOPT_URL => self::VERIFICATION_URL . '?' . http_build_query([
					'secret' => $this->secretKey,
					'response' => $post[self::RESPONSE_KEY],
					'remoteip' => $this->httpRequest->getRemoteAddress(),
				], '', '&'),

			CURLOPT_RETURNTRANSFER => TRUE,
		];

		if(!$this->useCurlSSL) {
			$options[CURLOPT_SSL_VERIFYPEER] = false;
		}

		curl_setopt_array($ch, $options);

		$response = curl_exec($ch);
		if (curl_errno($ch) !== 0) {

			if (curl_errno($ch) === 60) {
				throw new \Exception('Curl error: ' . curl_error($ch));
			}

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
