<?php
namespace app\pms;

use mvc\Controller;
use html\Pagination;

class DepartmentController extends PmsController {

	const DEPARTMENT_SIZE = 10;

	public function indexAction($param) {
		$list = $this->_list($page = 1);
		$this->renderHtml(array(
			'departments' => $list['departments'],
			'phtml'	=> 'pms/department.phtml',
			'pagination' => $list['pagination'],
		));
	}

	public function listAction($param) {
		$page = isset($param['page']) ? ($param['page'] >= 1 ? intval($param['page']) : 1) : 1;
		$list = $this->_list($page);

		$this->renderHtml(array(
			'departments' => $list['departments'],
			'phtml'	=> 'pms/department.phtml',
			'pagination' => $list['pagination'],
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

	public function _list($page) {

		$department_util = new PmsDepartmentDO(null, true);
		$size = self::DEPARTMENT_SIZE;
		$count = $department_util->getDepartmentCount();
		
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
		
		$pattern = "/department/list/page/{page}/";
		$pagination = new Pagination($pattern, $page, $pages);
		return array('departments' => $departments, 'pagination' => $pagination->__toString());
	}
}
