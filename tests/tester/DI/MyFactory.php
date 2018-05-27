<?php

class MyFactory
{

	public function factorySiteKey()
	{
		return 'SITE_KEY';
	}


	public function factorySecretKey()
	{
		return 'SECRET_KEY';
	}


	/** @return CustomRequester */
	public function createRequester()
	{
		return new CustomRequester;
	}

}
