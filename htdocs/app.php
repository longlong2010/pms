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

$router->addRoute(new Route('#^/dailywork/create/#', 'app\pms\DailyWork', 'create'));
$router->addRoute(new Route('#^/dailywork/write/#', 'app\pms\DailyWork', 'write'));
$router->addRoute(new Route('#^/dailywork/#', 'app\pms\DailyWork', 'index'));

$router->addRoute(new Route('#^/department/delete/#', 'app\pms\Department', 'delete'));
$router->addRoute(new Route('#^/department/create/#', 'app\pms\Department', 'create'));
$router->addRoute(new Route('#^/department/#', 'app\pms\Department', 'index'));

$router->addRoute(new Route('#^/user/edit/(u/\d+)#', 'app\pms\User', 'edit'));
$router->addRoute(new Route('#^/user/#', 'app\pms\User', 'index'));

$app = new Application();
try {
	$app->run($router, $_GET['route']);
} catch (Exception $ex) {
	header("HTTP/1.1 404 Not Found");
}
?>
