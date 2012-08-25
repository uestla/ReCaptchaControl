<?php

namespace ReCaptcha;


class Response
{
	/** @var bool */
	protected $valid = FALSE;

	/** @var string */
	protected $message = NULL;



	function __construct($valid, $message = NULL)
	{
		$this->valid = (bool) $valid;
		$this->message = $message;
	}



	/** @return bool */
	function isValid()
	{
		return $this->valid;
	}



	/** @return string */
	function getMessage()
	{
		return $this->message;
	}
}
