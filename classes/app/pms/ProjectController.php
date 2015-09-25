<?php
namespace app\pms;

use mvc\Controller;

class ProjectController extends PmsController {

	const PROJECT_SIZE = 10;

	public function indexAction($param) {
		$user_id = $this->user->getUserId();
		$name = $this->user->getName();
		$department = $this->department->getName();

		$list = $this->_list($page = 1);
	}

	protected function _list($page) {
		$project_util = new PmsProjectDO(null, true);
		$size = self::PROJECT_SIZE;
		$count = $project_util->getProjectCount();
		$pages = ceil($count / $size);
		$page = $page <= $pages ? $page : $pages;
		$offset = ($page - 1) * $size;
		$project_list = $project_util->getProjectList($offset, $size);

		$projects = array();

		foreach ($project_list as $project_id) {
			$project_do = new PmsProjectDO($project_id, true);
			$data = array();

			$data['project_id'] = $project_id;
			$data['code'] = $project_do->getCode();
			$data['name'] = $project_do->getName();
			$data['start'] = $project_do->getStart();
			$data['finish'] = $project_do->getFinish();

			$projects[] = $data;
		}

		return array('projects' => $projects);
	}
}
