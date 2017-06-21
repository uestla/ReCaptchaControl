<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

namespace ReCaptchaControl;

use Nette\Utils;
use ReCaptchaControl\Http\IRequestDataProvider;


class Validator
{

	/** @var IRequestDataProvider */
	private $requestDataProvider;

	/** @var string */
	private $secretKey;


	const VERIFICATION_URL = 'https://www.google.com/recaptcha/api/siteverify';


	/**
	 * @param  IRequestDataProvider $requestDataProvider
	 * @param  string $secretKey
	 */
	public function __construct(IRequestDataProvider $requestDataProvider, $secretKey)
	{
		$this->secretKey = $secretKey;
		$this->requestDataProvider = $requestDataProvider;
	}


	/** @return bool */
	public function validate()
	{
		$response = $this->requestDataProvider->getResponseValue();

		if (!$response) {
			return FALSE;
		}

		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL => self::VERIFICATION_URL . '?' . http_build_query([
				'secret' => $this->secretKey,
				'response' => $response,
				'remoteip' => $this->requestDataProvider->getRemoteIP(),
			], '', '&'),

			CURLOPT_RETURNTRANSFER => TRUE,
		]);

		$result = curl_exec($ch);
		if (curl_errno($ch) !== 0) {
			return FALSE;
		}

		try {
			$json = Utils\Json::decode($result);
			return isset($json->success) && $json->success;

		} catch (Utils\JsonException $e) {
			return FALSE;
		}
	}

}
