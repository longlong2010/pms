<?php
namespace mvc;
// The Router class stores Routes and handles all MVC related
//   routing logic
class Router {

    private $routes = array();
    
    public function __construct() {
		
    }
   
    public function addRoute(Route $route) {
        $this->routes[] = $route;        
    }
    
    // Gets the registered route that matches the specified
    //   sanitized url
    public function getMatchingRoute($santitizedUrl) {
        foreach ($this->routes as &$route) {            
            if ($route->isMatch($santitizedUrl)) {
                return $route;            
            }
        }
        return null;
    }    
}
?>
