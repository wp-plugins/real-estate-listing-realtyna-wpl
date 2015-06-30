<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');


_wpl_import('libraries.io.formats_base');
_wpl_import('libraries.io.commands_base');
class wpl_io_global
{
	var $commands_folder = 'commands';
	var $formats_folder = 'formats';


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
		$command_file = $this->get_command_path($cmd);
		require_once $command_file;
		$cmd_class = 'wpl_io_cmd_'.$cmd;

        /** @var $cmd_object wpl_io_cmd_base */
		$cmd_object = new $cmd_class();
        $cmd_object->init($username, $password, $vars);
		if($cmd_object->getError() != '')
        {
            return array('error' => $cmd_object->getError());
        }
        $validation = $cmd_object->validate();
        if($cmd_object->getError() != '')
        {
            return array('error' => $cmd_object->getError());
        }
        elseif($validation == false)
        {
            return array('error' => "Validation failed");
        }
        $response = $cmd_object->build();
		if($cmd_object->getError() != '')
        {
            return array('error' => $cmd_object->getError());
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
		if($format_object->getError() != '') return $format_object->getError();
		$rendered = $format_object->render($response);
		if ($format_object->getError() != '') return $format_object->getError();
		
		return $rendered;
	}
	
	/**
		@input void
		@return available commands
		@description use this function for getting available commands
	**/
	public function get_commands()
	{
		$files = wpl_folder::files($this->get_commands_path(), '.php$', false, false);
		
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
		@return commands path
	**/
	public function get_commands_path()
	{
		return WPL_ABSPATH.'libraries'.DS.'io'.DS. $this->commands_folder .DS;
	}
	
	/**
		@input $cmd string command name
		@return command path
	**/
	public function get_command_path($cmd)
	{
		$path = WPL_ABSPATH.'libraries'.DS.'io'.DS. $this->commands_folder .DS. 'overrides' .DS. $cmd .'.php';
		if(!wpl_file::exists($path)) $path = WPL_ABSPATH.'libraries'.DS.'io'.DS. $this->commands_folder .DS. $cmd .'.php';
		
		return $path;
	}
	
	/**
		@input void
		@return available formats
		@description use this function for getting available formats
	**/
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
		@input $cmd string format name
		@return format path
	**/
	public function get_format_path($format)
	{
		$path = WPL_ABSPATH.'libraries'.DS.'io'.DS. $this->formats_folder .DS. 'overrides' .DS. $format .'.php';
		if(!wpl_file::exists($path)) $path = WPL_ABSPATH.'libraries'.DS.'io'.DS. $this->formats_folder .DS. $format .'.php';
		
		return $path;
	}
}