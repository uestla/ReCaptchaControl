<?php

namespace Tests\App;

use Tester\Assert;
use ReCaptchaControl\Http\Requester\CurlRequester;

require_once __DIR__ . '/../bootstrap.php';


// request google.com
(function () {

	$requester = new CurlRequester;

	$response = $requester->post('https://google.com');
	Assert::type('string', $response);
	Assert::true(stripos($response, 'Google') !== FALSE);

})();


// invalid certificates
(function () {

	// set invalid CA paths but leave SSL verifications on
	$requester = new CurlRequester([
		CURLOPT_CAPATH => __DIR__,
		CURLOPT_CAINFO => __FILE__,
	]);

	Assert::false($requester->post('https://google.com'));

})();


// overloaded options
(function () {

	$requester = new CurlRequester([
		CURLOPT_URL => 'https://www.seznam.cz',
	]);

	$response = $requester->post('https://google.com');
	Assert::true(stripos($response, 'Google') !== FALSE);

})();
