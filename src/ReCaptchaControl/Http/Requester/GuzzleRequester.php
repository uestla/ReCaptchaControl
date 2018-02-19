<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

namespace ReCaptchaControl\Http\Requester;

use Guzzle\Http\Client;


class GuzzleRequester implements IRequester
{

	/** @var Client */
	private $client;


	/** @param  Client $client */
	public function __construct(Client $client)
	{
		$this->client = $client;
	}


	/** @inheritdoc */
	public function post($url, array $values = [])
	{
		try {
			$request = $this->client->post($url, [
				'form_params' => $values,
			]);

			$response = $request->send();
			return $response->getBody(true);

		} catch (\Exception $e) {} // convert exception & bubble up?

		return false;
	}

}
