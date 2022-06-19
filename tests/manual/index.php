<?php

declare(strict_types = 1);

use App\Bootstrap;
use Nette\Application\Application;

require_once __DIR__ . '/vendor/autoload.php';

(new Bootstrap)
	->boot()
	->createContainer()
	->getByType(Application::class)
	->run();
