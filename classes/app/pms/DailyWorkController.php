<?php
namespace app\pms;

use mvc\Controller;

class DailyWorkController extends PmsController {
	
	public function indexAction($param) {

		$this->renderHtml(array(
			'phtml'	=> 'pms/dailywork.phtml',
		));
	}
}
