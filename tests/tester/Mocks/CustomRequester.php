<?php

namespace Tests\Mocks;

use ReCaptchaControl\Http\Requester\IRequester;
use ReCaptchaControl\Http\Requester\RequestException;


final class CustomRequester implements IRequester
{

	/** @inheritdoc */
	public function post($url, array $values = []): string
	{
		$content = @file_get_contents($url);

		if ($content === false) {
			$error = error_get_last();
			throw RequestException::create($url, $error === null ? '' : $error['message']);
		}

		return strrev($content);
	}

}
