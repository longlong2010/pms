<?php
namespace app\pms;
use session\Cookie;

class PmsUser {

	private $do;
	private $user_id;

	const LOGIN_COOKIE = 'L';
	const SING_KEY = '2c71d6b9573403b43463d638db1bef90';
	const SESSION_EXPIRE = 8640000;
	
	public function __construct($user_id = 0) {
		if (!$user_id) {
			$user_id = $this->loadSection();
		}
		if ($user_id > 0) {
			$this->user_id = $user_id;
		}
		$this->do = new PmsUserDO($user_id, true);
	}

	public function getName() {
		return $this->do->getName();
	}

	public function getEmail() {
		return $this->do->getEmail();
	}

	public function getUserId() {
		return $this->user_id;
	}

	public function login($email, $password) {
		if ($this->user_id) {
			return true;
		}
		$user_id = $this->do->login($email, $password);
		if ($user_id > 0) {
			$this->user_id = $user_id;
			$this->saveSection();
			return true;
		}
		return false;
	}

	public function logout() {
		if ($this->user_id) {
			$cookie = new Cookie(self::LOGIN_COOKIE);
			$cookie->remove();
		}
		return true;
	}

	protected function loadSection() {
		$cookie = new Cookie(self::LOGIN_COOKIE);
		$user_id = intval($cookie->get('myuid'));
		$sign = $cookie->get('sign');
		$ts = $cookie->get('ts');
		if ($user_id > 0 && $sign) {
			return self::genSign($user_id, $ts) == $sign ? $user_id : 0;
		}
		return 0;
	}

	protected function saveSection() {
		$user_id = $this->user_id;
		if ($user_id > 0) {
			$cookie = new Cookie(self::LOGIN_COOKIE, self::SESSION_EXPIRE, '/');
			$ts = time();
			$sign = self::genSign($user_id, $ts);
			$cookie->set('ts', $ts);
			$cookie->set('sign', $sign);
			$cookie->set('myuid', $user_id);
			return $cookie->save();
		}
		return false;
	}

	protected static function genSign($user_id, $ts) {
		if ($ts > time() || $user_id <= 0) {
			return false;
		}
		return md5($user_id . $ts . self::SING_KEY);
	}
}
?>
