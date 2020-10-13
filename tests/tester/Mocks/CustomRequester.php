<?php

declare(strict_types = 1);

namespace Tests\Mocks;

use ReCaptchaControl\Http\Requester\IRequester;
use ReCaptchaControl\Http\Requester\RequestException;


final class CustomRequester implements IRequester
{

	/** @param  array<string, mixed> $values */
	public function post(string $url, array $values = []): string
	{
		$content = @file_get_contents($url);

		if ($content === false) {
			$error = error_get_last();
			throw RequestException::create($url, $error === null ? '' : $error['message']);
		}

		return strrev($content);
	}

}
