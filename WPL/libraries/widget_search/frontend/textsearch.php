<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'textsearch' and !$done_this)
{
	switch($field['type'])
	{
		case 'text':
			$show = 'text';
		break;
		
		case 'textarea':
			$show = 'textarea';
		break;
	}
	
	/** current value **/
	$current_value = wpl_request::getVar('sf_textsearch_'.$field_data['table_column'], '');
	
	$html .= '<label for="sf'.$widget_id.'_textsearch_'.$field_data['table_column'].'">'.__($field['name'], WPL_TEXTDOMAIN).'</label>';
	
	if($show == 'text')
	{
		$html .= '<input value="'.$current_value.'" name="sf'.$widget_id.'_textsearch_'.$field_data['table_column'].'" type="text" id="sf'.$widget_id.'_textsearch_'.$field_data['table_column'].'" />';
	}
	elseif($show == 'textarea')
	{
		$html .= '<textarea name="sf'.$widget_id.'_textsearch_'.$field_data['table_column'].'" id="sf'.$widget_id.'_textsearch_'.$field_data['table_column'].'">'.$current_value.'</textarea>';
	}
	
	$done_this = true;
}