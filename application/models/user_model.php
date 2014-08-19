<?php
class User_model extends CI_Model {

    function __construct() {
        parent::__construct();
		$this->table_name = 'users';
    }

	function select_single($user_id) {
		$query = $this->db->query("SELECT users.id, users.username, users.email, users.banned, users.ban_reason, last_login, created, user_profiles.rank, user_profiles.aim, user_profiles.yahoo, user_profiles.website FROM `users` LEFT JOIN user_profiles ON users.id = user_profiles.user_id WHERE users.id = " . $this->db->escape($user_id) . "");
		return $query->row_array();
	}

	function select_single_by_username($username) {
		$query = $this->db->query("SELECT users.id, users.username, users.email, users.banned, users.ban_reason, last_login, created, user_profiles.rank, user_profiles.aim, user_profiles.yahoo, user_profiles.website FROM `users` LEFT JOIN user_profiles ON users.id = user_profiles.user_id WHERE users.username = " . $this->db->escape($username) . " AND users.activated = 1");
		return $query->row_array();
	}

	function select_all() {
		$query = $this->db->query('SELECT users.id, users.username, user_profiles.rank FROM users LEFT JOIN user_profiles ON user_profiles.user_id = users.id WHERE users.activated = 1 AND users.banned = 0 ORDER BY user_profiles.rank DESC, users.username');
		return $query->result_array();
	}

	function update_profile($user_id, $data) {
		if (isset($data['id'])) {
			unset($data['id']);
		}
		if (isset($data['user_id'])) {
			unset($data['user_id']);
		}
		$this->db->where('user_id', $user_id);
		return $this->db->update('user_profiles', $data);
	}

}