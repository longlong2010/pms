<?php
namespace app\pms;
use db\DataObject;
class PmsUserDO extends DataObject {

	protected $fields = array(
		'user_id'	=>	null,
		'email'		=>	null,
		'password'	=>	null,
		'name'	=>	null,
		'department_id' => null,
	);

	protected static $primary = 'user_id';
	protected static $table_name = 'PmsUser';
	protected static $auto_increment = true;

	public function getEmail() {
		return $this->get('email');
	}

	public function setEmail($email) {
		return $this->set('email', $email);
	}

	public function getPassword() {
		return $this->get('password');
	}

	public function setPassword($password) {
		return $this->set('password', md5($password));
	}

	public function getName() {
		return $this->get('name');
	}

	public function setName($name) {
		return $this->set('name', $name);
	}

	public function getDepartmentId() {
		return $this->get('department_id');
	}

	public function setDepartmentId($department_id) {
		return $this->set('department_id', $department_id);
	}

	public function login($email, $password) {
		$password = md5($password);
		$table_name = static::$table_name;
		$sql = "SELECT user_id FROM {$table_name} WHERE email = ? AND password = ? LIMIT 1";
		$row = $this->db->fetch($sql, array($email, $password));
		return $row ? $row['user_id'] : false;
	}
}
