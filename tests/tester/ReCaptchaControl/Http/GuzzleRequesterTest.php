<?php

declare(strict_types = 1);

namespace Tests\App;

use Tester\Assert;
use GuzzleHttp\Client;
use ReCaptchaControl\Http\Requester\GuzzleRequester;

require_once __DIR__ . '/../../bootstrap.php';


(static function (): void {

	$client = new Client;
	$requester = new GuzzleRequester($client);

	$response = $requester->post('https://example.com');
	Assert::type('string', $response);
	Assert::true(stripos($response, 'Example Domain') !== false);

})();
