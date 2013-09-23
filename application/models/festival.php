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
			$this -> db -> select('id_usuari, id_festival, email, nom, cognoms, clau, admin');
			$this -> db -> from('Usuaris');
			$this -> db -> where('email', $usuari);
			$this -> db -> where('clau', $clau);
			$this -> db -> limit(1);
			
			$query = $this -> db -> get();
			
			if($query -> num_rows() == 1)
			{
				return $query->result_array();
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
		
		function get_users($search = null)
		{
			$this -> db -> select('id_usuari, nom, cognoms, email, admin');
			$this -> db -> from('Usuaris');
			$this -> db -> where('id_festival', $this->festival_id);
			if($search)
			{
				$this->db->like('nom', $search);
				$this->db->or_like('cognoms', $search);
				$this->db->or_like('email', $search);
			}
			$this -> db -> order_by('id_usuari');
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
			$this -> db -> select('id_usuari, id_festival, nom, cognoms, email, admin');
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
		
		function post_user($post_data)
		{
			$post_data['id_festival'] = $this->festival_id;
			return $this->db->insert('Usuaris', $post_data); 	
		}
		
		function put_user($id, $post_data)
		{
			$post_data['id_festival'] = $this->festival_id;
			$this->db->where('id_usuari', $id);
			$update = $this->db->update('Usuaris', $post_data);
			if($update)
			{
				return $id;
			}
			else
			{
				return false;
			}
		}
		
		function delete_user($id)
		{
			return $this->db->delete('Usuaris', array('id_usuari' => $id)); 
		}

		/************** VENUES **************/
		
		function get_venues($search = null)
		{
			$this -> db -> select('l.id_local, l.id_festival, l.nom, l.adreca, p.nom as poblacio');
			$this -> db -> from('Locals l');
			$this -> db -> where('id_festival', $this->festival_id);
			$this -> db -> join('Poblacions p', 'l.id_poblacio = p.id_poblacio');
			if($search)
			{
				$this->db->like('l.nom', $search);
				$this->db->or_like('l.adreca', $search);
				$this->db->or_like('p.nom', $search);
			}
			$this -> db -> order_by('l.id_local');
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
		
		function post_venue($post_data)
		{
			$post_data['id_festival'] = $this->festival_id;
			return $this->db->insert('Locals', $post_data); 	
		}
		
		function put_venue($id, $post_data)
		{
			$post_data['id_festival'] = $this->festival_id;
			$this->db->where('id_local', $id);
			$update = $this->db->update('Locals', $post_data);
			if($update)
			{
				return $id;
			}
			else
			{
				return false;
			}
		}
		
		function delete_venue($id)
		{
			return $this->db->delete('Locals', array('id_local' => $id)); 
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
		
		function get_screens($search = null)
		{
			$this -> db -> select('s.id_sala, s.id_local, s.nom, s.localitats, l.nom as local');
			$this -> db -> from('Sales s');
			$this -> db -> join('Locals l', 's.id_local = l.id_local');
			$this -> db -> where('l.id_festival', $this->festival_id);
			if($search)
			{
				$this->db->like('s.nom', $search);
				$this->db->or_like('s.localitats', $search);
				$this->db->or_like('l.nom', $search);
			}
			$this -> db -> order_by('s.id_sala');
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
		
		function post_screen($post_data)
		{
			return $this->db->insert('Sales', $post_data); 	
		}
		
		function put_screen($id, $post_data)
		{
			$this->db->where('id_sala', $id);
			$update = $this->db->update('Sales', $post_data);
			if($update)
			{
				return $id;
			}
			else
			{
				return false;
			}
		}
		
		function delete_screen($id)
		{
			return $this->db->delete('Sales', array('id_sala' => $id)); 
		}

		/************** SECTIONS **************/

		function get_sections($search = null)
		{
			$this -> db -> select('*');
			$this -> db -> from('Seccions');
			$this -> db -> where('id_festival', $this->festival_id);
			if($search)
			{
				$this->db->like('nom', $search);
				$this->db->or_like('jurat', $search);
				$this->db->or_like('premi', $search);
			}
			$this -> db -> order_by('id_seccio');
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
		
		function get_section_sessions($id)
		{
			$this -> db -> select('s.id_sessio, l.nom as local, sl.nom as sala, e.nom as peli, e.director, e.id_seccio, s.nom, s.data, s.hora');
			$this -> db -> from('Sessions s');
			$this -> db -> join('Sales sl', 'sl.id_sala = s.id_sala');
			$this -> db -> join('Locals l', 'l.id_local = sl.id_local');
			$this -> db -> join('Espectacles e', 'e.id_espectacle = s.id_espectacle');
			$this -> db -> join('Seccions se', 'se.id_seccio = e.id_seccio');
			$this -> db -> where('se.id_seccio', $id);
			$this -> db -> order_by('s.id_sessio');
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
		
		function get_section($id)
		{
			$this -> db -> select('*');
			$this -> db -> from('Seccions');
			$this -> db -> where('id_seccio', $id);
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
		
		function post_section($post_data)
		{
			return $this->db->insert('Seccions', $post_data); 	
		}
		
		function put_section($id, $post_data)
		{
			$this->db->where('id_seccio', $id);
			$update = $this->db->update('Seccions', $post_data);
			if($update)
			{
				return $id;
			}
			else
			{
				return false;
			}
		}
		
		function delete_section($id)
		{
			return $this->db->delete('Seccions', array('id_seccio' => $id)); 
		}


		/************** FILMS **************/
	
		function get_films($search = null)
		{
			$this -> db -> select('e.*, s.nom as seccio');
			$this -> db -> from('Espectacles e');
			$this -> db -> join('Seccions s', 's.id_seccio = e.id_seccio');
			$this -> db -> where('e.id_festival', $this->festival_id);
			
			if($search)
			{
				$this->db->like('e.nom', $search);
				$this->db->or_like('e.director', $search);
				$this->db->or_like('e.actors', $search);
				$this->db->or_like('e.minuts', $search);
				$this->db->or_like('e.any', $search);
				$this->db->or_like('e.genere', $search);
				$this->db->or_like('e.sinopsi', $search);
				$this->db->or_like('e.nacionalitat', $search);
				$this->db->or_like('s.nom', $search);
			}
			$this -> db -> order_by('e.id_espectacle');
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
			$this -> db -> select('e.*, s.nom as seccio');
			$this -> db -> from('Espectacles e');
			$this -> db -> where('e.id_espectacle', $id);
			$this -> db -> join('Seccions s', 's.id_seccio = e.id_seccio');
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
		
		function post_film($post_data)
		{
			return $this->db->insert('Espectacles', $post_data); 	
		}
		
		function put_film($id, $post_data)
		{
			$this->db->where('id_espectacle', $id);
			$update = $this->db->update('Espectacles', $post_data);
			if($update)
			{
				return $id;
			}
			else
			{
				return false;
			}
		}
		
		function delete_film($id)
		{
			return $this->db->delete('Espectacles', array('id_espectacle' => $id)); 
		}
		
		/************** SESSIONS **************/
	
		function get_sessions($search = null)
		{
			$this -> db -> select('s.id_sessio, l.nom as local, sl.nom as sala, e.nom as peli, e.director, e.id_seccio, s.nom, s.data, s.hora');
			$this -> db -> from('Sessions s');
			$this -> db -> join('Sales sl', 'sl.id_sala = s.id_sala');
			$this -> db -> join('Locals l', 'l.id_local = sl.id_local');
			$this -> db -> join('Espectacles e', 'e.id_espectacle = s.id_espectacle');
			$this -> db -> where('l.id_festival', $this->festival_id);
			if($search)
			{
				$this->db->like('l.nom', $search);
				$this->db->or_like('sl.nom', $search);
				$this->db->or_like('e.nom', $search);
				$this->db->or_like('s.nom', $search);
				$this->db->or_like('s.data', $search);
				$this->db->or_like('s.hora', $search);
			}
			$this -> db -> order_by('id_sessio');
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
		
		function get_session($id)
		{
			$this -> db -> select('*');
			$this -> db -> from('Sessions');
			$this -> db -> where('id_sessio', $id);
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
		
		function post_session($post_data)
		{
			return $this->db->insert('Sessions', $post_data); 	
		}
		
		function put_session($id, $post_data)
		{
			$this->db->where('id_sessio', $id);
			$update = $this->db->update('Sessions', $post_data);
			if($update)
			{
				return $id;
			}
			else
			{
				return false;
			}
		}
		
		function delete_session($id)
		{
			return $this->db->delete('Sessions', array('id_sessio' => $id)); 
		}


	
	}

?>