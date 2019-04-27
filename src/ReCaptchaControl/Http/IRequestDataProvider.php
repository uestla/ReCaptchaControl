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


interface IRequestDataProvider
{

	const RESPONSE_KEY = 'g-recaptcha-response';

	public function getResponseValue(): ?string;

	public function getRemoteIP(): ?string;

}
