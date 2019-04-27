<?php

namespace Tests\Mocks;

use ReCaptchaControl\Http\Requester\IRequester;
use ReCaptchaControl\Http\Requester\RequestException;


final class ErrorRequester implements IRequester
{

	public function post($url, array $values = []): string
	{
		throw RequestException::create($url, 'Error occurred!');
	}

}
