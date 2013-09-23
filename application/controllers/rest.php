<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

abstract class Rest extends CI_Controller 
{
	// Métode HTTP GET, POST, ...
	protected $method;
	
	// Format sol·licitat (JSON, ...)
    protected $format;
    
    protected $parameters;
	
	//Usuari que fa les peticions
	protected $username;
	
	//Usuari és admin?
	protected $isadmin;
	
	// Format per defecte
    protected $default_format = 'application/json';

	// Formats suportats
    protected $supported_formats  = array(
    	'json' => 'application/json',
        'html' => 'text/html'
    );
	
	function __construct()
    {
        parent::__construct();
		$this->load->helper('http_helper');
        $this->load->model('festival', 'festival', true);
    }
    
	public function index()
    {
		// Obtenim el métode HTTP
        $this->method = request_method(); //GET, POST, PUT, DELETE

        // Detectem el format sol·licitat
        $request_format = request_format(); //JSON, HTML
        
        if(in_array($request_format, $this->supported_formats))
        {
	        $this->format = $request_format;
        }
        else
        {
	        if(in_array($this->default_format, $this->supported_formats))
	        {
		        $this->format = $this->default_format;
	        }
	        else
	        {
		        $this->response(array('status' => false, 'error' => 'Format '.$request_format.' no suportat'), 405);
	        }
        }
        
        // Comprovem les credencials
        $this->checkAuth();
		
        switch($this->method) {
            case 'get':
                $this->get();
                break;
            case 'put':
            	$put_data = request_put();
            	if($this->isadmin == '1')
            	{
            		$this->put($put_data);
            	}
            	else
            	{
	            	$this->response(array('status' => false, 'error' => 'No autoritzat'), 401);
            	}
            	break;
            case 'post':
	            $post_data = request_post();
	            if($this->isadmin == '1')
            	{
	            	$this->post($post_data);
	            }
            	else
            	{
	            	$this->response(array('status' => false, 'error' => 'No autoritzat'), 401);
            	}
            	break;
            case 'delete':
	            if($this->isadmin == '1')
            	{
    	          	$this->delete();
    	        }
            	else
            	{
	            	$this->response(array('status' => false, 'error' => 'No autoritzat'), 401);
            	}
              	break;
            default:
	            $this->response(array('status' => false, 'error' => 'Metode '.$this->method.' no suportat'), 405);
                break;
        }                
    }

    private function checkAuth() 
    {
		if($this->input->server('PHP_AUTH_USER'))
		{
			$this->username = $this->input->server('PHP_AUTH_USER');
			$password = $this->input->server('PHP_AUTH_PW');
		}
		
		if($this->username && $password)
		{
			$login = $this->festival->login($this->username, $password);
		}
		else
		{
			$login = false;
		}

        if (!$login) 
        {
            $this->response(array('status' => false, 'error' => 'No autoritzat'), 401);
            exit;
        }
        else
        {
	        $this->isadmin = $login[0]['admin'];
        }
        
    }
    
    public function response($data = array(), $http_code = null)
	{
		//No hi ha dades i el codi és null
		if (empty($data) && $http_code === null)
		{
			$http_code = 404;
			$output = array('status' => false, 'error' => 'No hi ha dades');
		}
		//No hi ha dades, però tenim codi
		else if (empty($data) && is_numeric($http_code))
		{
			$output = null;
		}
		else
		{
			$function = array_search($this->format,$this->supported_formats);
			if (method_exists($this, 'to_'.$function))
			{	
				header('Content-Type: '.$this->format);
				$output = $this->{'to_'.$function}($data);
			}
			else //No hi ha funció per aquell format, escupim les dades
			{
				$output = var_dump($data);
			}
		}
		
		header('HTTP/1.1: ' . $http_code);
		header('Status: ' . $http_code);
		
		exit($output);
	}
	
	private function to_json($data)
	{
		return json_encode($data);
	}
	
	private function to_html($data)
	{
		$formated_data = 'json: <br /><br />'.json_encode($data).'<br /><br />';
		$formated_data .= 'dades: <br />';	
		$formated_data .= '<ul>';
		foreach ($data as $key => $val) 
		{
			if($val == false)
			{
				$val = 'Error';
			}
			$formated_data .= '<li>['.$key.']: '.$val.'</li>';
		}
		$formated_data .= '</ul>';
		return $formated_data;
	}
	
}

?>