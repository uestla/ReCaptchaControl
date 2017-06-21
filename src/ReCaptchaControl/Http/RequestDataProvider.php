<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

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
	public function getResponseValue()
	{
		return $this->httpRequest->getPost(self::RESPONSE_KEY, NULL);
	}


	/** @inheritdoc */
	public function getRemoteIP()
	{
		return $this->httpRequest->getRemoteAddress();
	}

}
