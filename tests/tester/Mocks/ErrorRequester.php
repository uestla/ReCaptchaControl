<?php

declare(strict_types = 1);

namespace Tests\Mocks;

use ReCaptchaControl\Http\Requester\IRequester;
use ReCaptchaControl\Http\Requester\RequestException;


final class ErrorRequester implements IRequester
{

	/** @param  array<string, mixed> $values */
	public function post(string $url, array $values = []): string
	{
		throw RequestException::create($url, 'Error occurred!');
	}

}
