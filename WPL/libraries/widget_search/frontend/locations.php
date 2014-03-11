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
		case 'locationtextsearch':
			$show = 'locationtextsearch';
		break;
		
		case 'radiussearch':
			$show = 'radiussearch';
		break;
		
		case 'simple' and $location_settings['location_method'] == 2:
			$show = 'simple_location_database';
		break;
		
		case 'simple' and $location_settings['location_method'] == 1:
			$show = 'simple_location_text';
		break;
	}
	
	$location_path = WPL_ABSPATH .DS. 'libraries' .DS. 'widget_search' .DS. 'frontend' .DS. 'location_items';
	$location_files = array();
	
	if(wpl_folder::exists($location_path)) $location_files = wpl_folder::files($location_path, '.php$');
	
	foreach($location_files as $location_file)
	{
		include($location_path .DS. $location_file);
	}
}