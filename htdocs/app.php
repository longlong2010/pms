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
$router->addRoute(new Route('#^/dailywork/list/(u/\d+)(?:/(page/\d+))?#', 'app\pms\DailyWork', 'list'));
$router->addRoute(new Route('#^/dailywork/#', 'app\pms\DailyWork', 'index'));

$router->addRoute(new Route('#^/department/delete/#', 'app\pms\Department', 'delete'));
$router->addRoute(new Route('#^/department/create/#', 'app\pms\Department', 'create'));
$router->addRoute(new Route('#^/department/list/(page/\d+)?#', 'app\pms\Department', 'list'));
$router->addRoute(new Route('#^/department/#', 'app\pms\Department', 'index'));

$router->addRoute(new Route('#^/user/edit/(u/\d+)#', 'app\pms\User', 'edit'));
$router->addRoute(new Route('#^/user/add/#', 'app\pms\User', 'add'));
$router->addRoute(new Route('#^/user/create/#', 'app\pms\User', 'create'));
$router->addRoute(new Route('#^/user/modify/#', 'app\pms\User', 'modify'));
$router->addRoute(new Route('#^/user/delete/#', 'app\pms\User', 'delete'));
$router->addRoute(new Route('#^/user/list/(page/\d+)?#', 'app\pms\User', 'list'));
$router->addRoute(new Route('#^/user/#', 'app\pms\User', 'index'));

$router->addRoute(new Route('#^/project/view/(p/\d+)/member/(page/\d+)?#', 'app\pms\Project', 'viewMember'));
$router->addRoute(new Route('#^/project/view/(p/\d+)/dailywork/(page/\d+)?#', 'app\pms\Project', 'viewDailywork'));
$router->addRoute(new Route('#^/project/add/#', 'app\pms\Project', 'add'));
$router->addRoute(new Route('#^/project/create/#', 'app\pms\Project', 'create'));
$router->addRoute(new Route('#^/project/modify/#', 'app\pms\Project', 'modify'));
$router->addRoute(new Route('#^/project/delete/#', 'app\pms\Project', 'delete'));
$router->addRoute(new Route('#^/project/edit/(p/\d+)#', 'app\pms\Project', 'edit'));
$router->addRoute(new Route('#^/project/member/(p/\d+)/add/#', 'app\pms\Project', 'memberAdd'));
$router->addRoute(new Route('#^/project/member/(p/\d+)/delete/#', 'app\pms\Project', 'memberDelete'));
$router->addRoute(new Route('#^/project/list/(page/\d+)?#', 'app\pms\Project', 'list'));
$router->addRoute(new Route('#^/project/#', 'app\pms\Project', 'index'));

$app = new Application();
try {
	$app->run($router, $_GET['route']);
} catch (Exception $ex) {
	header("HTTP/1.1 404 Not Found");
}
?>
