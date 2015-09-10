<?php
namespace db;
use config\Configuration;

class DbCommander {

	protected static $master_conn;
	protected static $slave_conn;

	public function __construct() {
		if (!self::$slave_conn) {
			self::initSlaveConnection();
		}
		if (!self::$master_conn) {
			self::initMasterConnection();
		}
	}

	public function fetch($sql, $input_parameters = array(), $read_only = true) {
		return $read_only ? static::$slave_conn->fetch($sql, $input_parameters) 
		: static::$master_conn->fetch($sql, $input_parameters);
	}

	public function fetchAll($sql, $input_parameters = array(), $read_only = true) {
		return $read_only ? static::$slave_conn->fetchAll($sql, $input_parameters) 
		: static::$master_conn->fetchAll($sql, $input_parameters);
	}

	public function execute($sql, $input_parameters = array()) {
		return static::$master_conn->execute($sql, $input_parameters);
	}

	public function getLastInsertId($name = null) {
		$config = Configuration::getInstance();
		$auto_increment = $config->get('database', 'auto_increment');
		return $auto_increment == 'seq' ? static::$master_conn->lastInsertId($name) : static::$master_conn->lastInsertId();
	}

	public static function startTransation() {
		if (!static::$master_conn) {
			self::initMasterConnection();
		}
		return static::$master_conn->startTransation();
	}

	public static function inTransaction() {
		if (!static::$master_conn) {
			return false;
		}
		return static::$master_conn->inTransaction();
	}

	public static function endTransation($commit = true) {
		if (!static::$master_conn) {
			self::initMasterConnection();
		}
		return static::$master_conn->endTransation($commit);
	}

	protected static function initSlaveConnection() {
		$config = Configuration::getInstance();
		$dsn = $config->get('database', 'slave_dsn');
		$user = $config->get('database', 'user');
		$password = $config->get('database', 'password');
		static::$slave_conn = new DbConnection($dsn, false, $user, $password);
	}

	protected static function initMasterConnection() {
		$config = Configuration::getInstance();
		$dsn = $config->get('database', 'master_dsn');
		$user = $config->get('database', 'user');
		$password = $config->get('database', 'password');
		static::$master_conn = new DbConnection($dsn, false, $user, $password); 
	}
}
?>
