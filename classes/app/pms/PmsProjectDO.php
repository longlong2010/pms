<?php
namespace app\pms;
use db\DataObject;
class PmsProjectDO extends DataObject {

	protected $fields = array(
		'project_id'	=>	null,
		'code' => null,
		'name'	=>	null,
		'manager_id' => null,
		'start' => null,
		'finish' => null,
	);

	protected static $primary = 'project_id';
	protected static $table_name = 'PmsProject';
	protected static $auto_increment = true;

	public function getCode() {
		return $this->get('code');
	}

	public function setCode($code) {
		return $this->set('code', $code);
	}

	public function getName() {
		return $this->get('name');
	}

	public function setName($name) {
		return $this->set('name', $name);
	}

	public function getManagerId() {
		return $this->get('manager_id');
	}

	public function setManagerId($manager_id) {
		return $this->set('manager_id', $manager_id);
	}

	public function getStart() {
		return $this->get('start');
	}

	public function setStart($start) {
		return $this->set('start', $start);
	}

	public function getFinish() {
		return $this->get('finish');
	}

	public function setFinish($finish) {
		return $this->set('finish', $finish);
	}
}
