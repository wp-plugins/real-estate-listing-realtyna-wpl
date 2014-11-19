<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** Check Access Command
** Developed 05/18/2014
**/

class wpl_io_cmd_check_access extends wpl_io_global
{
	var $user_id;
	var $built = array();
	var $settings = array
	(
		'include_raw'=>1,
		'access_view'=>''
	);
	
	var $status;
	var $message;
	var $error;
	var $access;
	
	function __construct($username, $password, $vars, $settings)
	{
		/** smart set of settings **/
		foreach($settings as $setting=>$setval) if($setval != '') $this->settings[$setting] = $setval;

		// Authenticating
		if($username != '')
		{
			$authenticate = wpl_users::authenticate($username, $password);
			if($authenticate['status'] != 1)
			{
				$this->error = "ERROR: Authentication failed!"; 
				return false;
			}
			
			$this->uid = $authenticate['uid'];
		}
		else $this->uid = 0;
		
		if(!wpl_global::check_access($this->settings['access_view'], $this->uid))
		{
			$this->error = "ERROR: No access to the command!";
			return false;
		}

		$this->access = $vars['access'];
	}
	
	function build()
	{
		if (trim($this->error) == '' and !wpl_global::check_access($this->access,$this->uid))
			$this->error = "Access Error";
		
		if (trim($this->access) == '')
			$this->error = "Empty Access";
		
		$this->status = trim($this->error)  == '' ? 1 : 0;
		$this->message = trim($this->error) == '' ? 'Valid' : $this->error;
		$this->built = array('response'=>array('status'=>$this->status, 'message'=>$this->message));
		
		return $this->built;
	}
}