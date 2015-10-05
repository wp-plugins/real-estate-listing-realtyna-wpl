<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.addon_mobile_application');
_wpl_import('libraries.users');
_wpl_import('libraries.db');

/**
 * WPL IO Library
 * @author Howard R <howard@realtyna.com>
 * @since WPL2.7.0
 * @package WPL
 */
class wpl_io_global
{
    /**
     * IO Commands Directory
     * @var string
     */
	public $commands_folder = 'commands';
    
    /**
     * IO Formats Directory
     * @var string
     */
	public $formats_folder = 'formats';





    /**
     * @param string $cmd
     * @param string $username
     * @param string $password
     * @param array $vars
     * @param string $dformat
     * @return wpl_io_cmd_base
     */
	public function response($cmd, $username, $password, $vars, $dformat = 'json')
	{
		$command_file = $this->get_command_path($cmd, $vars);
		require_once $command_file;
        
		$cmd_class = 'wpl_io_cmd_'.$cmd;

        /** @var $cmd_object wpl_io_cmd_base */
		$cmd_object = new $cmd_class();
        $cmd_object->init($username, $password, $vars);

		if($cmd_object->get_error() != '')
        {
            return array('error'=>$cmd_object->get_error());
        }
        
        $validation = $cmd_object->validate();

        if($cmd_object->get_error() != '')
        {
            return array('error'=>$cmd_object->get_error());
        }
        elseif($validation == false)
        {
            return array('error'=>"Validation failed");
        }
        
        $response = $cmd_object->build();

		
		if($cmd_object->get_error() != '')
        {
            return array('error'=>$cmd_object->get_error());
        }
        
		return $response;
	}

    /**
     * @param string $cmd
     * @param array $vars
     * @param wpl_io_cmd_base $response
     * @param string $dformat
     * @return mixed
     */
	public function render_format($cmd, $vars, $response, $dformat = 'json')
	{
		$format_file = $this->get_format_path($dformat);
		require_once $format_file;
        
		$format_class = 'wpl_io_format_'.$dformat;

        /** @var $format_object wpl_io_format_base */
		$format_object = new $format_class($cmd, $vars);
		if($format_object->get_error() != '') return $format_object->get_error();
        
		$rendered = $format_object->render($response);
		if($format_object->get_error() != '') return $format_object->get_error();
		
		return $rendered;
	}

    /**
     * @param $vars
     * @return available commands
     * @description use this function for getting available commands
     */
	public function get_commands($vars)
	{
		$files = wpl_folder::files($this->get_commands_path($vars), '.php$', false, false);

		$commands = array();
		foreach($files as $file)
		{
			$ex = explode('.', $file);
			$commands[] = $ex[0];
		}
		
		return $commands;
	}


    /**
     * @param $vars
     * @return string
     */
	public function get_commands_path($vars)
	{
        if(isset($vars['commands_directory']))
        {
            $folders = wpl_folder::folders(WPL_ABSPATH . 'libraries' . DS . 'io' . DS);
            if(in_array($vars['commands_directory'], $folders))
            {
                $this->commands_folder = $vars['commands_directory'];
            }
        }
		return WPL_ABSPATH . 'libraries' . DS . 'io' . DS . $this->commands_folder . DS;
	}

    /**
     * @param $cmd
     * @param $vars
     * @return string commands path
     */
	public function get_command_path($cmd, $vars)
	{
        if(isset($vars['commands_directory']))
        {
            $folders = wpl_folder::folders(WPL_ABSPATH . 'libraries' . DS . 'io' . DS);
            if(in_array($vars['commands_directory'], $folders))
            {
                $this->commands_folder = $vars['commands_directory'];
            }
        }
        $path = WPL_ABSPATH . 'libraries' . DS . 'io' . DS . $this->commands_folder . DS . 'overrides' . DS . $cmd . '.php';
		if(!wpl_file::exists($path))
        {
            $path = WPL_ABSPATH . 'libraries' . DS . 'io' . DS . $this->commands_folder . DS . $cmd . '.php';
        }
		
		return $path;
	}

    /**
     * use this function for getting available formats
     * @return array
     */
	public function get_formats()
	{
		$files = wpl_folder::files($this->get_formats_path(), '.php$', false, false);
		
		$commands = array();
		foreach($files as $file)
		{
			$ex = explode('.', $file);
			$commands[] = $ex[0];
		}
		
		return $commands;
	}
	
	/**
		@input void
		@return formats path
	**/
	public function get_formats_path()
	{
		return WPL_ABSPATH.'libraries'.DS.'io'.DS. $this->formats_folder .DS;
	}


    /**
     * @param $format
     * @return string
     */
	public function get_format_path($format)
	{
		$path = WPL_ABSPATH.'libraries'.DS.'io'.DS. $this->formats_folder .DS. 'overrides' .DS. $format .'.php';
		if(!wpl_file::exists($path)) $path = WPL_ABSPATH.'libraries'.DS.'io'.DS. $this->formats_folder .DS. $format .'.php';
		
		return $path;
	}


    /**
     * @param $command_name
     * @param $dapikey
     * @param $dapisecret
     * @param string $extra_params
     * @return string
     */
    public static function generate_command_url($command_name, $dapikey, $dapisecret, $extra_params = "")
    {
        $params = "";
        if ($extra_params != "")
        {
            foreach($extra_params as $key=>$value)
            {
                $params .= "&" . $key . "=" . $value;
            }
        }
        return get_site_url() . DS . "?wplview=io&wplformat=io&dapikey=" . $dapikey . "&dapisecret=" . $dapisecret . "&cmd=" . $command_name . $params;
    }

}



/**
 * The base class of all io formats
 * @author Chris <chris@realtyna.com>
 * @since WPL2.7.0
 * @package WPL
 * @date 2015/06/02
 */
abstract class wpl_io_format_base extends wpl_io_global
{
    protected $error;
    protected $params;
    protected $cmd;

    /**
     * @return string
     */
    public function get_error()
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function set_error($error)
    {
        $this->error = $error;
    }

    /**
     * @param wpl_io_cmd_base $response
     * @return mixed
     */
    public abstract function render($response);


    public function init($cmd, $params)
    {
        $this->params = $params;
        $this->cmd = $cmd;

    }
}


/**
 * The base class of all io commands
 * @author Chris <chris@realtyna.com>
 * @since WPL2.7.0
 * @package WPL
 * @date 2015/06/02
 */
abstract class wpl_io_cmd_base extends wpl_io_global
{
    protected $error;
    protected $params;
    protected $username;
    protected $password;
    protected $user_id;
    protected $initialization = true;
    protected $authentication = true;

    /**
     * @param boolean $initialization
     */
    public function set_initialization($initialization)
    {
        $this->initialization = $initialization;
    }

    /**
     * @param boolean $authentication
     */
    public function set_authentication($authentication)
    {
        $this->authentication = $authentication;
    }


    /**
     * This method is the main method of each commands
     * @return mixed
     */
    public abstract function build();


    /**
     * Data validation
     * @return boolean
     */
    public abstract function validate();



    /**
     * Getting the commands error
     * @author Chris <chris@realtyna.com>
     * @return string return the command errors
     */
    public function get_error()
    {
        return $this->error;
    }

    /**
     * Setting the commands error before finish
     * @param string $error
     */
    public function set_error($error)
    {
        $this->error = $error;
    }

    /**
     * Initialization the commands before build
     * @param string $username
     * @param string $password
     * @param array $params
     * @return boolean
     */
    public function init($username, $password, $params)
    {
        if($this->initialization == false)
        {
            return;
        }
        $this->username = base64_decode($username);
        $this->password = base64_decode($password);
        $this->params = $params;

        if($this->authentication)
        {
            if($username != '')
            {
                $authenticate = wpl_users::authenticate($username, $password);
                if($authenticate['status'] != 1)
                {
                    $this->error = "Authentication failed!";
                    return false;
                }

                $this->user_id = $authenticate['uid'];
            }
            else
            {
                $this->user_id = 0;
            }
        }

    }





}