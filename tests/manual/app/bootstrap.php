<?php

declare(strict_types = 1);

namespace App;

use Nette\Bootstrap\Configurator;


final class Bootstrap
{

	public function boot(): Configurator
	{
		$appDir = dirname(__DIR__);
		$configurator = new Configurator;

		$configurator->enableTracy($appDir . '/log');

		$configurator->setTimeZone('Europe/Prague');
		$configurator->setTempDirectory($appDir . '/temp');

		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		$configurator->addConfig($appDir . '/config/common.neon');
		$configurator->addConfig($appDir . '/config/local.neon');

		return $configurator;
	}

}
