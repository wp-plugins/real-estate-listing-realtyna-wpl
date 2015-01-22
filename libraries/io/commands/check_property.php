<?php
// no direct access
defined('_WPLEXEC') or die('Restricted access');


class wpl_io_cmd_check_property
{
	var $error = '';
	var $uid;
	var $pid;
	var $built = array();
	var $settings = array
    (
        'include_raw'=>0,
        'access_view'=>'propertyshow'
	);
	var $is_exists; // property existance?
	var $status;
	var $message;
	
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
		
		// Checking essential vars
		if (!isset($vars['pid']) or trim($vars['pid']) == '')
		{ 
			$this->error = "ERROR: No property id set!"; 
			return false;
		}
		
		$this->pid = $vars['pid'];
	}
	
	public function build()
	{
		$this->model = new wpl_property;
		$property = (array) $this->model->get_property_raw_data($this->pid);
		
		$this->status    = ($property) ? 1 : 0;
		$this->is_exists = ($property) ? 'Exists' : 'Not Exists';
		
		$this->build = array('response'=>array('status'=>$this->status, 'message'=>$this->is_exists));
		return $this->build;
	}
}