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
        //$this->load->model('widget_v1_model', '', true);
    }
    
    protected function get() 
    {

        // Obtenim el paràmetre id
        $id = $this->parameters['id'];

		die("obtenim un ".$id);
        // Inicialitzem el model ¡
        $this->festival->initialize($id);

        // Check to make sure the ID is valid.
        //$this->checkWidgetId($id);

        foreach ($this->supported_formats as $format => $type) {

            if ($this->format == $type) {

                $widget = $this->widget_v1_model->get();
                $view_data = Array('dto' => $widget);
                $output = $this->load->view('widget_v1/' . $format, $view_data, true);
                echo $output;
                exit;
            }
        }

        // If the requested format is not supported, then return a HTTP 404 code.
        $error_code = "404";
        $error_message = $error_code . " Unsupported format(s): " . $this->format;
        show_error($error_message, $error_code);
    }


}

?>