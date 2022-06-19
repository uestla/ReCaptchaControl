<?php

declare(strict_types = 1);

namespace App\Router;

use Nette\Application\Routers\RouteList;


final class RouterFactory
{

	public function createRouter(): RouteList
	{
		$router = new RouteList;
		$router->addRoute('<action>', 'Test:single');
		return $router;
	}

}
