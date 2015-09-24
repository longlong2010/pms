<?php
namespace app\pms;

use mvc\Controller;

class UserController extends PmsController {

	const USER_SIZE = 10;

	public function indexAction($param) {
		$user_id = $this->user->getUserId();
		$name = $this->user->getName();
		$department = $this->department->getName();
		$list = $this->_list($page = 1);
		$users = $list['users'];

		$this->renderHtml(array(
			'action' => 'list',
			'user_id' => $user_id,
			'name' => $name,
			'department' => $department,
			'users' => $users,
			'phtml'	=> 'pms/user.phtml',
		));
	}

	public function editAction($param) {
		$user_id = $this->user->getUserId();
		$name = $this->user->getName();
		$department = $this->department->getName();

		$user_do = new PmsUserDO($param['u'], true);
		$department_do = new PmsDepartmentDO($user_do->getDepartmentId(), true);
		$user = array();
		$user['user_id'] = $param['u'];
		$user['name'] = $user_do->getName();
		$user['email'] = $user_do->getEmail();
		$user['department'] = $department_do->getName();

		$department_util = new PmsDepartmentDO(null, true);
		$list = $department_util->getDepartmentList(0, 100);

		$this->renderHtml(array(
			'action' => 'edit',
			'user_id' => $user_id,
			'name' => $name,
			'department' => $department,
			'user' => $user,
			'phtml'	=> 'pms/user.phtml',
		));
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

		return array('users' => $users);
	}
}
