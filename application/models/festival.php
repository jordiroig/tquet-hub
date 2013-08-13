<?php

	Class Festival extends CI_Model
	{
		private $festival_id;
	
		function __construct()
		{
			parent::__construct();
			$festival_id = '';
		}
	
	    function initialize($festival_id)
		{
			$this->festival_id = $festival_id;		
		}
	
		function check_festival()
		{
			$this -> db -> select('*');
			$this -> db -> from('Festivals');
			$this -> db -> where('id_festival', $this->festival_id);
			$this -> db -> limit(1);
			
			$query = $this -> db -> get();
			
			if($query -> num_rows() == 1)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	
		function login($usuari, $clau)
		{
			$this -> db -> select('id_usuari, id_festival, email, nom, cognoms, clau');
			$this -> db -> from('Usuaris');
			$this -> db -> where('email', $usuari);
			$this -> db -> where('clau', MD5($clau));
			$this -> db -> limit(1);
			
			$query = $this -> db -> get();
			
			if($query -> num_rows() == 1)
			{
				return $query->result();
			}
			else
			{
				return false;
			}
		}
		
		function get_info()
		{
			$this -> db -> select('*');
			$this -> db -> from('Festivals');
			$this -> db -> where('id_festival', $this->festival_id);
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
		
		/************** USERS **************/
		
		function get_users()
		{
			$this -> db -> select('*');
			$this -> db -> from('Usuaris');
			$this -> db -> where('id_festival', $this->festival_id);
			$query = $this -> db -> get();
			if($query -> num_rows() > 0)
			{
				return $query->result_array();
			}
			else
			{
				return false;
			}
		}
		
		function get_user($id)
		{
			$this -> db -> select('*');
			$this -> db -> from('Usuaris');
			$this -> db -> where('id_festival', $this->festival_id);
			$this -> db -> where('id_usuari', $id);
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

		/************** VENUES **************/
		
		function get_venues()
		{
			$this -> db -> select('*');
			$this -> db -> from('Locals');
			$this -> db -> where('id_festival', $this->festival_id);
			$query = $this -> db -> get();
			if($query -> num_rows() > 0)
			{
				return $query->result_array();
			}
			else
			{
				return false;
			}
		}
		
		function get_venue($id)
		{
			$this -> db -> select('*');
			$this -> db -> from('Locals');
			$this -> db -> where('id_festival', $this->festival_id);
			$this -> db -> where('id_local', $id);
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
		
		function get_venue_screens($id)
		{
			$this -> db -> select('*');
			$this -> db -> from('Sales');
			$this -> db -> where('id_local', $id);
			$query = $this -> db -> get();
			if($query -> num_rows() > 0)
			{
				return $query->result_array();
			}
			else
			{
				return false;
			}
		}
		
		/************** SCREENS **************/
		
		function get_screens()
		{
			$this -> db -> select('Sales.*');
			$this -> db -> from('Sales');
			$this -> db -> join('Locals', 'Sales.id_local = Locals.id_local');
			$this -> db -> where('Locals.id_festival', $this->festival_id);
			$query = $this -> db -> get();
			if($query -> num_rows() > 0)
			{
				return $query->result_array();
			}
			else
			{
				return false;
			}
		}
		
		function get_screen($id)
		{
			$this -> db -> select('*');
			$this -> db -> from('Sales');
			$this -> db -> where('id_sala', $id);
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