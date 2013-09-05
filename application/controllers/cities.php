<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH."controllers/rest.php");

class Cities	 extends Rest 
{
	function __construct()
    {
        parent::__construct();
        $this->load->model('city', 'city', true);
    }
    
    protected function get() 
    {
        // Obtenim el paràmetre id (o login)
        $id = $this->uri->rsegment(3);
        
        if($id)
        {
		     $data_result = $this->city->get_city($id);
        }
        else
        {
	        $data_result = $this->city->get_citys();
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