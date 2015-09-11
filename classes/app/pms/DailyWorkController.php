<?php
namespace app\pms;

use mvc\Controller;

class DailyWorkController extends PmsController {
	
	public function indexAction($param) {
		$user_id = $this->user->getUserId();
		$name = $this->user->getName();
		$department = $this->department->getName();
		$this->renderHtml(array(
			'user_id' => $user_id,
			'name' => $name,
			'department' => $department,
			'phtml'	=> 'pms/dailywork.phtml',
		));
	}
}
