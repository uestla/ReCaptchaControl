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

use Nette\Utils;


class Renderer
{

	/** @var string */
	private $siteKey;


	public function __construct(string $siteKey)
	{
		$this->siteKey = $siteKey;
	}


	public function getHtml(): Utils\Html
	{
		return Utils\Html::el('div')
			->class('g-recaptcha')
			->data('sitekey', $this->siteKey);
	}

}
