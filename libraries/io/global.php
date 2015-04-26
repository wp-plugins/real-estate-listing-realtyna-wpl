<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** Global Library
** Developed 01/24/2014
**/

class wpl_io_global
{
	var $commands_folder = 'commands';
	var $formats_folder = 'formats';
	
	/**
		@param $cmd = string command name
		@param $dformat = string data format name
		@param $username = string username
		@param $password = string password
		@param $vars = array variables
		@param $dlang = string data language
		@param $settings = array settings
		@return array response
		@description use this function for generating response of command
	**/
	public function response($cmd, $username, $password, $vars, $dlang, $settings, $dformat = 'json')
	{
		/** including command file **/
		$command_file = $this->get_command_path($cmd);
		require_once $command_file;
		
		/** Creating CMD class **/
		$cmd_class = 'wpl_io_cmd_'.$cmd;
		$cmd_object = new $cmd_class($username, $password, $vars, $settings);
		if($cmd_object->error != '') return $cmd_object->error;
		
		/** generating response **/
		$response = $cmd_object->build();
		if ($cmd_object->error != '') return $cmd_object->error;
		
		return $response;
	}
	
	/**
		@param $cmd = string command name
		@param $dformat = string data format name
		@param $username = string username
		@param $dformat = string data format name
		@return array rendered data and header if needed
		@description use this function for rendering response of response function
	**/
	public function render($cmd, $vars, $response, $dformat = 'json')
	{
		/** including command file **/
		$format_file = $this->get_format_path($dformat);
		require_once $format_file;
		
		/** Creating format class **/
		$format_class = 'wpl_io_format_'.$dformat;
		$format_object = new $format_class($cmd, $vars);
		if($format_object->error != '') return $format_object->error;
		
		/** formatting response **/
		$rendered = $format_object->render($response);
		if ($format_object->error != '') return $format_object->error;
		
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