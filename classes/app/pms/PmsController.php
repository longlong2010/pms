<?php
namespace app\pms;

use mvc\Controller;
use app\PmsUser;

class PmsController extends Controller {
	protected $user;

	public function __construct() {
		$this->user = new PmsUser();
		$user_id = $this->user->getUserId();
		if (!$user_id) {
			header('Location: /login/');
			exit;
		}
	}
}
