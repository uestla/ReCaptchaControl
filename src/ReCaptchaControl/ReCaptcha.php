<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * Copyright (c) 2016 Petr Kessler (http://kesspess.1991.cz)
 *
 * @license  MIT
 * @link     https://github.com/uestla/ReCaptchaControl
 */

namespace ReCaptchaControl;

use Nette\Utils;


/**
 * ReCaptcha PHP class
 *
 * @author kesspess
 */
class ReCaptcha
{

	/** @var string */
	private $siteKey;

	/** @var string */
	private $secretKey;


	const RESPONSE_KEY = 'g-recaptcha-response';
	const VERIFICATION_URL = 'https://www.google.com/recaptcha/api/siteverify';


	/**
	 * @param  string $siteKey
	 * @param  string $secretKey
	 */
	public function __construct($siteKey, $secretKey)
	{
		$this->siteKey = $siteKey;
		$this->secretKey = $secretKey;
	}


	/** @return Utils\Html */
	public function getHtml()
	{
		return Utils\Html::el('div')
				->class('g-recaptcha')
				->data('sitekey', $this->siteKey);
	}


	/**
	 * @param  string $remoteIP
	 * @param  array $post
	 * @return bool
	 */
	public function validate($remoteIP, array $post)
	{
		if (!isset($post[self::RESPONSE_KEY])) {
			return FALSE;
		}

		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => self::VERIFICATION_URL . '?' . http_build_query(array(
				'remoteip' => $remoteIP,
				'secret' => $this->secretKey,
				'response' => $post[self::RESPONSE_KEY],
			), '', '&'),
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_SSL_VERIFYHOST => FALSE,
			CURLOPT_SSL_VERIFYPEER => FALSE,
		));

		$response = curl_exec($ch);
		if (curl_errno($ch) !== 0) {
			return FALSE;
		}

		try {
			$json = Utils\Json::decode($response);
			return isset($json->success) && $json->success;

		} catch (Utils\JsonException $e) {
			return FALSE;
		}
	}

}
