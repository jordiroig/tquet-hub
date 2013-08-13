<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(APPPATH."controllers/rest.php");

class Festivals extends Rest 
{

    function __construct()
    {
        parent::__construct();
        $this->default_format = 'application/json';
        $this->supported_formats = array(
            'json' => 'application/json',
            'html' => 'text/html'
        );
        $this->load->model('festival', 'festival', true);
    }
    
    protected function get() 
    {

        // Obtenim el paràmetre id
        $id = $this->uri->rsegment(3);

        // Inicialitzem el model
        $this->festival->initialize($id);

        // Comprovem que el id és vàlid
        $this->check_festival_id($id);

		//Obtenim el recurs sol·licitat
		$resource = $this->uri->rsegment(4);
		$resource_id = $this->uri->rsegment(5);

        foreach ($this->supported_formats as $format => $type) {
            if ($this->format == $type) {
				if($resource)
				{
						if($resource_id)
						{
							$call = substr("get_".$resource, 0, -1);
							$view_data['data'] = $this->festival->{$call}($resource_id);
						}
						else
						{
							$call = "get_".$resource;
							$view_data['data'] = $this->festival->{$call}();
						}
				}
				else
				{
					$view_data['data'] = $this->festival->get_info();
				}
				
				if($view_data['data'])
				{
					$output = $this->load->view('festivals/' . $format, $view_data, true);
	                echo $output;
	                exit;
				}
				else
				{
					$error_code = "404";
			        $error_message = $error_code . " No hi ha dades";
			        show_error($error_message, $error_code, 'S\'ha produït un error');
				}
            }
        }

        // If the requested format is not supported, then return a HTTP 404 code.
        $error_code = "404";
        $error_message = $error_code . " Format no soportat: " . $this->format;
        show_error($error_message, $error_code, 'S\'ha produït un error');
    }
    
    private function check_festival_id($id) {
        if (empty($id) || !$this->festival->check_festival()) {
            $error_code = "400";
            $error_message = $error_code . " Festival no vàlid";
            show_error($error_message, $error_code, 'S\'ha produït un error');
            exit;
        }
    }
    
}

?>