<?php
namespace app\pms;

use mvc\Controller;

class DepartmentController extends PmsController {

	const DEPARTMENT_SIZE = 10;

	public function indexAction($param) {
		$user_id = $this->user->getUserId();
		$name = $this->user->getName();
		$department = $this->department->getName();

		$department_util = new PmsDepartmentDO(null, true);
		$size = self::DEPARTMENT_SIZE;
		$count = $department_util->getDepartmentCount();
		
		$page = 1;
		$pages = ceil($count / $size);
		$page = $page <= $pages ? $page : $pages;
		$offset = ($page - 1) * $size;
		$department_list = $department_util->getDepartmentList($offset, $size);

		$departments = array();

		foreach ($department_list as $id) {
			$department_do = new PmsDepartmentDO($id, true);
			$data = array();
			$data['id'] = $id;
			$data['name'] = $department_do->getName();
			$departments[] = $data;
		}

		$this->renderHtml(array(
			'user_id' => $user_id,
			'name' => $name,
			'department' => $department,
			'departments' => $departments,
			'phtml'	=> 'pms/department.phtml',
		));
	}

	public function createAction($param) {
		$args = $_POST;
		$result = array(
			'success' => PmsDepartment::create($args) != false,
			'uri' => '/department/',
		);
		$this->renderJson($result);	
	}

	public function deleteAction($param) {
		$args = $_GET;
		$result = array(
			'success' => PmsDepartment::delete($args) != false,
			'uri' => '/department/',
		);
		$this->renderJson($result);	
	}
}
