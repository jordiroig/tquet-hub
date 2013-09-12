<?php

	Class User extends CI_Model
	{
		private $username;
		
		function __construct()
		{
			parent::__construct();
			$this->username = '';
		}
		
		function initialize($username)
		{
			$this->username = $username;	
		}
		
		function get_info()
		{
			$this -> db -> select('id_usuari, id_festival, nom, cognoms, email');
			$this -> db -> from('Usuaris');
			$this -> db -> where('email', $this->username);
			$this -> db -> limit(1);
			$query = $this-> db ->get();
			if($query -> num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function check_user($email)
		{
			$this -> db -> select('id_usuari, id_festival, nom, cognoms, email');
			$this -> db -> from('Usuaris');
			$this -> db -> where('email', $email);
			$this -> db -> limit(1);
			$query = $this-> db ->get();
			if($query -> num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
	}

?>