<?php
	class Users_model extends CI_Model {
		function __construct(){
			parent::__construct();
			$this->load->database();
		}
 
		public function login($username, $password){
			$query = $this->db->get_where('users', array('username'=>$username, 'password'=>$password));
			return $query->row_array();
		}
 
	}
?>