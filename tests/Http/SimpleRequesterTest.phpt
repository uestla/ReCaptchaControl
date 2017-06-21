<?php

namespace Tests\App;

use Tester\Assert;
use Tester\TestCase;
use ReCaptchaControl\Http\Requester\SimpleRequester;

require_once __DIR__ . '/../bootstrap.php';


class SimpleRequesterTest extends TestCase
{

	public function testRequest()
	{
		$requester = new SimpleRequester;

		$response = $requester->post('https://google.com');
		Assert::type('string', $response);
		Assert::true(stripos($response, 'Google') !== FALSE);
	}

}


(new SimpleRequesterTest())->run();
