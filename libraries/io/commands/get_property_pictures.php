<?php
// no direct access
defined('_WPLEXEC') or die('Restricted access');


class wpl_io_cmd_get_property_pictures extends wpl_io_global
{
	var $error = '';
	var $uid;
	var $pid;
	var $built = array();
	var $settings = array(
		'include_raw'=>0,
		'access_view' => 'propertyshow'
	);
	var $image_sizes; // image size requested
	
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
		$this->image_sizes = trim($vars['image_sizes']) != '' ? explode('-', $vars['image_sizes']) : '';			
	}
	
	public function build()
	{
		$this->model = new wpl_property;
		
		$property = (array) $this->model->get_property_raw_data($this->pid);
		
		/** no property found **/
		if(!$property)
		{
			$this->error = "ERROR: Property id is not valid."; 
			return false;
		}
		
		$items = wpl_items::get_items($this->pid, '', $property['kind'], '', 1);
		
		/** render gallery **/ 
		$images = wpl_items::render_gallery($items['gallery']);
		$this->built['listing']['images'] = $images;
				
		return $this->built;
	}
	
}
	