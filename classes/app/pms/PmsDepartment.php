<?php
namespace app\pms;
use session\Cookie;
use db\DbCommander;

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

	public static function create(array $param) {
		DbCommander::startTransation();
		$department_do = new PmsDepartmentDO(null, false);
		$department_do->setName($param['name']);
		$ret = $department_do->save();
		DbCommander::endTransation($ret);
		return $ret;
	}

	public static function delete(array $param) {
		DbCommander::startTransation();
		$department_do = new PmsDepartmentDO($param['id'], false);
		$ret = $department_do->remove();
		DbCommander::endTransation($ret);
		return $ret;
	}
}
