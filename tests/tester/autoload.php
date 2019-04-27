<?php

use Tester\Helpers;
use Nette\DI\Container;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/Mocks/CustomRequester.php';
require_once __DIR__ . '/Mocks/ErrorRequester.php';
require_once __DIR__ . '/Mocks/RequestFactory.php';
require_once __DIR__ . '/DI/MyFactory.php';


// === helpers =========================

function dd($var)
{
	array_map('dump', func_get_args());
	exit(1);
}


/** @return Container */
function createContainer($config = null)
{
	$configurator = new Nette\Configurator;

	$tempDir = __DIR__ . '/../var/temp/' . getmypid();

	Helpers::purge($tempDir);
	$configurator->setTempDirectory($tempDir);

	if ($config) {
		$configurator->addConfig($config);
	}

	register_shutdown_function(static function () use ($tempDir): void {
		Helpers::purge($tempDir);
		rmdir($tempDir);
	});

	return $configurator->createContainer();
}
