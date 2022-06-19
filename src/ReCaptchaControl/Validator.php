<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

declare(strict_types = 1);

namespace ReCaptchaControl;

use Nette\Utils\Json;
use Nette\SmartObject;
use Nette\Utils\JsonException;
use ReCaptchaControl\Http\IRequestDataProvider;
use ReCaptchaControl\Http\Requester\IRequester;
use ReCaptchaControl\Http\Requester\RequestException;


class Validator
{

	use SmartObject;


	/** @var IRequestDataProvider */
	private $requestDataProvider;

	/** @var IRequester */
	private $requester;

	/** @var string */
	private $secretKey;

	/** @var callable[] */
	public $onError = [];


	private const VERIFICATION_URL = 'https://www.google.com/recaptcha/api/siteverify';


	public function __construct(IRequestDataProvider $requestDataProvider, IRequester $requester, string $secretKey)
	{
		$this->secretKey = $secretKey;
		$this->requester = $requester;
		$this->requestDataProvider = $requestDataProvider;
	}


	public function validate(): bool
	{
		$response = $this->requestDataProvider->getResponseValue();

		if ($response === null) {
			return false;
		}

		try {
			$url = sprintf('%s?%s', self::VERIFICATION_URL, http_build_query([
				'secret' => $this->secretKey,
				'response' => $response,
				'remoteip' => $this->requestDataProvider->getRemoteIP(),

			], '', '&'));

			$result = $this->requester->post($url);

			try {
				$json = Json::decode($result);
				return $json instanceof \stdClass && isset($json->success) && $json->success;

			} catch (JsonException $e) {}

		} catch (RequestException $e) {
			$this->onError($e);
		}

		return false;
	}

}
