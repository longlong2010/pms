<?php
namespace app\pms;

use mvc\Controller;

class ProjectController extends PmsController {

	const PROJECT_SIZE = 10;

	public function indexAction($param) {

		$list = $this->_list($page = 1);
		$projects = $list['projects'];
		
		$this->renderHtml(array(
			'action' => 'list',
			'projects' => $projects,
			'phtml' => 'pms/project.phtml',
		));
	}

	public function viewAction($param) {
		$project_id = $param['p'];
		$page = 1;
		$project_do = new PmsProjectDO($project_id, true);
		$project = array();
		$project['name'] = $project_do->getName();
		$project['code'] = $project_do->getCode();

		$member_util = new PmsProjectMemberDO(null, true);
		$size = self::PROJECT_SIZE;
		$count = $member_util->getProjectMemberCount($project_id);
		$pages = ceil($count / $size);
		$page = $page <= $pages ? $page : $pages;
		$offset = ($page - 1) * $size;
		$member_list = $member_util->getProjectMemberList($project_id, $offset, $size);

		$members = array();
		foreach ($member_list as $user_id) {
			$user_do = new PmsUserDO($user_id, true);
			$data = array();
			$data['user_id'] = $user_id;
			$data['name'] = $user_do->getName();
			$members[] = $data;
		}

		$user_util = new PmsUserDO(null, true);
		$user_list = $user_util->getUserList(0, 9999);

		$users = array();
		foreach ($user_list as $user_id) {
			if (in_array($user_id, $member_list)) {
				continue;
			}
			$user_do = new PmsUserDO($user_id, true);
			$data = array();
			$data['user_id'] = $user_id;
			$data['name'] = $user_do->getName();
			$users[] = $data;
		}
		
		$this->renderHtml(array(
			'action' => 'view',
			'members' => $members,
			'project' => $project,
			'users' => $users,
			'phtml' => 'pms/project.phtml',
		));
	}

	public function addAction($param) {
		$user_util = new PmsUserDO(null, true);
		$user_list = $user_util->getUserList(0, 9999);

		$users = array();

		foreach ($user_list as $user_id) {
			$user_do = new PmsUserDO($user_id, true);
			$data = array();
			$data['user_id'] = $user_id;
			$data['name'] = $user_do->getName();
			$users[] = $data;
		}

		$this->renderHtml(array(
			'action' => 'add',
			'users' => $users,
			'phtml' => 'pms/project.phtml',
		));
	}

	public function createAction($param) {
		$args = $_POST;
		$result = array(
			'success' => PmsProject::create($args) != false,
			'uri' => '/project/',
		);
		$this->renderJson($result);	
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
			$user_do = new PmsUserDO($project_do->getManagerId(), true);
			$data = array();

			$data['project_id'] = $project_id;
			$data['code'] = $project_do->getCode();
			$data['name'] = $project_do->getName();
			$data['user'] = $user_do->getName();
			$data['start'] = $project_do->getStart();
			$data['finish'] = $project_do->getFinish();

			$projects[] = $data;
		}

		return array('projects' => $projects);
	}
}
