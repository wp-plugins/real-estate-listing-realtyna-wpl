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
		
		if($function == 'infowindow') self::infowindow();
		elseif($function == 'shortcode_wizard') self::shortcode_wizard();
	}
	
	private function infowindow()
	{
        $wpl_property = new wpl_property();
		$listing_fields = $wpl_property->get_plisting_fields();
		$select = $wpl_property->generate_select($listing_fields, 'p');
		$property_ids = wpl_request::getVar('property_ids', '');
		
		$query = "SELECT ".$select." FROM `#__wpl_properties` AS p WHERE 1 AND p.`deleted`='0' AND p.`finalized`='1' AND p.`confirmed`='1' AND p.`expired`='0' AND p.`id` IN (".$property_ids.")";
		$properties = $wpl_property->search($query);
		
		/** plisting fields **/
		$plisting_fields = $wpl_property->get_plisting_fields();
		
		$this->wpl_properties = array();
		foreach($properties as $property)
		{
			$this->wpl_properties[$property->id] = $wpl_property->full_render($property->id, $plisting_fields, $property);
		}
		
		parent::render($this->tpl_path, 'infowindow');
		exit;
	}
	
	private function shortcode_wizard()
	{
		_wpl_import('libraries.sort_options');
		
		/** global settings **/
		$this->settings = wpl_global::get_settings();
		
		parent::render($this->tpl_path, 'shortcode_wizard');
	}
}