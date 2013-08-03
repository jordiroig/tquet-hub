<?php
/* Begin file auth_helper.php */
if(!function_exists('isAuthorized'))
{
    /**
     * isAuthorized Function
     *
     * isAuthorized($auth_string, $date='', $method='', $query_string='')
     *
     * $auth_string  : The authorization string from the request
     * $date         : The date from the request
     * $method       : The method from the request
     * $query_string : The query string from the request
     *
     * Returns whether or not the request is authorized to access the service.
     *
     * @access  public
     * @return  bool
     */
    function isAuthorized($auth_string, $date='', $method='', $query_string='')
    {
        $CI =& get_instance();
        $default_db = $CI->load->database('default', TRUE);

        list($username, $request_signature) = explode(":", $auth_string, 2);
        $username = trim($username);
        $request_signature = trim($request_signature);

        if (empty($date)) {
        	
        	/*
            $request_signature = "{SHA1}" . $request_signature;

            $sql = "select top 1 id from api_keys where username = ";
            $sql .= $default_db->escape($username);
            $sql .= " and password = " . $default_db->escape($request_signature);
            $sql .= " and startdate <= " . $default_db->escape(date('Y-m-d'));
            $sql .= " and deletedate is null";
            $sql .= " order by startdate desc";

            $query = $default_db->query($sql);

            if ($query->num_rows() == 1) {
                return TRUE;
            }
            */
        	
        	//FIXME: Short-circuited the user test for testing.
        	return TRUE;
        	
        } else {
        	
        	/*
            $sql = "select top 1 password from api_keys where username = ";
            $sql .= $default_db->escape($username);
            $sql .= " and startdate <= " . $default_db->escape(date('Y-m-d'));
            $sql .= " and deletedate is null";
            $sql .= " order by startdate desc";

            $query = $default_db->query($sql);
            $row = $query->row();

            $password = preg_replace("/^\{SHA1\}/", "", $row->password);
			*/
        	
        	//FIXME: Hard-coded the password for testing.
        	$password = "deadbeef";
        	
            $method = strtoupper($method);

            parse_str($query_string,$params);
            // sort the parameters
            ksort($params);

            // create the canonicalized query
            $canonicalized_query = array();
            foreach ($params as $param=>$value)
            {
                $param = str_replace("%7E", "~", rawurlencode($param));
                $value = str_replace("%7E", "~", rawurlencode($value));
                $canonicalized_query[] = $param."=".$value;
            }
            $canonicalized_query = implode("&", $canonicalized_query);

            // create the string to sign
            $string_to_sign = $method."\n".$canonicalized_query."\n".$date;

            // calculate HMAC with SHA256 and base64-encoding
            $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $password, True));

            // encode the signature for the request
            $signature = str_replace("%7E", "~", rawurlencode($signature));

            if ($request_signature == $signature) {
                return TRUE;
            }

        }
        return FALSE;
    }
}

if(!function_exists('getClientAuthSignature'))
{
    /**
     * getClientAuthSignature Function
     *
     * getClientAuthSignature($method, $url, $date, $user, $secret)
     *
     * $method : The HTTP request method, e.g. GET, POST, etc.
     * $url    : The request URL
     * $date   : The GMT date timestamp, e.g. 24 Mar 2011 19:46:50Z
     * $user   : The web service user ID, e.g. bob
     * $secret : The shared secret for the user
     *
     * Returns the authorization signature used by the web service.
     *
     * @access  public
     * @return  string
     */
    function getClientAuthSignature($method, $url, $date, $user, $secret) {

        $request_url = parse_url($url);
        $query_string = $request_url['query'];

        parse_str($query_string,$params);
        // sort the parameters
        ksort($params);

        // create the canonicalized query
        $canonicalized_query = array();
        foreach ($params as $param=>$value)
        {
            $param = str_replace("%7E", "~", rawurlencode($param));
            $value = str_replace("%7E", "~", rawurlencode($value));
            $canonicalized_query[] = $param."=".$value;
        }
        $canonicalized_query = implode("&", $canonicalized_query);

        // create the string to sign
        $string_to_sign = $method."\n".$canonicalized_query."\n".$date;

        // calculate HMAC with SHA256 and base64-encoding
        $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $secret, True));

        // encode the signature for the request
        $signature = str_replace("%7E", "~", rawurlencode($signature));

        return $signature;
    }
}
/* End of file auth_helper.php */
/* Location: ./application/helpers/auth_helper.php */