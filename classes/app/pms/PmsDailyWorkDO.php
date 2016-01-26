<?php
namespace app\pms;
use db\DataObject;
class PmsDailyWorkDO extends DataObject {

	protected $fields = array(
		'work_id'	=>	null,
		'project_id' =>	null,
		'user_id' => null,
		'date' => null,
		'content'	=>	null,
		'completion'	=>	null,
		'hours' => null,
		'description' => null,
	);

	protected static $primary = 'work_id';
	protected static $table_name = 'PmsDailyWork';
	protected static $auto_increment = true;

	public function getProjectId() {
		return $this->get('project_id');
	}

	public function setProjectId($project_id) {
		return $this->set('project_id', $project_id);
	}

	public function getUserId() {
		return $this->get('user_id');
	}

	public function setUserId($user_id) {
		return $this->set('user_id', $user_id);
	}

	public function getDate() {
		return $this->get('date');
	}

	public function setDate($date) {
		return $this->set('date', $date);
	}

	public function getContent() {
		return $this->get('content');
	}

	public function setContent($content) {
		return $this->set('content', $content);
	}

	public function getCompletion() {
		return $this->get('completion');
	}

	public function setCompletion($completion) {
		return $this->set('completion', $completion);
	}

	public function getHours() {
		return $this->get('hours');
	}

	public function setHours($hours) {
		return $this->set('hours', $hours);
	}

	public function getDescription() {
		return $this->get('description');
	}

	public function setDescription($description) {
		return $this->set('description', $description);
	}

	public function getUserDailyWorkList($user_id, $offset = 0, $size = 10) {
		$table_name = static::$table_name;
		$sql = "SELECT work_id FROM {$table_name} WHERE user_id = ? ORDER BY work_id DESC LIMIT {$size} OFFSET {$offset}";
		$result = $this->db->fetchAll($sql, array($user_id));
		$list = array();
		if ($result) {
			foreach ($result as $row) {
				$list[] = $row['work_id'];
			}
		}
		return $list;
	}

	public function getUserDailyWorkListByDate($user_id, $start, $finish) {
		$table_name = static::$table_name;
		$sql = "SELECT work_id FROM {$table_name} WHERE user_id = ? AND date >= ? AND date <= ? ORDER BY work_id ASC";
		$result = $this->db->fetchAll($sql, array($user_id, $start, $finish));
		$list = array();
		if ($result) {
			foreach ($result as $row) {
				$list[] = $row['work_id'];
			}
		}
		return $list;
	}

	public function getUserDailyWorkCount($user_id) {
		$table_name = static::$table_name;
		$sql = "SELECT COUNT(*) AS m FROM {$table_name} WHERE user_id = ?";
		$result = $this->db->fetch($sql, array($user_id));
		return $result ? $result['m'] : false;
	}

	public function getProjectDailyWorkList($project_id, $offset = 0, $size = 10) {
		$table_name = static::$table_name;
		$sql = "SELECT work_id FROM {$table_name} WHERE project_id = ? ORDER BY work_id DESC LIMIT {$size} OFFSET {$offset}";
		$result = $this->db->fetchAll($sql, array($project_id));
		$list = array();
		if ($result) {
			foreach ($result as $row) {
				$list[] = $row['work_id'];
			}
		}
		return $list;
	}

	public function getProjectDailyWorkCount($project_id) {
		$table_name = static::$table_name;
		$sql = "SELECT COUNT(*) AS m FROM {$table_name} WHERE project_id = ?";
		$result = $this->db->fetch($sql, array($project_id));
		return $result ? $result['m'] : false;
	}
}
