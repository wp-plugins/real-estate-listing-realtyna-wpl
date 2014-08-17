<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'textarea' and !$done_this)
{
	/** current value **/
	$current_value = wpl_request::getVar('sf_text_'.$field_data['table_column'], '');
	
	$html .= '<label for="sf'.$widget_id.'_text_'.$field_data['table_column'].'">'.__($field['name'], WPL_TEXTDOMAIN).'</label>
				<textarea name="sf'.$widget_id.'_text_'.$field_data['table_column'].'" id="sf'.$widget_id.'_text_'.$field_data['table_column'].'">'.$current_value.'</textarea>';

	$done_this = true;
}