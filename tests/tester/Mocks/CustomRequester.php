<?php

namespace Tests\Mocks;

use ReCaptchaControl\Http\Requester\IRequester;


class CustomRequester implements IRequester
{

	public function post($url, array $values = [])
	{
		return strrev(file_get_contents($url));
	}

}
