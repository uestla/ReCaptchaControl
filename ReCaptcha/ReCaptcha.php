<?php

namespace ReCaptcha;

use Nette\Utils\Html;
use Nette\Utils\Strings;

require_once __DIR__ . '/Response.php';
require_once __DIR__ . '/Exception.php';


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

		list ($answer, $error) = explode("\n", $response);
		return Strings::trim($answer) === 'true' ? new Response(TRUE) : new Response(FALSE, $error);
	}



	/**
	 * @param  array
	 * @return array
	 */
	protected function request(array $params)
	{
		$context = stream_context_create(array(
			'http' => array(
				'method' => 'POST',
				'header' => "Content-Type: application/x-www-form-urlencoded;\r\n\r\n",
				'content' => http_build_query( $params ),
			),
		));

		$fp = fopen('http://www.google.com/recaptcha/api/verify', 'r', FALSE, $context);
		$response = stream_get_contents($fp);
		fclose($fp);

		return $response;
	}
}
