<?php
use mvc\Router;
use mvc\Route;
use mvc\Controller;
use mvc\Application;

spl_autoload_register(function($class) {
	$file = __DIR__ . '/../classes/' . str_replace('\\', '/', $class) . '.php';
	if (file_exists($file)) {
		require($file);
		return true;
	} else {
		throw new Exception("Class {$class} not found");
		return false;
	}
});

$router = new Router();
$router->addRoute(new Route('#^/login/signin/#', 'app\pms\Login', 'signin'));
$router->addRoute(new Route('#^/login/signout/#', 'app\pms\Login', 'signout'));
$router->addRoute(new Route('#^/login/#', 'app\pms\Login', 'index'));
$router->addRoute(new Route('#^/dailywork/#', 'app\pms\DailyWork', 'index'));

$app = new Application();
try {
	$app->run($router, $_GET['route']);
} catch (Exception $ex) {
	header("HTTP/1.1 404 Not Found");
}
?>
