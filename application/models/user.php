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
			$this -> db -> select('u.id_usuari, u.id_festival, u.nom, u.cognoms, u.email, u.admin, f.nom as festival, f.any as edicio');
			$this -> db -> from('Usuaris u');
			$this -> db -> where('u.email', $this->username);
			$this -> db -> join('Festivals f', 'f.id_festival = u.id_festival');
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