<?php

	Class City extends CI_Model
	{		
		function __construct()
		{
			parent::__construct();
		}
		
		function get_citys()
		{
			$this -> db -> select('*');
			$this -> db -> from('Poblacions');
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
		
		function get_city($id)
		{
			$this -> db -> select('*');
			$this -> db -> from('Poblacions');
			$this -> db -> where('id_poblacio', $id);
			$this -> db -> limit(1);
			$query = $this -> db ->get();
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