<?php
namespace config;
class Configuration {

	private static $config;
	
	private $data;

	private function __construct() {
		$this->data = array(
			'memcache'	=>	array(
				'servers'	=>	array(
					array('127.0.0.1', 11211),
				),
			),
			'upload' => array('dir' =>	'/var/www/localhost/upload/photo/',
							  'uri'	=>	'/upload/photo/',
							  'resize_dir' =>	'/var/www/localhost/resize/photo/',
							  'resize_uri' =>	'/resize/photo/',
						),
			'sqlcache' => array('file' => '/tmp/cache.sq3', 'table' => 'HashCache'),
			'database'	=>	array(
				'master_dsn'	=>	'mysql:dbname=pms;host=127.0.0.1;charset=utf8;',
				'slave_dsn'		=>	'mysql:dbname=pms;host=127.0.0.1;charset=utf8;',
//				'master_dsn'	=>	'sqlite:/var/www/localhost/test.sq3',
//				'slave_dsn'	=>	'sqlite:/var/www/localhost/test.sq3',
//				'master_dsn'	=>	'pgsql:dbname=pms;host=127.0.0.1;',
//				'slave_dsn'		=>	'pgsql:dbname=pms;host=127.0.0.1;',
				'user'			=>	'root',
				'password'		=>	'',
//				'auto_increment' => 'seq',
			),
		);
	}

	public function get($section, $key) {
		return $this->data[$section][$key];
	}

	public function __clone() {
		return static::$config;
	}

	public static function getInstance() {
		if (!self::$config) {
			self::$config = new self();
		}
		return self::$config;
	}
}
?>
