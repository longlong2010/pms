<?php
namespace app\pms;
use db\DbCommander;

class PmsProjectMember {

	private $do;
	private $id;

	public function __construct($id) {
		$this->id = $id;
		$this->do = new PmsProjectMemberDO($id, true);
	}

	public function getProjectId() {
		return $this->do->getProjectId();
	}

	public function getUserId() {
		return $this->do->getUserId();
	}

	public static function create(array $param) {
		DbCommander::startTransation();
		$member_do = new PmsProjectMemberDO(null, false);
		$member_do->setProjectId($param['p']);
		$member_do->setUserId($param['u']);
		$ret = $member_do->save();
		DbCommander::endTransation($ret);
		return $ret;
	}

	public static function delete(array $param) {
		$member_util = new PmsProjectMemberDO(null, false);
		$id = $member_util->getId($param['p'], $param['u']);

		DbCommander::startTransation();
		$member_do = new PmsProjectMemberDO($id, false);
		$ret = $member_do->remove();
		DbCommander::endTransation($ret);
		return $ret;
	}

}
