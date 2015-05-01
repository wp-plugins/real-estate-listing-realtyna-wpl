<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** Search command
** Developed 01/26/2014
**/

class wpl_io_cmd_search extends wpl_io_global
{
	var $error = '';
	var $uid;
	var $built = array();
	var $settings = array
	(
		'include_raw'=>1,
		'max_limit'=>100,
		'access_view'=>'propertylisting'
	);
	
	//specifics
	var $pictures = 1;
	var $attachments = 0;
	var $videos = 0;
	var $rooms = 0;
	var $agentinfo = 1;
	var $include_thumbnails = 1; // include thumbnails
	var $get_special_key = 0;
	var $special_keys = array();
	var $image_sizes; // image size requested
	
	var $limit = 10;
	var $sort;
	var $asc;
	var $start = 0;
	var $dtranslated = 1;
	var $where = array();
	var $result_type = 3; // type of results
	var $kind = 0;
	
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
		
		/** global settings **/
		$this->wpl_settings = wpl_settings::get_settings();
		
		$this->orderby = $this->wpl_settings['default_orderby'];
		$this->order = $this->wpl_settings['default_order'];
		
		// Include Thumbnails
		if(trim($vars['ithmb']) != '' and in_array($vars['ithmb'], array('0', '1'))) $this->include_thumbnails = $vars['ithmb'];
		
		// get special keys
		if(trim($vars['get_special_key']) != '' and in_array($vars['get_special_key'], array('0', '1')))
		{
			$this->get_special_key = $vars['get_special_key'];
			$this->special_keys = explode('-', $vars['special_keys']);
		}
		
		// Set result type
		if(trim($vars['result_type']) != '' and in_array($vars['result_type'], array('1', '2', '3'))) $this->result_type = $vars['result_type'];
		
		// translate status
		if(trim($vars['dtranslated']) != '' and in_array($vars['dtranslated'], array('0', '1'))) $this->dtranslated = $vars['dtranslated'];
		
		// setting vars
		if(isset($vars['pictures'])) $this->pictures = $vars['pictures'];
		if(isset($vars['attachments'])) $this->attachments = $vars['attachments'];
		if(isset($vars['videos'])) $this->attachments = $vars['videos'];
		if(isset($vars['rooms'])) $this->attachments = $vars['rooms'];
		if(isset($vars['agentinfo'])) $this->agentinfo = $vars['agentinfo'];
		if(isset($vars['sort'])) $this->orderby = $vars['sort'];
		if(isset($vars['asc'])) $this->order = $vars['asc'];
		if(isset($vars['start'])) $this->start = $vars['start'];
		if(isset($vars['limit'])) $this->limit = $vars['limit'];
		if(isset($vars['type'])) $this->type = $vars['type'];
		if(isset($vars['kind'])) $this->kind = $vars['kind'];
		
		/** customized size of images **/
		$this->image_sizes = trim($vars['image_sizes']) != '' ? explode('-', $vars['image_sizes']) : '';
		
		// Checking essential vars
		foreach($vars as $field=>$value)
		{ 
			if(substr($field, 0, 3) != 'sf_') continue;
			
			if(strpos($field, 'sf_select_') === false) $field = str_replace('sf_select', 'sf_select_', $field);
			if(strpos($field, 'sf_unit_') === false) $field = str_replace('sf_unit', 'sf_unit_', $field);
			if(strpos($field, 'sf_min_') === false) $field = str_replace('sf_min', 'sf_min_', $field);
			if(strpos($field, 'sf_max_') === false) $field = str_replace('sf_max', 'sf_max_', $field);
			if(strpos($field, 'sf_tmin_') === false) $field = str_replace('sf_tmin', 'sf_tmin_', $field);
			if(strpos($field, 'sf_tmax_') === false) $field = str_replace('sf_tmax', 'sf_tmax_', $field);
			if(strpos($field, 'sf_multiple_') === false) $field = str_replace('sf_multiple', 'sf_multiple_', $field);
			if(strpos($field, 'sf_datemin_') === false) $field = str_replace('sf_datemin', 'sf_datemin_', $field);
			if(strpos($field, 'sf_datemax_') === false) $field = str_replace('sf_datemax', 'sf_datemax_', $field);
			if(strpos($field, 'sf_locationtextsearch_') === false) $field = str_replace('sf_locationtextsearch', 'sf_locationtextsearch_', $field);
			if(strpos($field, 'sf_notselect_') === false) $field = str_replace('sf_notselect', 'sf_notselect_', $field);
			if(strpos($field, 'sf_radiussearchunit_') === false) $field = str_replace('sf_radiussearchunit', 'sf_radiussearchunit_', $field);
			if(strpos($field, 'sf_text_') === false) $field = str_replace('sf_text', 'sf_text_', $field);
			if(strpos($field, 'sf_textsearch_') === false) $field = str_replace('sf_textsearch', 'sf_textsearch_', $field);
			
			$this->where[$field] = $value;
		}
	}
	
	public function build()
	{
		/** property listing model **/
		$this->model = new wpl_property;
		
		if(!in_array($this->kind, wpl_flex::get_valid_kinds()))
		{
			$this->error = "ERROR: Invalid property kind!";
			return false;
		}
		
		$default_where = array('sf_select_confirmed'=>1, 'sf_select_finalized'=>1, 'sf_select_deleted'=>0, 'sf_select_expired'=>0, 'sf_select_kind'=>$this->kind);
		$this->where = array_merge($default_where, $this->where);
		
		/** Add search conditions to the where **/
        $vars = array_merge(wpl_request::get('POST'), wpl_request::get('GET'));
		$this->where = array_merge($vars, $this->where);
		
		/** start search **/
		$this->model->start($this->start, $this->limit, $this->orderby, $this->order, $this->where);
		$this->model->total = $this->model->get_properties_count();
		
		$this->built['listings']['total'] = $this->model->total;
		
		/** run the search **/
		$query = $this->model->query();
		$properties = $this->model->search();
		
		/** finish search **/
		$this->model->finish();
		
		$plisting_fields = $this->model->get_plisting_fields();
		foreach($properties as $property)
		{
			$rendered = array();
			$rendered = $this->model->full_render($property->id, $plisting_fields, $property);
			$rendered['property_id'] = $property->id;
			
			$items = $rendered['items'];
			/** render gallery **/ if($this->pictures) $rendered['images'] = wpl_items::render_gallery($items['gallery']);
			/** render attachments **/ if($this->attachments) $rendered['attachments'] = wpl_items::render_attachments($items['attachment']);
			/** render videos **/ if($this->videos) $rendered['videos'] = wpl_items::render_videos($items['video']);
			/** render rooms **/ if($this->rooms) $rendered['rooms'] = $items['rooms'];
			/** render gallery custom sizes **/
			if(is_array($this->image_sizes)) $rendered['custom_sizes'] = wpl_items::render_gallery_custom_sizes($property_id, $items['gallery'], $this->image_sizes);
			
			/** render agent data **/
			if($this->agentinfo)
			{
				$rendered['agent'] = wpl_users::full_render($property->user_id);
				unset($rendered['agent']['data']);
				unset($rendered['agent']['items']);
			}
		
			unset($rendered['items']);
			
			$this->built['listings'][$property->id] = $rendered;
		}
		
		return $this->built;
	}
}