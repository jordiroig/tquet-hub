<?php

	Class Festival extends CI_Model
	{
		private $festival_id;
		private $username;
	
		function __construct()
		{
			parent::__construct();
		}
	
	    function initialize($festival_id, $username)
		{
			$this->festival_id = $festival_id;
			$this->username = $username;		
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

		function check_festival_user()
		{
			$this -> db -> select('*');
			$this -> db -> from('Usuaris');
			$this -> db -> where('email', $this->username);
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
			$this -> db -> select('id_usuari, nom, cognoms, email');
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
			$this -> db -> select('id_usuari, id_festival, nom, cognoms, email');
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
			$this -> db -> select('l.id_local, l.id_festival, l.nom, l.adreca, p.nom as poblacio');
			$this -> db -> from('Locals l');
			$this -> db -> where('id_festival', $this->festival_id);
			$this -> db -> join('Poblacions p', 'l.id_poblacio = p.id_poblacio');
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
		
		/************** FILMS **************/
	
		function get_films()
		{
			$this -> db -> select('*');
			$this -> db -> from('Espectacles');
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
		
		function get_film($id)
		{
			$this -> db -> select('*');
			$this -> db -> from('Espectacles');
			$this -> db -> where('id_espectacle', $id);
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