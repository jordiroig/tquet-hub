<?php

function request_format()
{
	$HTTPH =& get_instance();
    if($HTTPH->input->server('HTTP_ACCEPT'))
    {
    	return strtolower($HTTPH->input->server('HTTP_ACCEPT'));
	}
	
	return 'text/html';
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

?>