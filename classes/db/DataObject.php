<?php
namespace db;
use config\Configuration; 
use cache\HashCache;
class DataObject {

	protected $fields;
	protected $exists;
	protected $read_only;
	protected $db;

	protected static $primary;
	protected static $table_name;
	protected static $auto_increment;

	public function __construct($primary, $read_only = true) {
		$this->read_only = $read_only;
		$this->db = new DbCommander();	
		if ($primary !== null) {
			$this->fields[static::$primary] = $primary;
			$this->load();
		}
	}

	public function save() {
		$this->beforeSave();
		if ($this->read_only === true) {
			return false;
		}
		$table_name = static::$table_name;
		$primary = static::$primary;
		$fields = $this->fields;
		$fields_keys = array_keys($fields);
		$fields_vals = array_values($fields);
		if (!$this->exists) {
			if (static::$auto_increment) {
				unset($fields[$primary]);
				$fields_keys = array_keys($fields);
				$fields_vals = array_values($fields);
			}
			$cols_count = count($fields_keys);
			$keys = implode(', ', $fields_keys);
			$question_marks = array();
			for ($i = 0; $i < $cols_count; $i++) {
				$question_marks[] = '?';
			}
			$question_marks = implode(',', $question_marks);
			$sql = "INSERT INTO {$table_name} ({$keys}) VALUES ({$question_marks})";
			$ret = $this->db->execute($sql, $fields_vals);
			if ($ret !== false) {
				if (static::$auto_increment) {
					$ret = $this->db->getLastInsertId("{$table_name}_{$primary}_seq");
					if ($ret) {
						$this->fields[$primary] = $ret;
					}
				} else {
					$ret = $this->fields[$primary];
				}
			}
		} else {
			$sets = array();
			$cols_count = count($fields_keys);
			foreach ($fields as $k => $v) {
				$sets[] = "{$k} = ?";
			}
			$sets = implode(', ', $sets);
			$sql = "UPDATE {$table_name} SET {$sets} WHERE {$primary} = ?";
			$input_parameters = $fields_vals;
			$input_parameters[] = $fields[$primary];
			$ret = $this->db->execute($sql, $input_parameters);
		}
		$this->afterSave($ret);
		return $ret;
	}

	public function remove() {
		if ($this->read_only === true) {
			return false;
		}
		$table_name = static::$table_name;
		$primary = static::$primary;
		$fields = $this->fields;
		$sql = "DELETE FROM {$table_name} WHERE {$primary} = ?";
		$ret = $this->db->execute($sql, array($fields[$primary]));
		if ($ret !== false) {
			$this->clearCache();
		}
		return $ret;
	}

	protected function load() {
		$vals = false;
		if ($this->read_only === true) {
			$vals = $this->loadCache();
		}
		if ($vals === false) {
			$vals = $this->loadDb();
			if (is_array($vals)) {
				$this->saveCache();
			}
		}
		$this->onLoad($vals);
		return $vals;
	}

	protected function loadCache() {
		$cache = new HashCache();
		$key = $this->genCacheKey();
		$vals = $cache->get($key);
		if ($vals) {
			$this->setValues($vals);
			$this->exists = true;
		} else if (is_array($vals)) {
			$this->exists = false;
		}
		return $vals;
	}

	protected function saveCache() {
		$cache = new HashCache();
		$key = $this->genCacheKey(); 
		return $cache->set($key, $this->exists ? $this->fields : array());
	}

	protected function clearCache() {
		$cache = new HashCache();
		$key = $this->genCacheKey();
		return $cache->delete($key);
	}

	protected function genCacheKey() {
		return strtolower(static::$table_name . '_' . $this->fields[static::$primary]);
	}

	protected function loadDb() {
		$table_name = static::$table_name;
		$primary = static::$primary;
		$fields = $this->fields;
		$sql = "SELECT * FROM {$table_name} WHERE {$primary} = ? LIMIT 1";
		if ($this->read_only === 'lock') {
			$sql .= " FOR UPDATE";
		}
		$vals = $this->db->fetch($sql, array($fields[$primary]));
		if ($vals) {
			$this->setValues($vals);
			$this->exists = true;
		} else {
			$this->exists = false;
		}
		return $vals;
	}

	protected function get($key) {
		return $this->fields[$key];
	}

	protected function set($key, $val) {
		return array_key_exists($key, $this->fields) ? ($this->fields[$key] = $val) : false;
	}

	protected function setValues(array $vals) {
		foreach ($vals as $key => $val) {
			if (array_key_exists($key, $this->fields)) {
				$this->fields[$key] = $val;
			}
		}
	}

	protected function beforeSave() {
		if (array_key_exists('create_ts', $this->fields)) {
			if (!$this->fields['create_ts']) {
				$this->fields['create_ts'] = time();
			}
		}
		if (array_key_exists('update_ts', $this->fields)) {
			$this->fields['update_ts'] = time();
		}
		return true;
	}

	protected function afterSave($status) {
		if ($status !== false) {
			$this->exists = true;
			$this->saveCache();
		}
		return true;
	}

	protected function onLoad($vals) {
		return false;
	}
}
?>
