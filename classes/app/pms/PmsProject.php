<?php
namespace app\pms;
use db\DbCommander;

class PmsProject {
	private $do;
	private $project_id;

	public function __construct($project_id) {
		$this->project_id = $project_id;
		$this->do = new PmsProjectDO($project_id, true);
	}

	public function getCode() {
		return $this->do->getCode();
	}

	public function getManagerId() {
		return $this->do->getManagerId();
	}

	public function getName() {
		return $this->do->getName();
	}

	public function getStart() {
		return $this->do->getStart();
	}

	public function getFinish() {
		return $this->do->getFinish();
	}

	public static function create(array $param) {
		DbCommander::startTransation();
		$project_do = new PmsProjectDO(null, false);
		$project_do->setCode($param['code']);
		$project_do->setName($param['name']);
		$project_do->setManagerId($param['manager_id']);
		$project_do->setStart($param['start']);
		$project_do->setFinish($param['finish']);
		$project_id = $project_do->save();
		if ($project_id) {
			$member_do = new PmsProjectMemberDO(null, false);
			$member_do->setProjectId($project_id);
			$member_do->setUserId($param['manager_id']);
			$ret = $member_do->save();
		}
		DbCommander::endTransation($ret);
		return $ret;
	}
}
