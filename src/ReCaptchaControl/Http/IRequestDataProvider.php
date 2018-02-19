<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

namespace ReCaptchaControl\Http;


interface IRequestDataProvider
{

	const RESPONSE_KEY = 'g-recaptcha-response';

	/** @return string|null */
	public function getResponseValue();

	/** @return string|null */
	public function getRemoteIP();

}
