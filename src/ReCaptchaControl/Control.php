<?php

/**
 * This file is part of the ReCaptchaControl package
 *
 * @license  MIT
 * @author   Petr Kessler (https://kesspess.cz)
 * @link     https://github.com/uestla/ReCaptchaControl
 */

namespace ReCaptchaControl;

use Nette\Forms;
use Nette\Utils\Html;


class Control extends Forms\Controls\BaseControl
{

	/** @var Validator */
	private $validator;

	/** @var Renderer */
	private $renderer;

	/** @var bool */
	private $initialized = FALSE;


	/**
	 * @param  Validator $validator
	 * @param  Renderer $renderer
	 * @param  string $caption
	 * @param  string $message
	 */
	public function __construct(Validator $validator, Renderer $renderer, $caption = NULL, $message = NULL)
	{
		parent::__construct($caption);

		$this->setOmitted();
		$this->renderer = $renderer;
		$this->validator = $validator;
		$this->setRequired(TRUE)->addRule(__CLASS__ . '::validateValid', $message);

		$this->initialized = TRUE;
	}


	/** @inheritdoc */
	public function addRule($validator, $message = NULL, $arg = NULL)
	{
		if ($this->initialized
				&& ($validator === [__CLASS__, 'validateValid'] || $validator === __CLASS__ . '::validateValid')) {
			trigger_error('ReCaptchaControl is required by default and thus calling addRule() is deprecated. Please remove it to prevent multiple validation.', E_USER_DEPRECATED);
		}

		return parent::addRule($validator, $message, $arg);
	}


	/** @return Html */
	public function getControl()
	{
		$this->setOption('rendered', TRUE);
		$control = $this->renderer->getHtml();
		$control->id = $this->getHtmlId();
		return $control;
	}


	/** @return bool */
	public function isFilled()
	{
		return TRUE;
	}


	/**
	 * @param  Control $control
	 * @return bool
	 */
	public static function validateValid(Control $control)
	{
		return $control->validator->validate();
	}


	/**
	 * @param  Validator $validator
	 * @param  Renderer $renderer
	 * @param  string $method
	 * @return void
	 */
	public static function register(Validator $validator, Renderer $renderer, $method = 'addRecaptcha')
	{
		Forms\Container::extensionMethod($method, function ($container, $name, $label = NULL, $message = NULL) use ($validator, $renderer) {
			return $container[$name] = new Control($validator, $renderer, $label, $message);
		});
	}

}
