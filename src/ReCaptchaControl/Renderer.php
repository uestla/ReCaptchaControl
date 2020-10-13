<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

declare(strict_types = 1);

namespace ReCaptchaControl;

use Nette\Utils\Html;


class Renderer
{

	/** @var string */
	private $siteKey;


	public function __construct(string $siteKey)
	{
		$this->siteKey = $siteKey;
	}


	/** @return Html<Html|string> */
	public function getHtml(): Html
	{
		return Html::el('div')
			->class('g-recaptcha')
			->data('sitekey', $this->siteKey);
	}

}
