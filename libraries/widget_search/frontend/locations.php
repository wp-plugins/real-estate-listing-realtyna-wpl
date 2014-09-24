<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'locations' and !$done_this)
{
	/** import library **/
	_wpl_import('libraries.locations');
	$location_settings = wpl_global::get_settings('3'); # location settings
	
	switch($field['type'])
	{
		case 'simple':
            
            if($location_settings['location_method'] == 2) $show = 'simple_location_database';
            else $show = 'simple_location_text';
            
		break;
            
        default:
			$show = $field['type'];
		break;
	}
	
    /** Place-holder **/
	$placeholder = (isset($field['extoption']) and trim($field['extoption'])) ? $field['extoption'] : $location_settings['locationzips_keyword'].', '.$location_settings['location3_keyword'].', '.$location_settings['location1_keyword'];
    
	$location_path = WPL_ABSPATH .DS. 'libraries' .DS. 'widget_search' .DS. 'frontend' .DS. 'location_items';
	$location_files = array();
	
	if(wpl_folder::exists($location_path)) $location_files = wpl_folder::files($location_path, '.php$');
	
	foreach($location_files as $location_file)
	{
		include($location_path .DS. $location_file);
	}
}