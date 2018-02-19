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
use ReCaptchaControl\Http\Requester\IRequester;


class Validator
{

	/** @var IRequestDataProvider */
	private $requestDataProvider;

	/** @var IRequester */
	private $requester;

	/** @var string */
	private $secretKey;


	const VERIFICATION_URL = 'https://www.google.com/recaptcha/api/siteverify';


	/**
	 * @param  IRequestDataProvider $requestDataProvider
	 * @param  IRequester $requester
	 * @param  string $secretKey
	 */
	public function __construct(IRequestDataProvider $requestDataProvider, IRequester $requester, $secretKey)
	{
		$this->secretKey = $secretKey;
		$this->requester = $requester;
		$this->requestDataProvider = $requestDataProvider;
	}


	/** @return bool */
	public function validate()
	{
		$response = $this->requestDataProvider->getResponseValue();

		if (!$response) {
			return false;
		}

		$result = $this->requester->post(self::VERIFICATION_URL . '?' . http_build_query([
			'secret' => $this->secretKey,
			'response' => $response,
			'remoteip' => $this->requestDataProvider->getRemoteIP(),

		], '', '&'));

		if (!$result) {
			return false;
		}

		try {
			$json = Utils\Json::decode($result);
			return isset($json->success) && $json->success;

		} catch (Utils\JsonException $e) {
			return false;
		}
	}

}
