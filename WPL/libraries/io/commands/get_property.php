<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** Get Property command
** Developed 01/24/2014
**/

class wpl_io_cmd_get_property extends wpl_io_global
{
	var $error = '';
	var $uid;
	var $pid;
	var $dtranslated = 1;
	var $built = array();
	var $settings = array
	(
		'include_raw'=>1,
		'access_view'=>'propertyshow'
	);
	
	var $include_thumbnails = 1; // include thumbnails
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
		
		// Include Thumbnails
		if(trim($vars['ithmb']) != '' and in_array($vars['ithmb'], array('0', '1'))) $this->include_thumbnails = $vars['ithmb'];
		
		// translate status
		if(trim($vars['dtranslated']) != '' and in_array($vars['dtranslated'], array('0', '1'))) $this->dtranslated = $vars['dtranslated'];
		
		// Checking essential vars
		if(!isset($vars['pid']) or trim($vars['pid']) == '')
		{ 
			$this->error = "ERROR: No property id set!"; 
			return false;
		}
		
		$this->pid = $vars['pid'];
		$this->image_sizes = trim($vars['image_sizes']) != '' ? explode('-', $vars['image_sizes']) : '';
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
		
		$this->pshow_fields = $this->model->get_pshow_fields('', $property['kind']);
		$this->pshow_categories = wpl_flex::get_categories('', '', " AND `enabled`>='1' AND `kind`='".$property['kind']."' AND `pshow`='1'");
		
		/** BUILD **/
		$this->built['listing']['raw'] = $property;
		$this->built['listing']['rendered_raw'] = $this->model->render_property($property, $this->pshow_fields);
		
		foreach($this->pshow_categories as $pshow_category)
		{
			$pshow_cat_fields = $this->model->get_pshow_fields($pshow_category->id, $property['kind']);
			$this->built['listing']['rendered'][$pshow_category->id]['self'] = (array) $pshow_category;
			$this->built['listing']['rendered'][$pshow_category->id]['data'] = $this->model->render_property($property, $pshow_cat_fields);
		}
		
		$items = wpl_items::get_items($this->pid, '', $property['kind'], '', 1);
		
		/** render gallery **/ $this->built['listing']['images'] = wpl_items::render_gallery($items['gallery']);
		/** render attachments **/ $this->built['listing']['attachments'] = wpl_items::render_attachments($items['attachment']);
		/** render videos **/ $this->built['listing']['videos'] = wpl_items::render_videos($items['video']);
		/** render rooms **/ $this->built['listing']['rooms'] = $items['rooms'];
		/** render gallery custom sizes **/ if(is_array($this->image_sizes)) $this->built['listing']['custom_sizes'] = wpl_items::render_gallery_custom_sizes($this->pid, $items['gallery'], $this->image_sizes);
		/** property full link **/ $this->built['listing']['property_link'] = $this->model->get_property_link($property);
		/** location text **/ $this->built['listing']['location_text'] = $property['location_text'];
		
		/** render agent data **/
		$rendered_agent = wpl_users::full_render($property['user_id']);
		unset($rendered_agent['data']);
		$this->built['listing']['agent'] = $rendered_agent;
		
		/** updating the visited times and etc **/
		$this->model->property_visited($this->pid);
		
		return $this->built;
	}
}