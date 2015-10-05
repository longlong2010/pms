<?php
namespace app\pms;
use db\DataObject;

class PmsProjectMemberDO extends DataObject {

	protected $fields = array(
		'id'	=> null,
		'project_id' => null,
		'user_id' => null,
	);

	protected static $primary = 'id';
	protected static $table_name = 'PmsProjectMember';
	protected static $auto_increment = true;

	public function getProjectId() {
		return $this->get('project_id');
	}

	public function getUserId() {
		return $this->get('user_id');
	}

	public function setProjectId($project_id) {
		return $this->set('project_id', $project_id);
	}

	public function setUserId($user_id) {
		return $this->set('user_id', $user_id);
	}

	public function getUserProjectCount($user_id) {
		$table_name = static::$table_name;
		$sql = "SELECT COUNT(*) AS m FROM {$table_name} WHERE user_id = ?";
		$row = $this->db->fetch($sql, array($user_id));
		return $row ? $row['m'] : false;
	}

	public function getProjectMemberList($project_id, $offset = 0, $size = 10) {
		$table_name = static::$table_name;
		$sql = "SELECT user_id FROM {$table_name} WHERE project_id = ? ORDER BY user_id ASC LIMIT {$size} OFFSET {$offset}";
		$result = $this->db->fetchAll($sql, array($project_id)); 
		$list = array();
		if ($result) {
			foreach ($result as $row) {
				$list[] = $row['user_id'];
			}
		}
		return $list;
	}

	public function getProjectMemberCount($project_id) {
		$table_name = static::$table_name;
		$sql = "SELECT COUNT(*) AS m FROM {$table_name} WHERE project_id = ?";
		$row = $this->db->fetch($sql, array($project_id));
		return $row ? $row['m'] : false;
	}

	public function getId($project_id, $user_id) {
		$table_name = static::$table_name;
		$sql = "SELECT id FROM {$table_name} WHERE project_id = ? AND user_id = ?";
		$row = $this->db->fetch($sql, array($project_id, $user_id));
		return $row ? $row['id'] : false;
	}
}
