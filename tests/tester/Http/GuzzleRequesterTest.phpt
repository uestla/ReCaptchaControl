<?php

namespace Tests\App;

use Tester\Assert;
use Guzzle\Http\Client;
use ReCaptchaControl\Http\Requester\GuzzleRequester;

require_once __DIR__ . '/../bootstrap.php';


(function () {

	$client = new Client;
	$requester = new GuzzleRequester($client);

	$response = $requester->post('https://seznam.cz');
	Assert::type('string', $response);
	Assert::true(stripos($response, 'Google') !== false);

})();
