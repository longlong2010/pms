<?php
namespace app\pms;

use mvc\Controller;
use html\Pagination;

class DailyWorkController extends PmsController {
	
	const WORK_SIZE = 10;

	public function indexAction($param) {
		$user_id = $this->user->getUserId();
		header("Location: /dailywork/list/u/{$user_id}");
		exit;
	}

	public function listAction($param) {
		$user_id = $param['u'];
		$user_do = new PmsUserDO($user_id, true);
		$user = array();
		$user['name'] = $user_do->getName();
		$page = isset($param['page']) ? ($param['page'] >= 1 ? intval($param['page']) : 1) : 1;
		$list = $this->_list($user_id, $page);
		$this->renderHtml(array(
			'user' => $user,
			'works' => $list['works'],
			'phtml'	=> 'pms/dailywork.phtml',
			'pagination' => $list['pagination'],
		));
	}

	public function writeAction($param) {

		$project_util = new PmsProjectDO(null, true);
		$codes = $project_util->getProjectCodeList();
		$this->renderHtml(array(
			'action' => 'write',
			'codes' => $codes,
			'phtml'	=> 'pms/dailywork.phtml',
		));
	}

	public function editAction($param) {
		$work_do = new PmsDailyWorkDO($param['d'], true);
		$project_do = new PmsProjectDO($work_do->getProjectId(), true);
		$work = array();
		$work['work_id'] = $param['d'];
		$work['code'] = $project_do->getCode();
		$work['date'] = $work_do->getDate();
		$work['content'] = $work_do->getContent();
		$work['completion'] = $work_do->getCompletion();
		$work['hours'] = $work_do->getHours();
		$work['description'] = $work_do->getDescription();

		$project_util = new PmsProjectDO(null, true);
		$codes = $project_util->getProjectCodeList();
		$this->renderHtml(array(
			'action' => 'write',
			'codes' => $codes,
			'work' => $work,
			'phtml'	=> 'pms/dailywork.phtml',
		));
	}

	public function exportAction($param) {
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
			'action' => 'export',
			'users' => $users,
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

	public function modifyAction($param) {
		$args = $_POST;
		$user_id = $this->user->getUserId();
		$args['user_id'] = $user_id;
		$result = array(
			'success' => PmsDailyWork::modify($args) !== false,
			'uri' => '/dailywork/',
		);
		$this->renderJson($result);
	}

	public function deleteAction($param) {
		$args = $_GET;
		$result = array(
			'success' => PmsDailyWork::delete($args) !== false,
			'uri' => '/dailywork/',
		);
		$this->renderJson($result);	
	}

	public function downloadAction($param) {
		$user_id = $_GET['user_id'];
		$start = $_GET['start'];
		$finish = $_GET['finish'];
		
		$user_do = new PmsUserDO($user_id, true);

		$work_util = new PmsDailyWorkDO(null, true);
		$work_list = $work_util->getUserDailyWorkListByDate($user_id, $start, $finish);
		
		$works = array();
		foreach ($work_list as $work_id) {
			$work_do = new PmsDailyWorkDO($work_id, true);
			$project_do = new PmsProjectDO($work_do->getProjectId(), true);
			$data = array();
			$data['code'] = $project_do->getCode();
			$data['date'] = $work_do->getDate();
			$data['content'] = iconv('utf-8', 'gbk', $work_do->getContent());
			$data['completion'] = iconv('utf-8', 'gbk', $work_do->getCompletion());
			$data['hours'] = $work_do->getHours();
			$data['description'] = iconv('utf-8', 'gbk', $work_do->getDescription());
			$works[] = implode("\t", $data);
		}

		header('Content-Type: application/vnd.ms-excel;charset=utf-8');
		header('Content-Disposition:filename=' . implode('-', array(iconv('utf-8', 'gbk', $user_do->getName()), $start, $finish)) . '.xls');
		echo iconv('utf-8', 'gbk', "项目代号\t日期\t工作内容\t完成情况\t工时\t问题描述和下一步计划\n");
		echo implode("\n", $works);
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
			$data['date'] = $work_do->getDate();
			$data['content'] = $work_do->getContent();
			$data['completion'] = $work_do->getCompletion();
			$data['hours'] = $work_do->getHours();
			$data['description'] = $work_do->getDescription();
			$works[] = $data;
		}

		$pattern = "/dailywork/list/u/{$user_id}/page/{page}/";
		$pagination = new Pagination($pattern, $page, $pages);
		return array('works' => $works, 'pagination' => $pagination->__toString());
	}
}
