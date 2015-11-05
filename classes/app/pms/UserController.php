<?php
namespace app\pms;

use mvc\Controller;
use html\Pagination;

class UserController extends PmsController {

	const USER_SIZE = 10;

	public function indexAction($param) {
		$list = $this->_list($page = 1);

		$this->renderHtml(array(
			'action' => 'list',
			'users' => $list['users'],
			'phtml'	=> 'pms/user.phtml',
			'pagination' => $list['pagination'],
		));
	}

	public function listAction($param) {
		$page = isset($param['page']) ? ($param['page'] >= 1 ? intval($param['page']) : 1) : 1;
		$list = $this->_list($page);

		$this->renderHtml(array(
			'action' => 'list',
			'users' => $list['users'],
			'phtml'	=> 'pms/user.phtml',
			'pagination' => $list['pagination'],
		));
	}

	public function addAction($param) {

		$department_util = new PmsDepartmentDO(null, true);
		$list = $department_util->getDepartmentList(0, 100);

		$departments = array();
		foreach ($list as $department_id) {
			$department_do = new PmsDepartmentDO($department_id, true);
			$data = array();
			$data['id'] = $department_id;
			$data['name'] = $department_do->getName();
			$departments[] = $data;
		}

		$this->renderHtml(array(
			'action' => 'add',
			'departments' => $departments,
			'phtml'	=> 'pms/user.phtml',
		));
	}

	public function editAction($param) {

		$user_do = new PmsUserDO($param['u'], true);
		$user = array();
		$user['user_id'] = $param['u'];
		$user['name'] = $user_do->getName();
		$user['email'] = $user_do->getEmail();
		$user['department_id'] = $user_do->getDepartmentId();

		$department_util = new PmsDepartmentDO(null, true);
		$list = $department_util->getDepartmentList(0, 100);

		$departments = array();
		foreach ($list as $department_id) {
			$department_do = new PmsDepartmentDO($department_id, true);
			$data = array();
			$data['id'] = $department_id;
			$data['name'] = $department_do->getName();
			$departments[] = $data;
		}

		$this->renderHtml(array(
			'action' => 'edit',
			'departments' => $departments,
			'user' => $user,
			'phtml'	=> 'pms/user.phtml',
		));
	}

	public function createAction($param) {
		$args = $_POST;
		$result = array(
			'success' => PmsUser::create($args) != false,
			'uri' => '/user/',
		);
		$this->renderJson($result);	
	}

	public function modifyAction($param) {
		$args = $_POST;
		$result = array(
			'success' => PmsUser::modify($args) !== false,
			'uri' => '/user/',
		);
		$this->renderJson($result);	
	}

	public function deleteAction($param) {
		$args = $_GET;
		$result = array(
			'success' => PmsUser::delete($args) !== false,
			'uri' => '/user/',
		);
		$this->renderJson($result);	
	}

	protected function _list($page) {
		$user_util = new PmsUserDO(null, true);
		$size = self::USER_SIZE;
		$count = $user_util->getUserCount();
		$pages = ceil($count / $size);
		$page = $page <= $pages ? $page : $pages;
		$offset = ($page - 1) * $size;
		$user_list = $user_util->getUserList($offset, $size);

		$users = array();

		foreach ($user_list as $user_id) {
			$user_do = new PmsUserDO($user_id, true);
			$department_do = new PmsDepartmentDO($user_do->getDepartmentId(), true);
			$data = array();
			
			$data['user_id'] = $user_id;
			$data['name'] = $user_do->getName();
			$data['email'] = $user_do->getEmail();
			$data['department'] = $department_do->getName();

			$users[] = $data;
		}

		$pattern = "/user/list/page/{page}/";
		$pagination = new Pagination($pattern, $page, $pages);
		return array('users' => $users, 'pagination' => $pagination->__toString());
	}
}
