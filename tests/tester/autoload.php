<?php

use Tester\Helpers;
use Nette\DI\Container;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/DI/CustomRequester.php';
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

	$tempDir = __DIR__ . '/temp/' . getmypid();

	Helpers::purge($tempDir);
	$configurator->setTempDirectory($tempDir);

	if ($config) {
		$configurator->addConfig($config);
	}

	return $configurator->createContainer();
}
