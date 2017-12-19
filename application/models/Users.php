<?php
class Users extends CI_Model{

	function get_users(){
		return $this->db->get('users')->result_array();
	}

}