<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

abstract class Rest extends CI_Controller 
{
	// Métode HTTP GET, POST, ...
	protected $method;
	
	// Format sol·licitat (JSON, ...)
    protected $format;
    
    protected $parameters;
    protected $valid_date_range_in_seconds;
	
	// Format per defecte
    protected $default_format;

	// Formats suportats
    protected $supported_formats = array();
	
	function __construct()
    {
        parent::__construct();
        $this->load->helper('auth_helper');
        $this->load->helper('http_helper');

		// 15 minuts de validesa per a les peticions 
        $this->valid_date_range_in_seconds = 900;
    }
	
	public function index()
    {            
		// Obtenim el métode HTTP
        $this->method = request_method(); //GET

        // Comprovem les credencials
        //$this->checkAuth($this->method);

        // Detectem el format sol·licitat
        $request_format = request_format(); //JSON
        
        $this->format = detect_format($request_format, 
                                      $this->supported_formats,
                                      $this->default_format);    

        $this->parameters = $this->input->get();

        switch($this->method) {
            case 'get':
                $this->get();
                break;
            default;
                $error_code = "404";
                $error_message = $error_code;
                $error_message .= " Unsupported method: ";
                $error_message .= $this->method;
                show_error($error_message, $error_code);    
                break;
        }                
    }
    
    abstract protected function get();
           
    private function checkAuth($method) {

        $auth = "";
        if($this->input->server('HTTP_X_AUTHORIZATION'))
        {
            $auth = $this->input->server('HTTP_X_AUTHORIZATION');
        }

        $request_date = "";
        if($this->input->server('HTTP_DATE'))
        {
            $request_date = $this->input->server('HTTP_DATE');
        }

        $query_string = "";
        if($this->input->server('QUERY_STRING'))
        {
            $query_string = $this->input->server('QUERY_STRING');
        }

        if (empty($request_date)
            || !$this->checkDate($request_date)) {
            $error_code = "403";
            $error_message = $error_code . " La data no és correcta";
            show_error($error_message, $error_code, 'S\'ha produït un error');
            exit;
        }

        if (empty($auth)
            || !isAuthorized($auth, $request_date,
                             $method, $query_string)) {
            $error_code = "401";
            $error_message = $error_code . " No autoritzat";
            show_error($error_message, $error_code, 'S\'ha produït un error');
            exit;
        }
    }

    private function checkDate($request_date)
    {
        if (!preg_match("/GMT/i", $request_date)
            && !preg_match("/UTC/i", $request_date)
            && !preg_match("/Z/i", $request_date))
        {
            $request_date .= " UTC";
        }
        $ts_req = strtotime($request_date);
        $ts_server = (int)gmdate('U');

        $valid_date_range = $this->valid_date_range_in_seconds;
        if ( ($ts_req > $ts_server - $valid_date_range)
             && ($ts_req < $ts_server + $valid_date_range) )
        {
            return true;
        }
        return false;
    }

}

?>