<?php
namespace app\pms;

use mvc\Controller;
use app\pms\PmsUser;

class LoginController extends Controller {

	public function indexAction($param) {
		$user = new PmsUser();
		$user_id = $user->getUserId();
		if ($user_id) {
			header('Location: /dailywork/');
			exit;
		}
		$this->renderHtml(array(
			'phtml' => 'login/login.phtml',
		));
	}

	public function signinAction($param) {
		$user = new PmsUser();
		$result = array(
			'success' => $user->login($_POST['email'], $_POST['password']),
			'uri' => '/dailywork/',
		);
		$this->renderJson($result);
	}

	public function signoutAction($param) {
		$user = new PmsUser();
		$result = array(
			'success' => $user->logout(),
			'uri' => '/login/',
		);
		$this->renderJson($result);
	}
}
