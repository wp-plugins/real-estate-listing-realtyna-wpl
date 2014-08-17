<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'neighborhood' and !$done_this)
{
	if($values[$field->table_column] == '1')
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		
		if($values[$field->table_column.'_distance'] != 0 and $values[$field->table_column.'_distance_by'] != 0)
		{
			$return['distance'] = $values[$field->table_column.'_distance'];
			
			if($values[$field->table_column.'_distance_by'] == '1') $return['by'] = __('Walk', WPL_TEXTDOMAIN);
			if($values[$field->table_column.'_distance_by'] == '2') $return['by'] = __('Car', WPL_TEXTDOMAIN);
			if($values[$field->table_column.'_distance_by'] == '3') $return['by'] = __('Train', WPL_TEXTDOMAIN);
		}
	}
	
	$done_this = true;
}
