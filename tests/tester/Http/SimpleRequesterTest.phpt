<?php

namespace Tests\App;

use Tester\Assert;
use ReCaptchaControl\Http\Requester\SimpleRequester;

require_once __DIR__ . '/../bootstrap.php';


(function () {

	$requester = new SimpleRequester;

	$response = $requester->post('https://google.com');
	Assert::type('string', $response);
	Assert::true(stripos($response, 'Google') !== false);

})();
