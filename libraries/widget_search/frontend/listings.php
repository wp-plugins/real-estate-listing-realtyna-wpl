<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'listings' and !$done_this)
{
	$listings = wpl_global::get_listings();
	
	switch($field['type'])
	{
		case 'select':
			$show = 'select';
			$any = true;
			$multiple = false;
			$label = true;
		break;
		
		case 'multiple':
			$show = 'multiple';
			$any = false;
			$multiple = true;
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
		
		case 'select-predefined':
			$show = 'select-predefined';
			$any = true;
			$label = true;
		break;
	}
	
	/** current value **/
	$current_value = wpl_request::getVar('sf_select_'.$field_data['table_column'], 0);
	
	if($label) $html .= '<label for="sf'.$widget_id.'_select_'.$field_data['table_column'].'">'.__($field['name'], WPL_TEXTDOMAIN).'</label>';

	if($show == 'select')
	{
		$html .= '<select name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" class="wpl_search_widget_field_'.$field['id'].'" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'" onchange="wpl_listing_changed'.$widget_id.'(this.value);">';
		if($any) $html .= '<option value="-1">'.__($field['name'], WPL_TEXTDOMAIN).'</option>';
        
		foreach($listings as $listing)
		{
			$html .= '<option value="'.$listing['id'].'" '.($current_value == $listing['id'] ? 'selected="selected"' : '').'>'.__($listing['name'], WPL_TEXTDOMAIN).'</option>';
		}
		
		$html .= '</select>';
	}
	elseif($show == 'multiple')
    {
		/** add scripts and style sheet **/
		$js = (object) array('param1'=>'jquery-multiselect-script', 'param2'=>'js/jquery.ui/multiselect/jquery.multiselect.js');
		wpl_extensions::import_javascript($js);
		
		$style = (object) array('param1'=>'jquery-multiselect-style', 'param2'=>'js/jquery.ui/multiselect/jquery.multiselect.css');
		wpl_extensions::import_style($style);
		
        $html .= '
		<script type="text/javascript">
		wplj(document).ready(function()
		{
			wplj("#sf'.$widget_id.'_multiple_'.$field_data['table_column'].'").multiselect({
				noneSelectedText: "'.__('Any', WPL_TEXTDOMAIN).'",
				checkAllText: "'.__('Check All', WPL_TEXTDOMAIN).'",
				uncheckAllText: "'.__('Uncheck All', WPL_TEXTDOMAIN).'",
				multiple: true
			});
		});
		</script>';
		
		/** current value **/
		$current_values = explode(',', wpl_request::getVar('sf_multiple_'.$field_data['table_column']));
	
        $html .= '<div class="wpl_searchwid_'.$field_data['table_column'].'_multiselect_container">
		<select class="wpl_searchmod_'.$field_data['table_column'].'_multiselect" id="sf'.$widget_id.'_multiple_'.$field_data['table_column'].'" name="sf'.$widget_id.'_multiple_'.$field_data['table_column'].'" multiple="multiple">';
		
        foreach($listings as $listing)
		{
            $html .= '<option value="'.$listing['id'].'" '.(in_array($listing['id'], $current_values) ? 'selected="selected"' : '').'>'.__($listing['name'], WPL_TEXTDOMAIN).'</option>';
        }
		
        $html .= '</select></div>';
    }
	elseif($show == 'checkboxes')
	{
		/** current value **/
		$current_values = explode(',', wpl_request::getVar('sf_multiple_'.$field_data['table_column']));
		
		$i = 0;
		foreach($listings as $listing)
		{
			$i++;
			$html .= '<input '.(in_array($listing['id'], $current_values) ? 'checked="checked"' : '').' name="chk'.$widget_id.'_multiple_'.$field_data['table_column'].'" type="checkbox" value="'.$listing['id'].'" id="chk'.$widget_id.'_multiple_'.$field_data['table_column'].'_'.$i.'" onclick="wpl_add_to_multiple'.$widget_id.'(this.value, this.checked, \''.$field_data['table_column'].'\');"><label for="chk'.$widget_id.'_multiple_'.$field_data['table_column'].'_'.$i.'">'.__($listing['name'], WPL_TEXTDOMAIN).'</label>';
		}
		
		$html .= '<input value="'.implode(',', $current_values).'" type="hidden" id="sf'.$widget_id.'_multiple_'.$field_data['table_column'].'" name="sf'.$widget_id.'_multiple_'.$field_data['table_column'].'" />';
	}
	elseif($show == 'radios')
	{
		$i = 0;
		if($any) $html .= '<input name="rdo'.$widget_id.'_select_'.$field_data['table_column'].'" type="radio" value="-1" id="rdo'.$widget_id.'_select_'.$field_data['table_column'].'_'.$i.'" onclick="wpl_select_radio'.$widget_id.'(this.value, this.checked, \''.$field_data['table_column'].'\');"><label for="rdo'.$widget_id.'_select_'.$field_data['table_column'].'_'.$i.'">'.__('Any', WPL_TEXTDOMAIN).'</label>';
		
		foreach($listings as $listing)
		{
			$i++;
			$html .= '<input '.($current_value == $listing['id'] ? 'checked="checked"' : '').' name="rdo'.$widget_id.'_select_'.$field_data['table_column'].'" type="radio" value="'.$listing['id'].'" id="rdo'.$widget_id.'_select_'.$field_data['table_column'].'_'.$i.'" onclick="wpl_select_radio'.$widget_id.'(this.value, this.checked, \''.$field_data['table_column'].'\');"><label for="rdo'.$widget_id.'_select_'.$field_data['table_column'].'_'.$i.'">'.__($listing['name'], WPL_TEXTDOMAIN).'</label>';
		}
		
		$html .= '<input value="'.$current_value.'" type="hidden" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'" name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" />';
	}
	elseif($show == 'predefined')
	{
		$predefined_types = implode(',', $field['extoption']);
		$html .= '<input name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'" type="hidden" value="'.$predefined_types.'" />';
	}
	elseif($show == 'select-predefined')
	{
		$html .= '<select name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" class="wpl_search_widget_field_'.$field['id'].'" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'" onchange="wpl_listing_changed'.$widget_id.'(this.value);">';
		if($any) $html .= '<option value="-1">'.__('Any', WPL_TEXTDOMAIN).'</option>';
        
		foreach($listings as $listing)
		{
			if(in_array($listing['id'], $field['extoption'])) $html .= '<option value="'.$listing['id'].'" '.($current_value == $listing['id'] ? 'selected="selected"' : '').'>'.__($listing['name'], WPL_TEXTDOMAIN).'</option>';
		}
		
		$html .= '</select>';
	}

	$done_this = true;
}