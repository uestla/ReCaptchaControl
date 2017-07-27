<?php

namespace Tests\App;

use Tester\Assert;
use Tester\TestCase;
use Guzzle\Http\Client;
use ReCaptchaControl\Http\Requester\GuzzleRequester;

require_once __DIR__ . '/../bootstrap.php';


class GuzzleRequesterTest extends TestCase
{

	public function testRequest()
	{
		$client = new Client;
		$requester = new GuzzleRequester($client);

		$response = $requester->post('https://seznam.cz');
		Assert::type('string', $response);
		Assert::true(stripos($response, 'Google') !== FALSE);
	}

}


(new GuzzleRequesterTest())->run();
