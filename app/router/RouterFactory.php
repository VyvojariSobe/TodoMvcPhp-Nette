<?php
declare(strict_types = 1);

namespace App;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class RouterFactory
{
	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter() : Nette\Application\IRouter
	{
		$router = new RouteList;
		//$router[] = new Route('<presenter>/<action>[/<id>]', 'Todos:default');
		$router[] = new Route('clear-completed', 'Todos:clearCompleted');
		$router[] = new Route('<filter=>', 'Todos:default');
		$router[] = new Route('<id>/remove', 'Todos:remove');
		$router[] = new Route('<id>/change-status/<status check|uncheck>', 'Todos:changeStatus');
		return $router;
	}
}
