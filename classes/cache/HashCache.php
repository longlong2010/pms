<?php
namespace cache;
use \config\Configuration;
use \Memcached;
class HashCache {

	private static $hashdb;

	public function __construct() {
		if (!static::$hashdb) {
			self::init();
		}
	}

	public function get($key) {
		return static::$hashdb->get($key);
	}

	public function add($key, $val, $expiration = 0) {
		return static::$hashdb->add($key, $val, $expiration);
	}

	public function set($key, $val, $expiration = 0) {
		return static::$hashdb->set($key, $val, $expiration);
	}

	public function replace($key, $val, $expiration = 0) {
		return static::$hashdb->replace($key, $val, $expiration);
	}

	public function delete($key) {
		return static::$hashdb->delete($key);
	}

	protected static function init() {
		$config = Configuration::getInstance();
		static::$hashdb = new Memcached();
		static::$hashdb->addServers($config->get('memcache', 'servers'));
		static::$hashdb->setOption(Memcached::OPT_COMPRESSION, false);
		/*
		spl_autoload_register(function($class) {
			require_once(__DIR__ . "/{$class}.php");
		});
		static::$hashdb = new SqlCache($config->get('sqlcache', 'file'), $config->get('sqlcache', 'table'));
		*/
	}
}
?>
