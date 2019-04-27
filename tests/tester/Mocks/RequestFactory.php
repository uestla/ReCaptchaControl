<?php

declare(strict_types = 1);

namespace Tests\Mocks;

use Nette\Http\Request;
use Nette\Http\UrlScript;
use ReCaptchaControl\Http\RequestDataProvider;


final class RequestFactory
{

	public static function create(): Request
	{
		return new Request(new UrlScript('/'), [
			RequestDataProvider::RESPONSE_KEY => 'test',
		]);
	}

}
