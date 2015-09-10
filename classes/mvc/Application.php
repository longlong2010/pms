<?php
namespace mvc;

class Application {

	public function run(Router $router, $url) {
		$route = $router->getMatchingRoute($url);
		if ($route) {
			$actionValues = $route->getRequestParam();
			$controllerName = $route->getControllerName();
			$controllerName = $controllerName . 'Controller';
			$controller = new $controllerName();
			$action = $route->getActionName() . 'Action';
			$controller->$action($actionValues);
		} else {
			throw new Exception('Not Found', 404);
		}
	}
}
?>
