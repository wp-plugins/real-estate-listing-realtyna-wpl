<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'neighborhood' and !$done_this)
{
	switch($field['type'])
	{	
		case 'checkbox':
			$show = 'checkbox';
		break;
		
		case 'yesno':
			$show = 'yesno';
		break;
		
		case 'select':
			$show = 'select';
		break;
	}
	
	/** current value **/
	$current_value = wpl_request::getVar('sf_select_'.$field_data['table_column'], -1);

	if($show == 'checkbox')
	{
    	$html .= '<input value="1" '.($current_value == 1 ? 'checked="checked"' : '').' name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" type="checkbox" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'" class="wpl_search_widget_field_'.$field['id'].'_check" />
        	<label for="sf'.$widget_id.'_select_'.$field_data['table_column'].'">'.__($field['name'], WPL_TEXTDOMAIN).'</label>';
	}
	if($show == 'yesno')
	{
    	$html .= '<input value="1" '.($current_value == 1 ? 'checked="checked"' : '').' name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" type="checkbox" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'" class="wpl_search_widget_field_'.$field['id'].'_check yesno" />
        	<label for="sf'.$widget_id.'_select_'.$field_data['table_column'].'">'.__($field['name'], WPL_TEXTDOMAIN).'</label>';
	}
	elseif($show == "select")
	{
		$html .= '<label for="sf'.$widget_id.'_select_'.$field_data['table_column'].'">'.__($field['name'], WPL_TEXTDOMAIN).'</label>
			<select name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'" class="wpl_search_widget_field_'.$field['id'].'_select">
				<option value="-1" '.($current_value == -1 ? 'selected="selected"' : '').'>'.__('Any', WPL_TEXTDOMAIN).'</option>
				<option value="1" '.($current_value == 1 ? 'selected="selected"' : '').'>'.__('Yes', WPL_TEXTDOMAIN).'</option>
				<option value="0" '.($current_value == 0 ? 'selected="selected"' : '').'>'.__('No', WPL_TEXTDOMAIN).'</option>
			</select>';
	}
	
	$done_this = true;
}