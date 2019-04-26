<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

namespace ReCaptchaControl\Http\Requester;


interface IRequester
{

	/**
	 * Performs a HTTP POST request with given $values
	 */
	public function post(string $url, array $values = []) : ?string;

}
