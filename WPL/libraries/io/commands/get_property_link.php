<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** Get property link command
** Developed 01/26/2014
**/

class wpl_io_cmd_get_property_link extends wpl_io_global
{
	var $error = '';
	var $uid;
	var $built = array();
	var $settings = array
	(
		'include_raw'=>1,
		'access_view'=>'propertyshow'
	);
	
	var $values;
	var $pid;
	
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
		if(!isset($vars['pid']) or trim($vars['pid']) == '')
		{ 
			$this->error = "ERROR: No property id set!"; 
			return false;
		}
		
		$this->pid = $vars['pid'];
	}
	
	public function build()
	{
		/** property listing model **/
		$this->model = new wpl_property;
		
		$property = (array) $this->model->get_property_raw_data($this->pid);
		
		/** no property found **/
		if(!$property)
		{
			$this->error = "ERROR: Property id is not valid."; 
			return false;
		}
		
		$this->built['response']['message']['status'] = "Done";
		$this->built['response']['report']['link'] = $this->model->get_property_link($property);
		$this->built['response']['report']['pid'] = $this->pid;
		
		return $this->built;
	}
}