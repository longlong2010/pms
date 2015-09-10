<?php
namespace session;
class Cookie {

	private $name;
	private $expire;
	private $path;
	private $domain;
	private $pairs;

	const SEPARATOR = "\x02";
	const CONNECTOR = "\x03";

	public function __construct($name, $expire = 0, $path = '', $domain = '') {
		$this->name = $name;
		$this->expire = time() + $expire;
		$this->path = $path ? $path : '/';
		$this->domain = $domain;
		$this->pairs = array();
		$this->load();
	}

	public function set($key, $val) {
		return $this->pairs[$key] = $val;
	}

	public function get($key) {
		return $this->pairs[$key];
	}

	public function delete($key) {
		$pairs = $this->pairs;
		unset($pairs[$key]);
		return $this->pairs = $pairs;
	}
	
	public function save() {
		$vals = $this->pairs;
		$pairs = array();
		foreach ($vals as $key => $val) {
			$pairs[] = $key . self::CONNECTOR . $val;
		}
		$pairs = implode(self::SEPARATOR, $pairs);
		return setcookie($this->name, $pairs, $this->expire, $this->path, $this->domain);
	}

	public function remove() {
		setcookie($this->name, '', 0, $this->path, $this->domain);
	}

	protected function load() {
		$pairs = explode(self::SEPARATOR, $_COOKIE[$this->name]);
		$vals = array();
		foreach ($pairs as $pair) {
			list($key, $val) = explode(self::CONNECTOR, $pair);
			$vals[$key] = $val;
		}
		$this->pairs = $vals;
	}
}
?>
