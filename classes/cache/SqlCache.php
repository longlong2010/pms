<?php
namespace cache;
class SqlCache {

	private $sqlite;
	private $table;

	public function __construct($file, $table) {
		$this->table = $table;
		$this->sqlite = new PDO("sqlite:{$file}");
		$stat = $this->sqlite->query("SELECT * FROM sqlite_master WHERE type = 'table' AND name = '{$table}'");
		if (!$stat->fetch(PDO::FETCH_ASSOC)) {
			$this->sqlite->exec("CREATE TABLE {$table}" . 
								  "(k VARCHAR(256) PRIMARY KEY, " . 
								   "v text, " . 
								   "expire_ts INTEGER)" 
								  );
		}
	}

	public function get($key) {
		$table = $this->table;
		$sql = "SELECT v FROM {$table} WHERE k = ? AND expire_ts > ?";
		$stat = $this->sqlite->prepare($sql);
		$stat->execute(array($key, time()));
		$result = $stat->fetch(PDO::FETCH_ASSOC);
		return unserialize($result['v']);
	}

	public function add($key, $val, $expiration = 0) {
		$table = $this->table;
		$sql = "INSERT INTO {$table} VALUES (?, ?, ?)";
		$stat = $this->sqlite->prepare($sql);
		$expire_ts = time() + ($expiration > 0 ? $expiration : 2592000);
		return $stat->execute(array($key, serialize($val), $expire_ts));
	}

	public function set($key, $val, $expiration = 0) {
		$table = $this->table;
		$sql = "REPLACE INTO {$table} VALUES (?, ?, ?)";
		$stat = $this->sqlite->prepare($sql);
		$expire_ts = time() + ($expiration > 0 ? $expiration : 2592000);
		return $stat->execute(array($key, serialize($val), $expire_ts));
	}

	public function replace($key, $val, $expiration) {
		$table = $this->table;
		$sql = "UPDATE {$table} SET v = ?, expire_ts = ? WHERE k = ?";
		$stat = $this->sqlite->prepare($sql);
		$expire_ts = time() + ($expiration > 0 ? $expiration : 2592000);
		return $stat->execute(array(serialize($val), $expire_ts, $key));
	}

	public function delete($key) {
		$table = $this->table;
		$sql = "DELETE FROM {$table} WHERE k = ?";
		$stat = $this->sqlite->prepare($sql);
		return $stat->execute(array($key));
	}
}
?>
