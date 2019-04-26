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


class SimpleRequester implements IRequester
{

	/** @inheritdoc */
	public function post(string $url, array $values = []) : ?string
	{
		$context = stream_context_create([
			'http' => [
				[
					'method' => 'POST',
					'content' => http_build_query($values, '', '&'),
				]
			],
		]);

		$result = file_get_contents($url, false, $context);

		return $result === false ? null : $result;
	}

}
