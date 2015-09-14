<?php
namespace app\pms;
use db\DbCommander;

class PmsDailyWork {

	private $do;
	private $work_id;

	public function __construct($work_id) {
		$this->work_id = $work_id;
		$this->do = new PmsDailyWorkDO($work_id, true);
	}

	public static function create(array $param) {
		$project_util = new PmsProjectDO(null, true);
		$project_id = $project_util->getProjectId($param['code']);
		if (!$project_id) {
			return false;
		}
		DbCommander::startTransation();
		$work_do = new PmsDailyWorkDO(null, false);
		$work_do->setProjectId($project_id);
		$work_do->setUserId($param['user_id']);
		$work_do->setContent($param['content']);
		$work_do->setCompletion($param['completion']);
		$work_do->setHours($param['hours']);
		$work_do->setDescription($param['description']);
		$ret = $work_do->save();
		DbCommander::endTransation($ret);
		return $ret;
	}
}
