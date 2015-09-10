<?php
namespace mvc;

class Route {

    private $pattern = '';
    private $controllerName = '';
    private $actionName = '';
	private $requestParam = array();
    
    public function __construct($pattern, $controllerName, $actionName) {
        $this->pattern = $pattern;
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
    }
    
    public function getPattern() {
        return $this->pattern;
    }
    
    public function getControllerName() {
        return $this->controllerName;
    }
   
    public function getActionName() {
        return $this->actionName;
    }
    
    public function isMatch($url) {
		if (preg_match($this->pattern, $url, $match)) {
			$param = array();
			$count = count($match);
			for ($i = 1; $i < $count; $i++) {
				list($k, $v) = explode('/', $match[$i], 2);
				$param[$k] = $v;
			}
			$this->requestParam = $param;
			return true;
		} else {
			return false;
		}
    }

	public function getRequestParam() {
		return $this->requestParam;
	}
}
?>
