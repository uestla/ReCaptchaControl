<?php

namespace Tests\App;

use Tester\Assert;
use ReCaptchaControl\Http\Requester\CurlRequester;
use ReCaptchaControl\Http\Requester\RequestException;

require_once __DIR__ . '/../bootstrap.php';


// request google.com
(function () {

	$requester = new CurlRequester;

	$response = $requester->post('http://example.com');
	Assert::type('string', $response);
	Assert::true(stripos($response, 'Example Domain') !== false);

})();


// invalid certificates
(function () {

	// set invalid CA paths but leave SSL verifications on
	$requester = new CurlRequester([
		CURLOPT_CAPATH => __DIR__,
		CURLOPT_CAINFO => __FILE__,
	]);

	Assert::exception(static function () use ($requester): void {
		$requester->post('https://example.com');
	}, RequestException::class, 'Cannot fetch URL "https://example.com": error setting certificate verify locations:%A%');

})();


// overloaded options
(function () {

	$requester = new CurlRequester([
		CURLOPT_URL => 'https://www.seznam.cz',
	]);

	$response = $requester->post('http://example.com');
	Assert::true(stripos($response, 'Example Domain') !== false);

})();
