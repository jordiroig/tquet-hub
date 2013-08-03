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
        if($CI->input->server('HTTP_ACCEPT'))
        {
        	return strtolower($CI->input->server('HTTP_ACCEPT'));
		}
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

if(!function_exists('detect_format'))
{
    /**
     * detect_format Function
     *
     * Attempts to detect the requested format within the list of
     * supported formats.
     *
     * @access  public
     * @return  string
     */
    function detect_format($request_format, $supported_formats, $default_format='')
    {
        // check the $request_format (if it exists and we are allowed)
        if ($request_format)
        {
            // Check all formats against the $reques_format
            foreach ($supported_formats as $format => $type)
            {
                // Has this format been requested?
                if (strpos($request_format, $type) !== FALSE)
                {
                    // If not HTML or XML assume its right and send it on its way
                    if ($format != 'html' AND $format != 'xml')
                    {
                        return $type;
                    }

                    // HTML or XML have shown up as a match
                    else
                    {
                        // If it is truely HTML, it wont want any XML
                        if ($format == 'html' AND strpos($request_format, 'xml') === FALSE)
                        {
                            return $type;
                        }

                        // If it is truely XML, it wont want any HTML
                        elseif ($format == 'xml' AND strpos($request_format, 'html') === FALSE)
                        {
                            return $type;
                        }
                    }
                }
            }
        } // End HTTP_ACCEPT checking
        // Well, none of that has worked! Let's see if the controller has a default
        if (!empty($default_format))
        {
            return $default_format;
        }
        // Just use the request format
        return $request_format;
    }
}

/* End of file http_helper.php */
/* Location: ./application/helpers/http_helper.php */