<?php
namespace app\pms;

use mvc\Controller;

class PmsController extends Controller {
	protected $user;
	protected $department;

	public function __construct() {
		$this->user = new PmsUser();
		$user_id = $this->user->getUserId();
		if (!$user_id) {
			header('Location: /login/');
			exit;
		}
		$this->department = new PmsDepartment($this->user->getDepartmentId());
	}
}
