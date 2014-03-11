<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'neighborhood' and !$done_this)
{
	if($values['n_'.$field->id] == '1')
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		
		if($values['n_'.$field->id.'_distance'] != 0 and $values["n_".$field->id."_distance_by"] != 0)
		{
			$return['distance'] = $values['n_'.$field->id.'_distance'];
			
			if($values["n_".$field->id."_distance_by"] == '1') $return['by'] = __('Walk', WPL_TEXTDOMAIN);
			if($values["n_".$field->id."_distance_by"] == '2') $return['by'] = __('Car', WPL_TEXTDOMAIN);
			if($values["n_".$field->id."_distance_by"] == '3') $return['by'] = __('Train', WPL_TEXTDOMAIN);
		}
	}
	
	$done_this = true;
}
