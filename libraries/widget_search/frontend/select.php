<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'select' and !$done_this)
{
	switch($field['type'])
	{
		case 'select':
			$show = 'select';
			$any = true;
			$label = true;
		break;
		
		case 'multiple':
			$show = 'multiple';
			$any = false;
			$label = true;
		break;
		
		case 'checkboxes':
			$show = 'checkboxes';
			$any = false;
			$label = true;
		break;
		
		case 'radios':
			$show = 'radios';
			$any = false;
			$label = true;
		break;

		case 'radios_any':
			$show = 'radios';
			$any = true;
			$label = true;
		break;
		
		case 'predefined':
			$show = 'predefined';
			$any = false;
			$label = false;
		break;
	}
	
	/** current value **/
	$current_value = wpl_request::getVar('sf_select_'.$field_data['table_column'], -1);
	
	if($label) $html .= '<label>'.__($field['name'], WPL_TEXTDOMAIN).'</label>';

	if($show == 'select')
	{
		$html .= '<select name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" class="wpl_search_widget_field_'.$field['id'].'" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'">';
		if($any) $html .= '<option value="-1">'.__($field['name'], WPL_TEXTDOMAIN).'</option>';
		
		foreach($options['params'] as $option)
			$html .= '<option value="'.$option['key'].'" '.($current_value == $option['key'] ? 'selected="selected"' : '').'>'.__($option['value'], WPL_TEXTDOMAIN).'</option>';
		
		$html .= '</select>';
	}
	elseif($show == 'multiple')
    {
		/** current value **/
		$current_values = explode(',', wpl_request::getVar('sf_multiple_'.$field_data['table_column']));
	
        $html .= '<div class="wpl_searchwid_'.$field_data['table_column'].'_multiselect_container">
		<select class="wpl_searchmod_'.$field_data['table_column'].'_multiselect" id="sf'.$widget_id.'_multiple_'.$field_data['table_column'].'" name="sf'.$widget_id.'_multiple_'.$field_data['table_column'].'" multiple="multiple">';
		
        foreach($options['params'] as $option)
		{
            $html .= '<option value="'.$option['key'].'" '.(in_array($option['key'], $current_values) ? 'selected="selected"' : '').'>'.__($option['value'], WPL_TEXTDOMAIN).'</option>';
        }
		
        $html .= '</select></div>';
    }
	elseif($show == 'checkboxes')
	{
		/** current value **/
		$current_values = explode(',', wpl_request::getVar('sf_multiple_'.$field_data['table_column']));
		
		$i = 0;
		foreach($options['params'] as $option)
		{
			$i++;
			$html .= '<input '.(in_array($option['key'], $current_values) ? 'checked="checked"' : '').' name="chk'.$widget_id.'_select_'.$field_data['table_column'].'" type="checkbox" value="'.$option['key'].'" id="chk'.$widget_id.'_select_'.$field_data['table_column'].'_'.$i.'" onclick="wpl_add_to_multiple'.$widget_id.'(this.value, this.checked, \''.$field_data['table_column'].'\');"><label for="chk'.$widget_id.'_select_'.$field_data['table_column'].'_'.$i.'">'.__($option['value'], WPL_TEXTDOMAIN).'</label>';
		}
		
		$html .= '<input value="'.implode(',', $current_values).'" type="hidden" id="sf'.$widget_id.'_multiple_'.$field_data['table_column'].'" name="sf'.$widget_id.'_multiple_'.$field_data['table_column'].'" />';
	}
	elseif($show == 'radios')
	{
		$i = 0;
		if($any) $html .= '<input '.($current_value == -1 ? 'checked="checked"' : '').' name="rdo'.$widget_id.'_select_'.$field_data['table_column'].'" type="radio" value="-1" id="rdo'.$widget_id.'_select_'.$field_data['table_column'].'_'.$i.'" onclick="wpl_select_radio'.$widget_id.'(this.value, this.checked, \''.$field_data['table_column'].'\');"><label for="rdo'.$widget_id.'_select_'.$field_data['table_column'].'_'.$i.'">'.__('Any', WPL_TEXTDOMAIN).'</label>';

		foreach($options['params'] as $option)
		{
			$i++;
           	$html .= '<input '.($current_value == $option['key'] ? 'checked="checked"' : '').' name="rdo'.$widget_id.'_select_'.$field_data['table_column'].'" type="radio" value="'.$option['key'].'" id="rdo'.$widget_id.'_select_'.$field_data['table_column'].'_'.$i.'" onclick="wpl_select_radio'.$widget_id.'(this.value, this.checked, \''.$field_data['table_column'].'\');"><label for="rdo'.$widget_id.'_select_'.$field_data['table_column'].'_'.$i.'">'.__($option['value'], WPL_TEXTDOMAIN).'</label>';
		}
		
		$html .= '<input value="'.$current_value.'" type="hidden" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'" name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" />';
	}
	elseif($show == 'predefined')
	{
		$predefined_types = implode(',', $field['extoption']);
		$html .= '<input name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" type="hidden" value="'.$predefined_types.'" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'" />';
	}

	$done_this = true;
}