<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH."controllers/rest.php");

class Users extends Rest 
{
	function __construct()
    {
        parent::__construct();
        $this->load->model('user', 'user', true);
    }
    
    protected function get() 
    {
    	// Inicialitzem el model
        $this->user->initialize($this->username);
        
        // Obtenim el paràmetre id (o login)
        $id = $this->uri->rsegment(3);
        
        if($id)
        {
	     	if($id == 'login') //L'usuari vol fer login
	     	{
		     	$data_result = $this->user->get_info();
	     	}
	     	else //Informació sobre un usuari, en principi només pot sobre ell mateix
	     	{
		     	$data_result = $this->user->get_user($id);
	     	}
        }
        else
        {
	        $this->response(array('status' => false, 'error' => 'Usuari no valid'), 404);
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
}

?>