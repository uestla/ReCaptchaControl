<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

declare(strict_types = 1);

namespace ReCaptchaControl\Http;

use Nette\Http\Request;


class RequestDataProvider implements IRequestDataProvider
{

	/** @var Request */
	private $httpRequest;


	public function __construct(Request $httpRequest)
	{
		$this->httpRequest = $httpRequest;
	}


	public function getResponseValue(): ?string
	{
		$response = $this->httpRequest->getPost(self::RESPONSE_KEY);
		assert($response === null || is_string($response));
		return $response;
	}


	public function getRemoteIP(): ?string
	{
		return $this->httpRequest->getRemoteAddress();
	}

}
