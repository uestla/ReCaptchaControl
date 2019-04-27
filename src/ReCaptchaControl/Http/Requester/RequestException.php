<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

declare(strict_types = 1);

namespace ReCaptchaControl\Http\Requester;


class RequestException extends \Exception
{

	public static function create(string $url, string $error): self
	{
		return new self(sprintf('Cannot fetch URL "%s": %s', $url, $error));
	}

}
