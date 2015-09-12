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
			$data = array();
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
