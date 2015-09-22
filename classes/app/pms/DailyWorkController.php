<?php
namespace app\pms;

use mvc\Controller;

class DailyWorkController extends PmsController {
	
	const WORK_SIZE = 10;

	public function indexAction($param) {
		$user_id = $this->user->getUserId();
		$name = $this->user->getName();
		$department = $this->department->getName();

		$list = $this->_list($user_id, $page = 1);
		$works = $list['works'];
		$this->renderHtml(array(
			'user_id' => $user_id,
			'name' => $name,
			'works' => $works,
			'department' => $department,
			'phtml'	=> 'pms/dailywork.phtml',
		));
	}

	public function writeAction($param) {
		$user_id = $this->user->getUserId();
		$name = $this->user->getName();
		$department = $this->department->getName();

		$project_util = new PmsProjectDO(null, true);
		$codes = $project_util->getProjectCodeList();
		$this->renderHtml(array(
			'action' => 'write',
			'user_id' => $user_id,
			'name' => $name,
			'works' => $works,
			'codes' => $codes,
			'department' => $department,
			'phtml'	=> 'pms/dailywork.phtml',
		));
	}

	public function createAction($param) {
		$args = $_POST;
		$user_id = $this->user->getUserId();
		$args['user_id'] = $user_id;
		$result = array(
			'success' => PmsDailyWork::create($args) != false,
			'uri' => '/dailywork/',
		);
		$this->renderJson($result);	
	}

	protected function _list($user_id, $page) {
		$work_util = new PmsDailyWorkDO(null, true);
		$size = self::WORK_SIZE;
		$count = $work_util->getUserDailyWorkCount($user_id);
		$pages = ceil($count / $size);
		$page = $page <= $pages ? $page : $pages;
		$offset = ($page - 1) * $size;
		$work_list = $work_util->getUserDailyWorkList($user_id, $offset, $size);
		
		$works = array();

		foreach ($work_list as $work_id) {
			$work_do = new PmsDailyWorkDO($work_id, true);
			$project_do = new PmsProjectDO($work_do->getProjectId(), true);
			$data = array();
			$data['code'] = $project_do->getCode();
			$data['work_id'] = $work_id;
			$data['content'] = $work_do->getContent();
			$data['completion'] = $work_do->getCompletion();
			$data['hours'] = $work_do->getHours();
			$data['description'] = $work_do->getDescription();
			$works[] = $data;
		}

		return array('works' => $works);
	}
}
