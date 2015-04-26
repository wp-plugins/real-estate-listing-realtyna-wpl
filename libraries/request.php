<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Request Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 03/10/2013
 * @package WPL
 */
class wpl_request
{
    /**
     * Returns request method
     * @author Howard <howard@realtyna.com>
     * @static
     * @return string
     */
	public static function get_method()
	{
		return strtoupper($_SERVER['REQUEST_METHOD']);
	}
	
    /**
     * get a variable
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $name
     * @param mixed $default
     * @param string $hash
     * @param boolean $clean
     * @return mixed
     */
	public static function getVar($name, $default = null, $hash = 'default', $clean = false)
	{
		// Ensure hash and type are uppercase
		$hash = strtoupper($hash);
		
		if ($hash === 'METHOD')
		{
			$hash = strtoupper($_SERVER['REQUEST_METHOD']);
		}

		// Get the input hash
		switch ($hash)
		{
			case 'GET':
				$input = &$_GET;
				break;
			case 'POST':
				$input = &$_POST;
				break;
			case 'FILES':
				$input = &$_FILES;
				break;
			case 'COOKIE':
				$input = &$_COOKIE;
				break;
			case 'ENV':
				$input = &$_ENV;
				break;
			case 'SERVER':
				$input = &$_SERVER;
				break;
			default:
				$input = &$_REQUEST;
				$hash = 'REQUEST';
				break;
		}

		$var = isset($input[$name]) ? $input[$name] : $default;
		
		/** clean **/
		if($clean) $var = wpl_global::clean($var);
		
		return $var;
	}

    /**
     * Gets a variable array
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $hash
     * @return array
     */
	public static function get($hash = 'default')
	{
		// Ensure hash and type are uppercase
		$hash = strtoupper($hash);

		if ($hash === 'METHOD')
		{
			$hash = strtoupper($_SERVER['REQUEST_METHOD']);
		}

		switch ($hash)
		{
			case 'GET':
				$input = $_GET;
				break;

			case 'POST':
				$input = $_POST;
				break;

			case 'FILES':
				$input = $_FILES;
				break;

			case 'COOKIE':
				$input = $_COOKIE;
				break;

			case 'ENV':
				$input = &$_ENV;
				break;

			case 'SERVER':
				$input = &$_SERVER;
				break;

			default:
				$input = $_REQUEST;
				break;
		}

		return $input;
	}
	
    /**
     * Set a variable in one of the request variables. 
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $name
     * @param mixed $value
     * @param string $hash
     * @param boolean $overwrite
     * @return mixed
     */
	public static function setVar($name, $value = null, $hash = 'method', $overwrite = true)
	{
		// If overwrite is true, makes sure the variable hasn't been set yet
		if(!$overwrite && array_key_exists($name, $_REQUEST))
		{
			return $_REQUEST[$name];
		}

		/** Get the request hash value **/
		$hash = strtoupper($hash);
		if($hash === 'METHOD') $hash = strtoupper($_SERVER['REQUEST_METHOD']);

		$previous = array_key_exists($name, $_REQUEST) ? $_REQUEST[$name] : null;

		switch($hash)
		{
			case 'GET':
				$_GET[$name] = $value;
				$_REQUEST[$name] = $value;
				break;
			case 'POST':
				$_POST[$name] = $value;
				$_REQUEST[$name] = $value;
				break;
			case 'COOKIE':
				$_COOKIE[$name] = $value;
				$_REQUEST[$name] = $value;
				break;
			case 'FILES':
				$_FILES[$name] = $value;
				break;
			case 'ENV':
				$_ENV['name'] = $value;
				break;
			case 'SERVER':
				$_SERVER['name'] = $value;
				break;
		}
		
		return $previous;
	}

    /**
     * Sets array to the request
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $array
     * @param string $hash
     * @param boolean $overwrite
     */
	public static function set($array, $hash = 'default', $overwrite = true)
	{
		foreach($array as $key=>$value)
		{
			self::setVar($key, $value, $hash, $overwrite);
		}
	}
}

/**
 * Session Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.8.1
 * @date 23/09/2014
 * @package WPL
 */
class wpl_session
{
    /**
     * Set a variable to session
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $key
     * @param mixed $value
     * @param boolean $override
     * @return mixed
     */
    public static function set($key, $value = NULL, $override = true)
    {
        $apply = false;
        if(!isset($_SESSION[$key]))
        {
            $apply = true;
            $_SESSION[$key] = $value;
        }
        elseif(isset($_SESSION[$key]) and $override)
        {
            $apply = true;
            $_SESSION[$key] = $value;
        }
        
        return ($apply ? $value : NULL);
    }
    
    /**
     * Get a session variable
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $key
     * @return mixed
     */
    public static function get($key = NULL)
    {
        if($key) return (isset($_SESSION[$key]) ? $_SESSION[$key] : NULL);
        return $_SESSION;
    }
}

/**
 * Security Library
 * @author Howard <howard@realtyna.com>
 * @since WPL2.1.0
 * @package WPL
 */
class wpl_security
{
    /**
     * Security Salt
     * @var string
     */
    private $salt = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz0123456789';
    
    /**
     * Generates and returns a token
     * @author Howard <howard@realtyna.com>
     * @return string
     */
    public function token()
    {
        $random_key = substr(str_shuffle($this->salt), 0, 10);
        $token = md5($random_key.time());
        
        $query = "INSERT INTO `#__wpl_items` (`parent_kind`,`item_type`,`item_cat`,`item_name`,`creation_date`) VALUES ('-1','security','token','$token','".date("Y-m-d H:i:s")."')";
        wpl_db::q($query, 'INSERT');
        
        return $token;
    }
    
    /**
     * Check validity of a token
     * @param string $token
     * @param boolean $delete
     * @return boolean
     */
    public function validate_token($token, $delete = false)
    {
        $query = "SELECT COUNT(*) FROM `#__wpl_items` WHERE `item_name`='$token' AND `parent_kind`='-1'";
        $num = wpl_db::num($query);
        
        if($num and $delete)
        {
            $query = "DELETE FROM `#__wpl_items` WHERE `parent_kind`='-1' AND `item_name`='$token'";
            wpl_db::q($query, 'DELETE');
        }
        
        return $num ? true : false;
    }

    /**
     * Encrypt a string using mcrypt
     * @author Steve A. <steve@realtyna.com>
     * @static
     * @param  string  $data     Input String
     * @param  string  $key      Encryption Key
     * @param  integer $strength Encryption Strength
     * @return string            Encrypted String
     */
    public static function encrypt($data, $key = 'WPL', $strength = 128)
    {
    	if(!extension_loaded('mcrypt')) return false;

    	if($strength == 192)
    		$cipher = MCRYPT_RIJNDAEL_192;
    	elseif($strength == 256)
    		$cipher = MCRYPT_RIJNDAEL_256;
    	else
    		$cipher = MCRYPT_RIJNDAEL_128;

	    $iv_size = mcrypt_get_iv_size($cipher, MCRYPT_MODE_CBC);
	    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	    $result = $iv.mcrypt_encrypt($cipher, $key, $data, MCRYPT_MODE_CBC, $iv);
	    return base64_encode($result);
    }

    /**
     * Decrypt a string using mcrypt
     * @author Steve A. <steve@realtyna.com>
     * @static
     * @param  string  $data     Input String
     * @param  string  $key      Encryption Key
     * @param  integer $strength Encryption Strength
     * @return string            Decrypted String
     */
    public static function decrypt($data, $key = 'WPL', $strength = 128)
    {
    	if(!extension_loaded('mcrypt')) return false;

    	if($strength == 192)
    		$cipher = MCRYPT_RIJNDAEL_192;
    	elseif($strength == 256)
    		$cipher = MCRYPT_RIJNDAEL_256;
    	else
    		$cipher = MCRYPT_RIJNDAEL_128;

	    $data = base64_decode($data);
    	$iv_size = mcrypt_get_iv_size($cipher, MCRYPT_MODE_CBC);
	    $iv_dec = substr($data, 0, $iv_size);
	    $result = mcrypt_decrypt($cipher, $key, substr($data, $iv_size), MCRYPT_MODE_CBC, $iv_dec);
	    return $result;
    }
}