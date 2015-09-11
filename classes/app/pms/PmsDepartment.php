<?php
namespace app\pms;
use session\Cookie;

class PmsDepartment {

	private $do;
	private $department_id;

	public function __construct($department_id) {
		$this->department_id = $department_id;
		$this->do = new PmsDepartmentDO($department_id, true);
	}

	public function getName() {
		return $this->do->getName();
	}
}
