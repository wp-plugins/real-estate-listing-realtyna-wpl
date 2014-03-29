<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.flex');
_wpl_import('libraries.property');
_wpl_import('libraries.images');
		
class wpl_functions_controller extends wpl_controller
{
	var $tpl_path = 'views.basics.functions.tmpl';
	var $tpl;
	
	public function display()
	{
		$function = wpl_request::getVar('wpl_function');
		
		if($function == 'ajax_save') self::ajax_save();
		elseif($function == 'infowindow') self::infowindow();
		elseif($function == 'shortcode_wizard') self::shortcode_wizard();
		elseif($function == 'save_theme_options') self::save_theme_options();
		elseif($function == 'clear_empty_data') self::clear_empty_data();
	}
	
	private function ajax_save()
	{
		$table = wpl_request::getVar('table');
		$key = wpl_request::getVar('key');
		$value = wpl_request::getVar('value');
		$id = wpl_request::getVar('id');
		
		$query = "UPDATE `#__$table` SET `$key`='$value' WHERE id='$id'";
		$res = wpl_db::q($query);
		
		/** trigger event **/
		wpl_global::event_handler('ajax_save_done', array('table'=>$table, 'key'=>$key, 'value'=>$value, 'id'=>$id));
		
		$res = (int) $res;
		$message = $res ? __('Saved.') : __('Error Occured.');
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
	
	private function save_theme_options()
	{
		$wpl_theme_options = wpl_request::getVar('wpl_theme_options', array());
		
		delete_option('wpl_theme_options');
		add_option('wpl_theme_options', $wpl_theme_options);
		
		$res = 1;
		$message = $res ? __('Saved.') : __('Error Occured.');
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
	
	private function infowindow()
	{
		$listing_fields = wpl_property::get_plisting_fields();
		$select = wpl_property::generate_select($listing_fields, 'p');
		$property_ids = wpl_request::getVar('property_ids', '');
		
		$query = "SELECT ".$select." FROM `#__wpl_properties` AS p WHERE 1 AND p.`deleted`='0' AND p.`finalized`='1' AND p.`confirmed`='1' AND p.`id` IN (".$property_ids.")";
		$properties = wpl_property::search($query);
		
		/** plisting fields **/
		$plisting_fields = wpl_property::get_plisting_fields();
		
		$this->wpl_properties = array();
		foreach($properties as $property)
		{
			$this->wpl_properties[$property->id] = wpl_property::full_render($property->id, $plisting_fields, $property);
		}
		
		parent::display($this->tpl_path, 'infowindow');
		exit;
	}
	
	private function shortcode_wizard()
	{
		_wpl_import('libraries.sort_options');
		
		/** global settings **/
		$this->settings = wpl_global::get_settings();
		
		parent::display($this->tpl_path, 'shortcode_wizard');
	}
	
	private function clear_empty_data()
	{
		_wpl_import('libraries.property_types');
		_wpl_import('libraries.listing_types');

		wpl_property_types::clear_empty_property_types();
		wpl_listing_types::clear_empty_listing_types();
		
		exit;
	}
}