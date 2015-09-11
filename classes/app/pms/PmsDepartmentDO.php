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
}
