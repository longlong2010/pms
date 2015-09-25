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

	protected function renderHtml(array $view) {
		$user_id = $this->user->getUserId();
		$name = $this->user->getName();
		$department = $this->department->getName();
		$member_util = new PmsProjectMemberDO(null, true);
		$project_count = $member_util->getUserProjectCount($user_id);
		
		parent::renderHtml(array_merge(array(
			'user_id' => $user_id,
			'name' => $name,
			'department' => $department,
			'project_count' => $project_count,
		), $view));
	}
}
