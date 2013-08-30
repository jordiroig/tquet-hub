<?php

if(!function_exists('request_format'))
{
    /**
     * request_format Function
     *
     * Returns the HTTP requested format, e.g. 'application/xml', 'application/json', etc.
     *
     * @access  public
     * @return  string
     */    
	function request_format()
    {
    	$CI =& get_instance();
   
        // If a HTTP_ACCEPT header is present...
        /*if($CI->input->server('HTTP_ACCEPT'))
        {
        	return strtolower($CI->input->server('HTTP_ACCEPT'));
		}*/
		return 'text/html';
    }
}

if(!function_exists('request_method'))
{
    /**
     * request_method Function
     *
     * Returns the HTTP request method, e.g. get, post, etc.
     *
     * @access  public
     * @return  string
     */
    function request_method()
    {
        $CI =& get_instance();
        $method = strtolower($CI->input->server('REQUEST_METHOD'));
        if(in_array($method, array('get', 'delete', 'post', 'put')))
        {
            return strtolower($method);
        }

        return 'get';
    }
}

?>