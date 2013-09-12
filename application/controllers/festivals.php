<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH."controllers/rest.php");

class Festivals extends Rest 
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('festival', 'festival', true);
    }

	protected function put($post_data)
    {
	    // Obtenim el paràmetre id
        $id = $this->uri->rsegment(3);

        // Inicialitzem el model
        $this->festival->initialize($id, $this->username);
	    
	    // Comprovem que el id és vàlid
        $this->check_festival_id($id);

        //Obtenim el recurs sol·licitat
		$resource = $this->uri->rsegment(4);
		$resource_id = $this->uri->rsegment(5);
		$second_resource = $this->uri->rsegment(6);

		if($resource)
		{
			if($resource_id)
			{
				if($second_resource)
				{
					$call = substr("put_".$resource, 0, -1)."_".$second_resource;
				}
				else
				{
					$call = substr("put_".$resource, 0, -1);
				}
			}
			else
			{
				$call = "put_".$resource;
			}
			
			if(method_exists($this->festival, $call))
			{
				try
				{
					$data_result = $this->festival->{$call}($resource_id, $post_data);
				}
				catch(Exception $e)
				{
					$this->response(array('status' => false, 'error' => 'S\'ha produit un error'), 400);
				}
			}
			else
			{
				$this->response(array('status' => false, 'error' => 'Aquest recurs no existeix'), 404);
			}						
		}
		else
		{
			//Edició festival
		}
		
		if($data_result)
		{
            $this->response($data_result, 200);
		}
		else
		{
			$this->response(array('status' => false, 'error' => 'No s\'ha creat el registre'), 400);
		}

	}
    
    protected function post($post_data)
    {
	    // Obtenim el paràmetre id
        $id = $this->uri->rsegment(3);

        // Inicialitzem el model
        $this->festival->initialize($id, $this->username);
	    
	    // Comprovem que el id és vàlid
        $this->check_festival_id($id);

        //Obtenim el recurs sol·licitat
		$resource = $this->uri->rsegment(4);
		$resource_id = $this->uri->rsegment(5);
		$second_resource = $this->uri->rsegment(6);

		if($resource)
		{
			if($resource_id)
			{
				if($second_resource)
				{
					$call = substr("post_".$resource, 0, -1)."_".$second_resource;
				}
				else
				{
					$call = substr("post_".$resource, 0, -1);
				}
			}
			else
			{
				$call = substr("post_".$resource, 0, -1);
			}
			
			if(method_exists($this->festival, $call))
			{
				try
				{
					$data_result = $this->festival->{$call}($post_data);
				}
				catch(Exception $e)
				{
					$this->response(array('status' => false, 'error' => 'S\'ha produit un error'), 400);
				}
			}
			else
			{
				$this->response(array('status' => false, 'error' => 'Aquest recurs no existeix'), 404);
			}						
		}
		else
		{
			//Creació festival
		}
		
		if($data_result)
		{
            $this->response($data_result, 200);
		}
		else
		{
			$this->response(array('status' => false, 'error' => 'No s\'ha creat el registre'), 400);
		}

    }
    
    protected function get() 
    {
        // Obtenim el paràmetre id
        $id = $this->uri->rsegment(3);

        // Inicialitzem el model
        $this->festival->initialize($id, $this->username);

        // Comprovem que el id és vàlid
        $this->check_festival_id($id);

		//Obtenim el recurs sol·licitat
		$resource = $this->uri->rsegment(4);
		$resource_id = $this->uri->rsegment(5);
		$second_resource = $this->uri->rsegment(6);
		
		$search = (isset($_GET['search']))?$_GET['search']:null;
		
         
		if($resource)
		{
			if($resource_id)
			{
				if($second_resource)
				{
					$call = substr("get_".$resource, 0, -1)."_".$second_resource;
				}
				else
				{
					$call = substr("get_".$resource, 0, -1);
				}
			}
			else
			{
				$call = "get_".$resource;
			}
			
			if(method_exists($this->festival, $call))
			{
				if($search)
				{
					$data_result = $this->festival->{$call}($search);
				}
				else
				{
					$data_result = $this->festival->{$call}($resource_id);
				}
			}
			else
			{
				$this->response(array('status' => false, 'error' => 'Aquest recurs no existeix'), 404);
			}						
		}
		else
		{
			$data_result = $this->festival->get_info();
		}
		
		if($data_result)
		{
            $this->response($data_result, 200);
		}
		else
		{
			$this->response(array('status' => false, 'error' => 'No hi ha dades'), 404);
		}
    }
    
    protected function delete() 
    {
        // Obtenim el paràmetre id
        $id = $this->uri->rsegment(3);

        // Inicialitzem el model
        $this->festival->initialize($id, $this->username);

        // Comprovem que el id és vàlid
        $this->check_festival_id($id);

		//Obtenim el recurs sol·licitat
		$resource = $this->uri->rsegment(4);
		$resource_id = $this->uri->rsegment(5);
		$second_resource = $this->uri->rsegment(6);
         
		if($resource)
		{
			if($resource_id)
			{
				if($second_resource)
				{
					$call = substr("delete_".$resource, 0, -1)."_".$second_resource;
				}
				else
				{
					$call = substr("delete_".$resource, 0, -1);
				}
			}
			else
			{
				$call = substr("delete_".$resource, 0, -1);
			}
			
			if(method_exists($this->festival, $call))
			{
				$data_result = $this->festival->{$call}($resource_id);	
			}
			else
			{
				$this->response(array('status' => false, 'error' => 'Aquest recurs no existeix'), 404);
			}						
		}
		else
		{
			//borrar un festival
		}
		
		if($data_result)
		{
            $this->response($data_result, 200);
		}
		else
		{
			$this->response(array('status' => false, 'error' => 'No hi ha dades'), 404);
		}
    }
        
    private function check_festival_id($id)
    {
        if (empty($id) || !$this->festival->check_festival()) {
	        $this->response(array('status' => false, 'error' => 'Festival no valid'), 404);
            exit;
        }
        else
        {
        	if(!$this->festival->check_festival_user())
        	{
				$this->response(array('status' => false, 'error' => 'No autoritzat'), 403);
				exit;
        	}
        }
    }
    
}

?>