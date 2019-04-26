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


	/** @param  Request $httpRequest */
	public function __construct(Request $httpRequest)
	{
		$this->httpRequest = $httpRequest;
	}


	/** @inheritdoc */
	public function getResponseValue() : ?string
	{
		return $this->httpRequest->getPost(self::RESPONSE_KEY, null);
	}


	/** @inheritdoc */
	public function getRemoteIP() : ?string
	{
		return $this->httpRequest->getRemoteAddress();
	}

}
