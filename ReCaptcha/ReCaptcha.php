<?php

namespace ReCaptcha;

use Nette\Utils\Html;
use Nette\Utils\Strings;


/**
 * ReCaptcha PHP class
 *
 * @author kesspess
 */
class ReCaptcha
{
	/** @var string */
	protected $publicKey;

	/** @var string */
	protected $privateKey;

	/** @var string|NULL */
	protected $error;

	/** @var bool */
	protected $secured;



	/**
	 * @param  string
	 * @param  string
	 * @param  string|NULL
	 * @param  bool
	 */
	function __construct($publicKey, $privateKey, $error = NULL, $secured = FALSE)
	{
		$this->publicKey = $publicKey;
		$this->privateKey = $privateKey;
		$this->error = $error;
		$this->secured = (bool) $secured;
	}



	/** @return Html */
	function getHtml()
	{
		$server = $this->secured ? 'https://www.google.com/recaptcha/api' : 'http://www.google.com/recaptcha/api';

		$query = http_build_query( array(
			'k' => $this->publicKey,
			'error' => $this->error,
		) );

		$script = Html::el('script')
				->type('text/javascript')
				->src( $server . '/challenge?' . $query );

		$noscript = Html::el('noscript');

		$iframe = Html::el('iframe')
				->src( $server . '/noscript?' . $query )
				->width(500)
				->height(300)
				->frameborder(0);

		$textarea = Html::el('textarea')
				->name('recaptcha_challenge_field')
				->rows(3)
				->cols(40);

		$input = Html::el('input')
				->type('hidden')
				->name('recaptcha_response_field')
				->value('manual_challenge');

		return Html::el(NULL)
				->add( $script )
				->add( $noscript->add( $iframe )->add( Html::el('br') )->add( $textarea )->add( $input ) );
	}



	/**
	 * @param  string
	 * @param  array
	 * @return Response
	 */
	function validate($remoteAddress, array $post)
	{
		$chKey = 'recaptcha_challenge_field';
		$reKey = 'recaptcha_response_field';

		if (!isset($post[ $chKey ], $post[ $reKey ])
				|| !strlen( Strings::trim( $post[ $chKey ] ) )
				|| !strlen( Strings::trim( $post[ $reKey ] ) )) {
			return new Response(FALSE, 'incorrect-captcha-sol');
		}

		$response = $this->request(array(
			'privatekey' => $this->privateKey,
			'remoteip' => $remoteAddress,
			'challenge' => $post[ $chKey ],
			'response' => $post[ $reKey ],
		));

		list ($answer, $error) = explode("\n", $response[1]);
		return Strings::trim($answer) === 'true' ? new Response(TRUE) : new Response(FALSE, $error);
	}



	/**
	 * @param  array
	 * @return array
	 */
	protected function request(array $params)
	{
		$server = 'www.google.com';
		$query = http_build_query($params);

		$header = "POST /recaptcha/api/verify HTTP/1.0\r\n"
			. "Host: $server\r\n"
			. "Content-Type: application/x-www-form-urlencoded;\r\n"
			. "Content-Length: " . strlen($query) . "\r\n"
			. "User-Agent: Booyakasha\r\n\r\n"
			. $query;

		if (($socket = @fsockopen($server, 80)) === FALSE) {
			throw new Exception("Could not open socket to '$server'.");
		}

		fwrite($socket, $header);
		$response = '';

		while (!feof($socket)) {
			$response .= fgets($socket, 1160);
		}

		fclose($socket);
		return explode("\r\n\r\n", $response, 2);
	}
}
