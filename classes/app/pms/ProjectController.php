<?php
namespace app\pms;

use mvc\Controller;
use html\Pagination;

class ProjectController extends PmsController {

	const PROJECT_SIZE = 10;

	public function indexAction($param) {
		$list = $this->_list($page = 1);
		
		$this->renderHtml(array(
			'action' => 'list',
			'projects' => $list['projects'],
			'phtml' => 'pms/project.phtml',
			'pagination' => $list['pagination'],
		));
	}

	public function listAction($param) {
		$page = isset($param['page']) ? ($param['page'] >= 1 ? intval($param['page']) : 1) : 1;
		$list = $this->_list($page);

		$this->renderHtml(array(
			'action' => 'list',
			'projects' => $list['projects'],
			'phtml' => 'pms/project.phtml',
			'pagination' => $list['pagination'],
		));
	}

	public function viewMemberAction($param) {
		$project_id = $param['p'];
		$page = isset($param['page']) ? ($param['page'] >= 1 ? intval($param['page']) : 1) : 1;

		$project_do = new PmsProjectDO($project_id, true);
		$manager_id = $project_do->getManagerId();
		$project = array();
		$project['project_id'] = $project_id;
		$project['name'] = $project_do->getName();
		$project['code'] = $project_do->getCode();
		$project['manager_id'] = $manager_id;

		$member_util = new PmsProjectMemberDO(null, true);
		$size = self::PROJECT_SIZE;
		$count = $member_util->getProjectMemberCount($project_id);
		$pages = ceil($count / $size);
		$page = $page <= $pages ? $page : $pages;
		$offset = ($page - 1) * $size;
		$member_list = $member_util->getProjectMemberList($project_id, $offset, $size);
		array_unshift($member_list, $manager_id);

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

		$pattern = "/project/view/p/{$project_id}/member/page/{page}/";
		$pagination = new Pagination($pattern, $page, $pages);
		
		$this->renderHtml(array(
			'action' => 'view_member',
			'members' => $members,
			'project' => $project,
			'users' => $users,
			'phtml' => 'pms/project.phtml',
			'pagination' => $pagination->__toString(),
		));
	}

	public function viewDailyworkAction($param) {
		$project_id = $param['p'];
		$page = isset($param['page']) ? ($param['page'] >= 1 ? intval($param['page']) : 1) : 1;

		$work_util = new PmsDailyWorkDO(null, true);
		$size = self::PROJECT_SIZE;
		$count = $work_util->getProjectDailyWorkCount($project_id);
		$pages = ceil($count / $size);
		$page = $page <= $pages ? $page : $pages;
		$offset = ($page - 1) * $size;
		$work_list = $work_util->getProjectDailyWorkList($project_id, $offset, $size);

		$works = array();

		foreach ($work_list as $work_id) {
			$work_do = new PmsDailyWorkDO($work_id, true);
			$project_do = new PmsProjectDO($work_do->getProjectId(), true);
			$user_do = new PmsUserDO($work_do->getUserId(), true);
			$data = array();
			$data['code'] = $project_do->getCode();
			$data['user'] = $user_do->getName();
			$data['work_id'] = $work_id;
			$data['content'] = $work_do->getContent();
			$data['completion'] = $work_do->getCompletion();
			$data['hours'] = $work_do->getHours();
			$data['date'] = $work_do->getDate();
			$data['description'] = $work_do->getDescription();
			$works[] = $data;
		}

		$pattern = "/project/view/p/{$project_id}/dailywork/page/{page}/";
		$pagination = new Pagination($pattern, $page, $pages);

		return $this->renderHtml(array(
			'action' => 'view_dailywork',
			'works' => $works,
			'pagination' => $pagination->__toString(),
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

	public function editAction($param) {
		$project_id = $param['p'];
		$project_do = new PmsProjectDO($project_id, true);

		$project = array();

		$project['id'] = $project_id;
		$project['code'] = $project_do->getCode();
		$project['name'] = $project_do->getName();
		$project['manager_id'] = $project_do->getManagerId();
		$project['start'] = $project_do->getStart();
		$project['finish'] = $project_do->getFinish();

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
			'action' => 'edit',
			'users' => $users,
			'project' => $project,
			'phtml' => 'pms/project.phtml',
		));

		echo 1;
	}

	public function createAction($param) {
		$args = $_POST;
		$result = array(
			'success' => PmsProject::create($args) != false,
			'uri' => '/project/',
		);
		$this->renderJson($result);	
	}

	public function modifyAction($param) {
		$args = $_POST;
		$result = array(
			'success' => PmsProject::modify($args) != false,
			'uri' => '/project/',
		);
		$this->renderJson($result);	
	}

	public function deleteAction($param) {
		$args = $_GET;
		$result = array(
			'success' => PmsProject::delete($args) != false,
			'uri' => '/project/',
		);
		$this->renderJson($result);	
	}

	public function memberAddAction($param) {
		$args = $_POST;
		$args['p'] = $param['p'];
		$result = array(
			'success' => PmsProjectMember::create($args) != false,
		);
		$this->renderJson($result);
	}

	public function memberDeleteAction($param) {
		$args = $_POST;
		$args['p'] = $param['p'];
		$result = array(
			'success' => PmsProjectMember::delete($args),
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

		$pattern = "/project/list/page/{page}/";
		$pagination = new Pagination($pattern, $page, $pages);
		return array('projects' => $projects, 'pagination' => $pagination->__toString());
	}
}
