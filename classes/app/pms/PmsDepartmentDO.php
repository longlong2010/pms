<?php
namespace app\pms;
use db\DataObject;
class PmsDepartmentDO extends DataObject {

	protected $fields = array(
		'id'	=>	null,
		'name'	=>	null,
	);

	protected static $primary = 'id';
	protected static $table_name = 'PmsDepartment';
	protected static $auto_increment = true;

	public function getName() {
		return $this->get('name');
	}

	public function setName($name) {
		return $this->set('name', $name);
	}

	public function getDepartmentList($offset = 0, $size = 10) {
		$table_name = static::$table_name;
		$sql = "SELECT id FROM {$table_name} ORDER BY id ASC LIMIT {$size} OFFSET {$offset}";
		$result = $this->db->fetchAll($sql);
		$list = array();
		if ($result) {
			foreach ($result as $row) {
				$list[] = $row['id'];
			}
		}
		return $list;
	}

	public function getDepartmentCount() {
		$table_name = static::$table_name;
		$sql = "SELECT COUNT(*) AS m FROM {$table_name}";
		$result = $this->db->fetch($sql);
		return $result ? $result['m'] : false;
	}
}
