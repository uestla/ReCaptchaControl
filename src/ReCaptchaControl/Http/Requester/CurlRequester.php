<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

declare(strict_types = 1);

namespace ReCaptchaControl\Http\Requester;

use Nette\Utils\Strings;


class CurlRequester implements IRequester
{

	/** @var mixed[] */
	private $options;


	/** @param  mixed[] $options */
	public function __construct(array $options = [])
	{
		if (!extension_loaded('curl')) {
			throw new \RuntimeException(sprintf('cURL extension is needed by the %s class.', __CLASS__));
		}

		$this->options = self::processOptions($options);
	}


	/** @param  array<string, mixed> $values */
	public function post(string $url, array $values = []): string
	{
		$ch = curl_init();

		curl_setopt_array($ch, [
			CURLOPT_URL => $url,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $values,
			CURLOPT_RETURNTRANSFER => true,

		] + $this->options);

		$response = curl_exec($ch);
		$errno = curl_errno($ch);
		$error = curl_error($ch);
		curl_close($ch);

		if ($errno === 0 && is_string($response)) {
			return $response;
		}

		throw RequestException::create($url, $error);
	}


	/**
	 * @param  mixed[] $options
	 * @return mixed[]
	 */
	private static function processOptions(array $options): array
	{
		// NOTE: intentionally not using array_walk since array keys cannot be changed
		foreach ($options as $key => $val) {
			if (Strings::startsWith((string) $key, 'CURLOPT_')) {
				unset($options[$key]);
				$options[constant($key)] = $val;
			}
		}

		return $options;
	}

}
