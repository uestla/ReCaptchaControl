<?php

declare(strict_types = 1);

use Tests\Mocks\CustomRequester;
use ReCaptchaControl\Http\Requester\IRequester;


final class MyFactory
{

	public function factorySiteKey(): string
	{
		return 'SITE_KEY';
	}


	public function factorySecretKey(): string
	{
		return 'SECRET_KEY';
	}


	public function createRequester(): IRequester
	{
		return new CustomRequester;
	}

}
