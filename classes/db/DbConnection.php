<?php
namespace db;
use PDO;

class DbConnection extends PDO {

	public function __construct($dsn, $presistent = true, $user = 'root', $password = '') {
		$attr = $presistent ? array(PDO::ATTR_PERSISTENT => true) : array(); 
		parent::__construct($dsn, $user, $password, $attr);
	}

	public function startTransation() {
		return $this->beginTransaction();
	}

	public function endTransation($commit = true) {
		return $commit ? $this->commit() : $this->rollback();
	}
	
	public function fetch($sql, array $input_parameters = array()) {
		if ($input_parameters) {
			$statement = $this->prepare($sql);
			if ($statement) {
				$statement->execute($input_parameters);
			}
		} else {
			$statement = $this->query($sql);
		}
		if ($statement) {
			$ret = $statement->fetch(PDO::FETCH_ASSOC);
			return $ret ? $ret : array();
		}
		return false;
	}

	public function fetchAll($sql, array $input_parameters = array()) {
		if ($input_parameters) {
			$statement = $this->prepare($sql);
			if ($statement) {
				$statement->execute($input_parameters);
			}
		} else {
			$statement = $this->query($sql);
		}
		if ($statement) {
			return $statement->fetchAll(PDO::FETCH_ASSOC);
		}
		return false;
	}

	public function execute($sql, array $input_parameters = array()) {
		if ($input_parameters) {
			$statement = $this->prepare($sql);
			if ($statement) {
				$statement->execute($input_parameters);
				return $statement->rowCount();
			}
		} else {
			return $this->exec($sql);
		}
		return false;
	}
}
?>
