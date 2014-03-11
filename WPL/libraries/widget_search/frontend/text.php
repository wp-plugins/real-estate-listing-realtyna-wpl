<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'text' and !$done_this)
{
	switch($field['type'])
	{
		case 'text':
			$query_type = 'text';
		break;
		
		case 'exacttext':
			$query_type = 'select';
		break;
	}
	
	/** current value **/
	$current_value = wpl_request::getVar('sf_'.$query_type.'_'.$field_data['table_column'], '');

	$html .= '<label for="sf'.$widget_id.'_'.$query_type.'_'.$field_data['table_column'].'">'.__($field['name'], WPL_TEXTDOMAIN).'</label>
				<input name="sf'.$widget_id.'_'.$query_type.'_'.$field_data['table_column'].'" type="text" id="sf'.$widget_id.'_'.$query_type.'_'.$field_data['table_column'].'" value="'.$current_value.'" />';
	
	$done_this = true;
}