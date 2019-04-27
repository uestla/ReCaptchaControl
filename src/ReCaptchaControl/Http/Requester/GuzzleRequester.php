<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

namespace ReCaptchaControl\Http\Requester;

use GuzzleHttp\ClientInterface;


class GuzzleRequester implements IRequester
{

	/** @var ClientInterface */
	private $client;


	public function __construct(ClientInterface $client)
	{
		$this->client = $client;
	}


	/** @inheritdoc */
	public function post($url, array $values = [])
	{
		try {
			$response = $this->client->request('POST', $url, [
				'form_params' => $values,
			]);

			return (string) $response->getBody();

		} catch (\Exception $e) {}

		throw RequestException::create($url, $e->getMessage());
	}

}
