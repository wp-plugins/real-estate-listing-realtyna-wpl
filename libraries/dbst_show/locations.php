<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'locations' and !$done_this)
{
	_wpl_import('libraries.locations');
	$location_settings = wpl_global::get_settings('3'); # location settings
	
	$return['field_id'] = $field->id;
	$return['type'] = $field->type;
	$return['name'] = __($field->name, WPL_TEXTDOMAIN);
	
	for($i=1; $i<=7; $i++)
	{
		$location_id = isset($values['location'.$i.'_id']) ? $values['location'.$i.'_id'] : NULL;
		if(!isset($values['location'.$i.'_name'])) continue;
		if(!trim($values['location'.$i.'_name'])) continue;
		
		$return['location_ids'][$i] = $location_id;
		$return['locations'][$i] = $values['location'.$i.'_name'];
		$return['keywords'][$i] = $location_settings['location'.$i.'_keyword'];
	}
	
	if(trim($values['zip_name']))
	{
		$return['location_ids']['zips'] = $values['zip_id'];
		$return['locations']['zips'] = $values['zip_name'];
		$return['keywords']['zips'] = $location_settings['locationzips_keyword'];
	}
	
	$done_this = true;
}

