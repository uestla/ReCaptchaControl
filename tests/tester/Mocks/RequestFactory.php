<?php

namespace Tests\Mocks;

use Nette\Http\Request;
use Nette\Http\UrlScript;
use ReCaptchaControl\Http\RequestDataProvider;


final class RequestFactory
{

	/** @return Request */
	public static function create()
	{
		return new Request(new UrlScript('/'), null, [
			RequestDataProvider::RESPONSE_KEY => 'test',
		]);
	}

}
