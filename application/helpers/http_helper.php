<?php

function request_format()
{
	$HTTPH =& get_instance();
    if($HTTPH->input->server('HTTP_ACCEPT'))
    {
    	return strtolower($HTTPH->input->server('HTTP_ACCEPT'));
	}
	
	return 'application/json';
}

function request_method()
{
    $HTTPH =& get_instance();
    $method = strtolower($HTTPH->input->server('REQUEST_METHOD'));
    if(in_array($method, array('get', 'delete', 'post', 'put')))
    {
        return strtolower($method);
    }

    return 'get';
}

function request_post()
{
	$HTTPH =& get_instance();
    $data = $HTTPH->input->post();
	return $data;    
}

function request_put()
{
	parse_str(file_get_contents("php://input"),$post_vars);
	return $post_vars;    
}



		
?>