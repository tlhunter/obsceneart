<?php
class Language_model extends CI_Model {

    function __construct() {
        parent::__construct();
		$this->table_name = 'languages';
    }

	function select_single($language_id) {
		$query = $this->db->query("SELECT * FROM languages WHERE id = " . $this->db->escape($user_id) . " LIMIT 1");
		return $query->row_array();
	}

	function select_all() {
		$query = $this->db->query('SELECT * FROM languages ORDER BY id');
		return $query->result_array();
	}

}