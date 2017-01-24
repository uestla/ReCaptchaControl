<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

namespace ReCaptchaControl;

use Nette\Utils;


class Renderer
{

	/** @var string */
	private $siteKey;


	/** @param string $siteKey */
	public function __construct($siteKey)
	{
		$this->siteKey = $siteKey;
	}


	/** @return Utils\Html */
	public function getHtml()
	{
		return Utils\Html::el('div')
			->class('g-recaptcha')
			->data('sitekey', $this->siteKey);
	}

}
