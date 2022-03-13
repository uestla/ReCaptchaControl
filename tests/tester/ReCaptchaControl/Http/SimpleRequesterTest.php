<?php

declare(strict_types = 1);

namespace Tests\App;

use Tester\Assert;
use ReCaptchaControl\Http\Requester\SimpleRequester;

require_once __DIR__ . '/../../bootstrap.php';


(static function (): void {

	$requester = new SimpleRequester;

	$response = $requester->post('https://example.com');
	Assert::type('string', $response);
	Assert::true(stripos($response, 'Example Domain') !== false);

})();
