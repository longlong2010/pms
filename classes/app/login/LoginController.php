<?php
namespace app\login;

use mvc\Controller;
use app\PmsUser;

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

	public function signAction($param) {
		$user = new PmsUser();
		$result = array(
			'success' => $user->login($_POST['email'], $_POST['password']),
			'uri' => '/dailywork/',
		);
		echo json_encode($result);
	}
}
