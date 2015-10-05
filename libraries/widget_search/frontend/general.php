<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'gallery' and !$done_this)
{
	/** current value **/
	$current_value = wpl_request::getVar('sf_gallery', -1);
	
	$html .= '<input value="1" '.($current_value == 1 ? 'checked="checked"' : '').' name="sf'.$widget_id.'_gallery" type="checkbox" id="sf'.$widget_id.'_gallery" />
				<label for="sf'.$widget_id.'_gallery">'.__($field['name'], WPL_TEXTDOMAIN).'</label>';

	$done_this = true;
}
elseif($type == 'date' and !$done_this)
{
	/** system date format **/
	$date_format_arr = explode(':', wpl_global::get_setting('main_date_format'));
	$jqdate_format = $date_format_arr[1];
	
	/** MIN/MAX extoptions **/
	$extoptions = explode(',', $field['extoption']);
	
	$min_value = (isset($extoptions[0]) and trim($extoptions[0]) != '') ? $extoptions[0] : '1990-01-01';
	$max_value = (isset($extoptions[1]) and trim($extoptions[1]) != '') ? $extoptions[1] : '2025-01-01';
	$show_icon = (isset($extoptions[2]) and trim($extoptions[2]) != '') ? $extoptions[2] : 0;
	
	$mindate = explode('-', $min_value);
	$maxdate = explode('-', $max_value);
	
	switch($field['type'])
	{
		case 'datepicker':
			$show = 'datepicker';
		break;
	}

	$html .= '<label>'.__($field['name'], WPL_TEXTDOMAIN).'</label>';
	
	if($show == 'datepicker')
	{
		/** current value **/
		$current_min_value = wpl_request::getVar('sf_datemin_'.$field_data['table_column'], '');
		$current_max_value = wpl_request::getVar('sf_datemax_'.$field_data['table_column'], '');
		
    	$html .= '<div class="wpl_search_widget_from_container"><label for="sf'.$widget_id.'_datemin_'.$field_data['table_column'].'">'.__('Min', WPL_TEXTDOMAIN).'</label><input type="text" placeholder="'.sprintf(__('Min %s', WPL_TEXTDOMAIN), __($field['name'], WPL_TEXTDOMAIN)).'" name="sf'.$widget_id.'_datemin_'.$field_data['table_column'].'" id="sf'.$widget_id.'_datemin_'.$field_data['table_column'].'" value="'.($current_min_value != '' ? $current_min_value : '').'" /></div>';
    	$html .= '<div class="wpl_search_widget_to_container"><label for="sf'.$widget_id.'_datemax_'.$field_data['table_column'].'">'.__('Max', WPL_TEXTDOMAIN).'</label><input type="text" placeholder="'.sprintf(__('Min %s', WPL_TEXTDOMAIN), __($field['name'], WPL_TEXTDOMAIN)).'" name="sf'.$widget_id.'_datemax_'.$field_data['table_column'].'" id="sf'.$widget_id.'_datemax_'.$field_data['table_column'].'" value="'.($current_max_value != '' ? $current_max_value : '').'" /></div>';
		
		$html .= '
		<script type="text/javascript">
		wplj(document).ready(function()
		{
			wplj("#sf'.$widget_id.'_datemax_'.$field_data['table_column'].'").datepicker(
			{ 
				dayNamesMin: ["'.addslashes(__('SU', WPL_TEXTDOMAIN)).'", "'.addslashes(__('MO', WPL_TEXTDOMAIN)).'", "'.addslashes(__('TU', WPL_TEXTDOMAIN)).'", "'.addslashes(__('WE', WPL_TEXTDOMAIN)).'", "'.addslashes(__('TH', WPL_TEXTDOMAIN)).'", "'.addslashes(__('FR', WPL_TEXTDOMAIN)).'", "'.addslashes(__('SA', WPL_TEXTDOMAIN)).'"],
				dayNames: 	 ["'.addslashes(__('Sunday', WPL_TEXTDOMAIN)).'", "'.addslashes(__('Monday', WPL_TEXTDOMAIN)).'", "'.addslashes(__('Tuesday', WPL_TEXTDOMAIN)).'", "'.addslashes(__('Wednesday', WPL_TEXTDOMAIN)).'", "'.addslashes(__('Thursday', WPL_TEXTDOMAIN)).'", "'.addslashes(__('Friday', WPL_TEXTDOMAIN)).'", "'.addslashes(__('Saturday', WPL_TEXTDOMAIN)).'"],
				monthNames:  ["'.addslashes(__('January', WPL_TEXTDOMAIN)).'", "'.addslashes(__('February', WPL_TEXTDOMAIN)).'", "'.addslashes(__('March', WPL_TEXTDOMAIN)).'", "'.addslashes(__('April', WPL_TEXTDOMAIN)).'", "'.addslashes(__('May', WPL_TEXTDOMAIN)).'", "'.addslashes(__('June', WPL_TEXTDOMAIN)).'", "'.addslashes(__('July', WPL_TEXTDOMAIN)).'", "'.addslashes(__('August', WPL_TEXTDOMAIN)).'", "'.addslashes(__('September', WPL_TEXTDOMAIN)).'", "'.addslashes(__('October', WPL_TEXTDOMAIN)).'", "'.addslashes(__('November', WPL_TEXTDOMAIN)).'", "'.addslashes(__('December', WPL_TEXTDOMAIN)).'"],
				dateFormat: "'.addslashes($jqdate_format).'",
				gotoCurrent: true,
				minDate: new Date('.$mindate[0].', '.intval($mindate[1]).'-1, '.$mindate[2].'),
				maxDate: new Date('.$maxdate[0].', '.intval($maxdate[1]).'-1, '.$maxdate[2].'),
				changeYear: true,
				yearRange: "'.$mindate[0].':'.$maxdate[0].'",
				'.($show_icon == '1' ? 'showOn: "both", buttonImage: "'.wpl_global::get_wpl_asset_url('img/system/calendar2.png').'",' : '').'
				buttonImageOnly: true
			});

			wplj("#sf'.$widget_id.'_datemin_'.$field_data['table_column'].'").datepicker(
			{ 
				dayNamesMin: ["'.addslashes(__('SU', WPL_TEXTDOMAIN)).'", "'.addslashes(__('MO', WPL_TEXTDOMAIN)).'", "'.addslashes(__('TU', WPL_TEXTDOMAIN)).'", "'.addslashes(__('WE', WPL_TEXTDOMAIN)).'", "'.addslashes(__('TH', WPL_TEXTDOMAIN)).'", "'.addslashes(__('FR', WPL_TEXTDOMAIN)).'", "'.addslashes(__('SA', WPL_TEXTDOMAIN)).'"],
				dayNames: 	 ["'.addslashes(__('Sunday', WPL_TEXTDOMAIN)).'", "'.addslashes(__('Monday', WPL_TEXTDOMAIN)).'", "'.addslashes(__('Tuesday', WPL_TEXTDOMAIN)).'", "'.addslashes(__('Wednesday', WPL_TEXTDOMAIN)).'", "'.addslashes(__('Thursday', WPL_TEXTDOMAIN)).'", "'.addslashes(__('Friday', WPL_TEXTDOMAIN)).'", "'.addslashes(__('Saturday', WPL_TEXTDOMAIN)).'"],
				monthNames:  ["'.addslashes(__('January', WPL_TEXTDOMAIN)).'", "'.addslashes(__('February', WPL_TEXTDOMAIN)).'", "'.addslashes(__('March', WPL_TEXTDOMAIN)).'", "'.addslashes(__('April', WPL_TEXTDOMAIN)).'", "'.addslashes(__('May', WPL_TEXTDOMAIN)).'", "'.addslashes(__('June', WPL_TEXTDOMAIN)).'", "'.addslashes(__('July', WPL_TEXTDOMAIN)).'", "'.addslashes(__('August', WPL_TEXTDOMAIN)).'", "'.addslashes(__('September', WPL_TEXTDOMAIN)).'", "'.addslashes(__('October', WPL_TEXTDOMAIN)).'", "'.addslashes(__('November', WPL_TEXTDOMAIN)).'", "'.addslashes(__('December', WPL_TEXTDOMAIN)).'"],
				dateFormat: "'.addslashes($jqdate_format).'",
				gotoCurrent: true,
				minDate: new Date('.$mindate[0].', '.intval($mindate[1]).'-1, '.$mindate[2].'),
				maxDate: new Date('.$maxdate[0].', '.intval($maxdate[1]).'-1, '.$maxdate[2].'),
				changeYear: true,
				yearRange: "'.$mindate[0].':'.$maxdate[0].'",
				'.($show_icon == '1' ? 'showOn: "both", buttonImage: "'.wpl_global::get_wpl_asset_url('img/system/calendar2.png').'",' : '').'
				buttonImageOnly: true
			});
		});
		</script>';
	}
	
	$done_this = true;
}
elseif($type == 'feature' and !$done_this)
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
    
        case 'option_single':
            
			$show = 'options';
            $multiple = false;
            
		break;
    
        case 'option_multiple':
            
			$show = 'options';
            $multiple = true;
            
		break;
	}
	
	/** current value **/
	$current_value = wpl_request::getVar('sf_select_'.$field_data['table_column'], -1);
	
	if($show == 'checkbox')
	{
		$html .= '<input value="1" '.($current_value == 1 ? 'checked="checked"' : '').' name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" type="checkbox" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'" class="wpl_search_widget_field_'.$field['id'].'_check" />
        	<label for="sf'.$widget_id.'_select_'.$field_data['table_column'].'">'.__($field['name'], WPL_TEXTDOMAIN).'</label>';
	}
	elseif($show == 'yesno')
	{
		$html .= '<input value="1" '.($current_value == 1 ? 'checked="checked"' : '').' name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" type="checkbox" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'" class="wpl_search_widget_field_'.$field['id'].'_check yesno" />
        	<label for="sf'.$widget_id.'_select_'.$field_data['table_column'].'">'.__($field['name'], WPL_TEXTDOMAIN).'</label>';
	}
	elseif($show == 'select')
	{
		$html .= '<label for="sf'.$widget_id.'_select_'.$field_data['table_column'].'">'.__($field['name'], WPL_TEXTDOMAIN).'</label>
			<select name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'" class="wpl_search_widget_field_'.$field['id'].'_select">
				<option value="-1" '.($current_value == -1 ? 'selected="selected"' : '').'>'.__('Any', WPL_TEXTDOMAIN).'</option>
				<option value="1" '.($current_value == 1 ? 'selected="selected"' : '').'>'.__('Yes', WPL_TEXTDOMAIN).'</option>
				<option value="0" '.($current_value == 0 ? 'selected="selected"' : '').'>'.__('No', WPL_TEXTDOMAIN).'</option>
			</select>';
	}
    elseif($show == 'options')
	{
        /** current value **/
        $current_value = explode(',', wpl_request::getVar('sf_feature_'.$field_data['table_column'], ''));
        if(trim($current_value[0]) == '') $current_value = array();
        
		$html .= '<label for="sf'.$widget_id.'_feature_'.$field_data['table_column'].'">'.__($field['name'], WPL_TEXTDOMAIN).'</label>
			<select name="sf'.$widget_id.'_feature_'.$field_data['table_column'].'" id="sf'.$widget_id.'_feature_'.$field_data['table_column'].'" class="wpl_search_widget_field_'.$field['id'].'_select" '.($multiple ? 'multiple="multiple"' : '').'>';
        
		if(!$multiple) $html .= '<option value="">'.__('Any', WPL_TEXTDOMAIN).'</option>';
        foreach($options['values'] as $option) $html .= '<option value="'.$option['key'].'" '.(in_array($option['key'], $current_value) ? 'selected="selected"' : '').'>'.__($option['value'], WPL_TEXTDOMAIN).'</option>';
                
		$html .= '</select>';
	}
	
	$done_this = true;
}
elseif(($type == 'checkbox' or $type == 'tag') and !$done_this)
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
	elseif($show == 'yesno')
	{
		$html .= '<input value="1" '.($current_value == 1 ? 'checked="checked"' : '').' name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" type="checkbox" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'" class="wpl_search_widget_field_'.$field['id'].'_check yesno" />
        	<label for="sf'.$widget_id.'_select_'.$field_data['table_column'].'">'.__($field['name'], WPL_TEXTDOMAIN).'</label>';
	}
	elseif($show == 'select')
	{
		$html .= '<label for="sf'.$widget_id.'_select_'.$field_data['table_column'].'">'.__($field['name'], WPL_TEXTDOMAIN).'</label>
			<select name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'" class="wpl_search_widget_field_'.$field['id'].'_select">
				<option value="-1" '.($current_value == -1 ? 'selected="selected"' : '').'>'.__('Any', WPL_TEXTDOMAIN).'</option>
				<option value="1" '.($current_value == 1 ? 'selected="selected"' : '').'>'.__('Yes', WPL_TEXTDOMAIN).'</option>
			</select>';
	}
	
	$done_this = true;
}
elseif($type == 'listings' and !$done_this)
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
		/** current value **/
		$current_values = explode(',', wpl_request::getVar('sf_multiple_'.$field_data['table_column']));
	
        $html .= '<div class="wpl_searchwid_'.$field_data['table_column'].'_multiselect_container">
		<select data-placeholder="'.__($field['name'], WPL_TEXTDOMAIN).'" class="wpl_searchmod_'.$field_data['table_column'].'_multiselect" id="sf'.$widget_id.'_multiple_'.$field_data['table_column'].'" name="sf'.$widget_id.'_multiple_'.$field_data['table_column'].'" multiple="multiple">';
		
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
elseif($type == 'neighborhood' and !$done_this)
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
	elseif($show == 'yesno')
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
elseif($type == 'number' and !$done_this)
{
	switch($field['type'])
	{
		case 'text':
			$show = 'text';
		break;
		
		case 'exacttext':
			$show = 'exacttext';
		break;
		
		case 'minmax':
			$show = 'minmax';
		break;
		
		case 'minmax_slider':
			$show = 'minmax_slider';
		break;
		
		case 'minmax_selectbox':
			$show = 'minmax_selectbox';
		break;
		
		case 'minmax_selectbox_plus':
			$show = 'minmax_selectbox_plus';
        break;
        
        case 'minmax_selectbox_minus':
			$show = 'minmax_selectbox_minus';
		break;
    
        case 'minmax_selectbox_range':
			$show = 'minmax_selectbox_range';
		break;
	}
	
	/** MIN/MAX extoptions **/
	$extoptions = isset($field['extoption']) ? explode(',', $field['extoption']) : array();
    
	$min_value = (isset($extoptions[0]) and trim($extoptions[0]) != '') ? $extoptions[0] : 0;
	$max_value = isset($extoptions[1]) ? $extoptions[1] : 100000;
	$division = isset($extoptions[2]) ? $extoptions[2] : 1000;
	$separator = isset($extoptions[3]) ? $extoptions[3] : ',';
	
    $html .= '<label>'.__($field['name'], WPL_TEXTDOMAIN).'</label>';

	/** current values **/
	$current_min_value = max(stripslashes(wpl_request::getVar('sf_tmin_'.$field_data['table_column'], $min_value)), $min_value);
	$current_max_value = min(stripslashes(wpl_request::getVar('sf_tmax_'.$field_data['table_column'], $max_value)), $max_value);
    
	if($show == 'text')
	{
		/** current values **/
		$current_value = stripslashes(wpl_request::getVar('sf_text_'.$field_data['table_column'], ''));
		
    	$html .= '<input name="sf'.$widget_id.'_text_'.$field_data['table_column'].'" type="text" id="sf'.$widget_id.'_text_'.$field_data['table_column'].'" value="'.$current_value.'" placeholder="'.__($field['name'], WPL_TEXTDOMAIN).'" />';
	}
	elseif($show == 'exacttext')
	{
		/** current values **/
		$current_value = stripslashes(wpl_request::getVar('sf_select_'.$field_data['table_column'], ''));
		
    	$html .= '<input name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" type="text" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'" value="'.$current_value.'"  placeholder="'.__($field['name'], WPL_TEXTDOMAIN).'"/>';
	}
    elseif($show == 'minmax')
	{	
		$html .= '<label for="sf'.$widget_id.'_tmin_'.$field_data['table_column'].'">'.__('Min', WPL_TEXTDOMAIN).'</label>';
		$html .= '<input name="sf'.$widget_id.'_tmin_'.$field_data['table_column'].'" type="text" id="sf'.$widget_id.'_tmin_'.$field_data['table_column'].'" value="'.$current_min_value.'" placeholder="'.__('Min', WPL_TEXTDOMAIN).'" />';
        
		$html .= '<label for="sf'.$widget_id.'_tmax_'.$field_data['table_column'].'">'.__('Max', WPL_TEXTDOMAIN).'</label>';
		$html .= '<input name="sf'.$widget_id.'_tmax_'.$field_data['table_column'].'" type="text" id="sf'.$widget_id.'_tmax_'.$field_data['table_column'].'" value="'.$current_max_value.'" placeholder="'.__('Max', WPL_TEXTDOMAIN).'" />';
	}
	elseif($show == 'minmax_slider')
	{
		$html .= '<script type="text/javascript">
				wplj(function()
				{
					wplj("#slider'.$widget_id.'_range_'.$field_data['table_column'].'" ).slider(
					{
						step: '.$division.',
						range: true,
						min: '.(is_numeric($min_value) ? $min_value : 0).',
						max: '.$max_value.',
                        field_id: '.$field['id'].',
						values: ['.$current_min_value.', '.$current_max_value.'],
						slide: function(event, ui)
						{
							v1 = wpl_th_sep'.$widget_id.'(ui.values[0]);
							v2 = wpl_th_sep'.$widget_id.'(ui.values[1]);
							wplj("#slider'.$widget_id.'_showvalue_'.$field_data['table_column'].'").html(v1+" - "+ v2);
						},
						stop: function(event, ui)
						{
							wplj("#sf'.$widget_id.'_tmin_'.$field_data['table_column'].'").val(ui.values[0]);
							wplj("#sf'.$widget_id.'_tmax_'.$field_data['table_column'].'").val(ui.values[1]);
							'.((isset($this->ajax) and $this->ajax == 2) ? 'wpl_do_search_'.$widget_id.'();' : '').'
						}
					});
				});
				</script>';
		
		$html .= '<span class="wpl_search_slider_container">
				<input type="hidden" value="'.$current_min_value.'" name="sf'.$widget_id.'_tmin_'.$field_data['table_column'].'" id="sf'.$widget_id.'_tmin_'.$field_data['table_column'].'" /><input type="hidden" value="'.$current_max_value.'" name="sf'.$widget_id.'_tmax_'.$field_data['table_column'].'" id="sf'.$widget_id.'_tmax_'.$field_data['table_column'].'" />
				<span class="wpl_slider_show_value" id="slider'.$widget_id.'_showvalue_'.$field_data['table_column'].'">'.number_format((double) $current_min_value, 0, '', $separator).' - '.number_format((double) $current_max_value, 0, '', $separator).'</span>
				<span class="wpl_span_block" style="width: 92%; height: 20px;"><span class="wpl_span_block" id="slider'.$widget_id.'_range_'.$field_data['table_column'].'" ></span></span>
				</span>';
	}
    elseif($show == 'minmax_selectbox')
	{
    	$html .= '<select name="sf'.$widget_id.'_tmin_'.$field_data['table_column'].'" id="sf'.$widget_id.'_tmin_'.$field_data['table_column'].'">';
		
		$i = $min_value;
		$html .= '<option value="0" '.($current_min_value == $i ? 'selected="selected"' : '').'>'.__('Min '.$field_data['name'], WPL_TEXTDOMAIN).'</option>';
        
        $selected_printed = false;
        if($current_min_value == $i) $selected_printed = true;

		while($i < $max_value)
		{
			$html .= '<option value="'.$i.'" '.(($current_min_value == $i and !$selected_printed) ? 'selected="selected"' : '').'>'.$i.'</option>';
			$i += $division;
		}
		
		$html .= '<option value="'.$max_value.'" '.(($current_min_value == $i and !$selected_printed) ? 'selected="selected"' : '').'>'.$max_value.'</option>';
        $html .= '</select>';
        
        $html .= '<select name="sf'.$widget_id.'_tmax_'.$field_data['table_column'].'" id="sf'.$widget_id.'_tmax_'.$field_data['table_column'].'">';
        
        $i = $min_value;
		$html .= '<option value="999999999999" '.($current_max_value == $i ? 'selected="selected"' : '').'>'.__('Max '.$field_data['name'], WPL_TEXTDOMAIN).'</option>';
		
		$selected_printed = false;
        if($current_max_value == $i) $selected_printed = true;
        
		while($i < $max_value)
		{
			$html .= '<option value="'.$i.'" '.(($current_max_value == $i and !$selected_printed) ? 'selected="selected"' : '').'>'.$i.'</option>';
			$i += $division;
		}
		
		$html .= '<option value="'.$max_value.'">'.$max_value.'</option>';
        $html .= '</select>';
	}
    elseif($show == 'minmax_selectbox_plus')
	{
        $i = $min_value;
        
		$html .= '<select name="sf'.$widget_id.'_tmin_'.$field_data['table_column'].'" id="sf'.$widget_id.'_tmin_'.$field_data['table_column'].'">';
		$html .= '<option value="-1" '.($current_min_value == $i ? 'selected="selected"' : '').'>'.__($field['name'], WPL_TEXTDOMAIN).'</option>';
		
        $selected_printed = false;
        if($current_min_value == $i) $selected_printed = true;
        
		while($i < $max_value)
		{
            if($i == '0')
			{
				$i += $division;
				continue;
			}
            
			$html .= '<option value="'.$i.'" '.(($current_min_value == $i and !$selected_printed) ? 'selected="selected"' : '').'>'.$i.'+</option>';
			$i += $division;
		}
		
		$html .= '<option value="'.$max_value.'">'.$max_value.'+</option>';
        $html .= '</select>';
    }
    elseif($show == 'minmax_selectbox_minus')
	{
        $i = $min_value;
        
		$html .= '<select name="sf'.$widget_id.'_tmax_'.$field_data['table_column'].'" id="sf'.$widget_id.'_tmax_'.$field_data['table_column'].'">';
		$html .= '<option value="-1" '.($current_max_value == $i ? 'selected="selected"' : '').'>'.__($field['name'], WPL_TEXTDOMAIN).'</option>';
		
        $selected_printed = false;
        if($current_max_value == $i) $selected_printed = true;
        
		while($i < $max_value)
		{
            if($i == '0')
			{
				$i += $division;
				continue;
			}
            
			$html .= '<option value="'.$i.'" '.(($current_max_value == $i and !$selected_printed) ? 'selected="selected"' : '').'>-'.$i.'</option>';
			$i += $division;
		}
		
		$html .= '<option value="'.$max_value.'">-'.$max_value.'</option>';
        $html .= '</select>';
    }
    elseif($show == 'minmax_selectbox_range')
	{
        $i = $min_value;
        
        $current_between_value = stripslashes(wpl_request::getVar('sf_between_'.$field_data['table_column'], ''));
        
		$html .= '<select name="sf'.$widget_id.'_between_'.$field_data['table_column'].'" id="sf'.$widget_id.'_between_'.$field_data['table_column'].'">';
		$html .= '<option value="-1">'.__($field['name'], WPL_TEXTDOMAIN).'</option>';
        
		while($i < $max_value)
		{
            $range_value = $i.':'.($i+$division);
			$html .= '<option value="'.$range_value.'" '.($current_between_value == $range_value ? 'selected="selected"' : '').'>'.number_format($i, 0, '.', ',').' - '.number_format(($i+$division), 0, '.', ',').'</option>';
			$i += $division;
		}
        
		$html .= '<option value="'.$max_value.'" '.($current_between_value == $max_value ? 'selected="selected"' : '').'>'.number_format($max_value, 0, '.', ',').'+</option>';
        $html .= '</select>';
	}
	
	$done_this = true;
}
elseif($type == 'property_types' and !$done_this)
{
	$property_types = wpl_global::get_property_types();

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
			$multiple = true;
			$label = true;
		break;
	}
	
	/** current value **/
	$current_value = stripslashes(wpl_request::getVar('sf_select_'.$field_data['table_column'], 0));
	
	if($label) $html .= '<label>'.__($field['name'], WPL_TEXTDOMAIN).'</label>';
	
	if($show == 'select')
	{
		$html .= '<select name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" class="wpl_search_widget_field_'.$field_data['table_column'].' wpl_search_widget_field_'.$field['id'].'" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'" onchange="wpl_property_type_changed'.$widget_id.'(this.value);">';
		if($any) $html .= '<option value="-1">'.__($field['name'], WPL_TEXTDOMAIN).'</option>';
		
		foreach($property_types as $property_type)
		{
			$html .= '<option class="wpl_pt_parent wpl_pt_parent'.$property_type['parent'].'" value="'.$property_type['id'].'" '.($current_value == $property_type['id'] ? 'selected="selected"' : '').'>'.__($property_type['name'], WPL_TEXTDOMAIN).'</option>';
		}
		
		$html .= '</select>';
	}
	elseif($show == 'multiple')
    {
		/** current value **/
		$current_values = explode(',', stripslashes(wpl_request::getVar('sf_multiple_'.$field_data['table_column'])));
		
        $html .= '<div class="wpl_searchwid_'.$field_data['table_column'].'_multiselect_container">
		<select data-placeholder="'.__($field['name'], WPL_TEXTDOMAIN).'" class="wpl_search_widget_field_'.$field_data['table_column'].' wpl_searchmod_'.$field_data['table_column'].'_multiselect" id="sf'.$widget_id.'_multiple_'.$field_data['table_column'].'" name="sf'.$widget_id.'_multiple_'.$field_data['table_column'].'" multiple="multiple">';
		
        foreach($property_types as $property_type)
		{
            $html .= '<option class="wpl_pt_parent wpl_pt_parent'.$property_type['parent'].'" value="'.$property_type['id'].'" '.(in_array($property_type['id'], $current_values) ? 'selected="selected"' : '').'>'.__($property_type['name'], WPL_TEXTDOMAIN).'</option>';
        }
		
        $html .= '</select></div>';
    }
	elseif($show == 'checkboxes')
	{
		/** current value **/
		$current_values = explode(',', stripslashes(wpl_request::getVar('sf_multiple_'.$field_data['table_column'])));
		
		$i = 0;
		foreach($property_types as $property_type)
		{
			$i++;
			$html .= '<input '.(in_array($property_type['id'], $current_values) ? 'checked="checked"' : '').' name="chk'.$widget_id.'_select_'.$field_data['table_column'].'" type="checkbox" value="'.$property_type['id'].'" id="chk'.$widget_id.'_select_'.$field_data['table_column'].'_'.$i.'" onclick="wpl_add_to_multiple'.$widget_id.'(this.value, this.checked, \''.$field_data['table_column'].'\');"><label for="chk'.$widget_id.'_select_'.$field_data['table_column'].'_'.$i.'">'.__($property_type['name'], WPL_TEXTDOMAIN).'</label>';
		}
		
		$html .= '<input value="'.implode(',', $current_values).'" type="hidden" id="sf'.$widget_id.'_multiple_'.$field_data['table_column'].'" name="sf'.$widget_id.'_multiple_'.$field_data['table_column'].'" />';
	}
	elseif($show == 'radios')
	{
		$i = 0;
		if($any) $html .= '<input name="rdo'.$widget_id.'_select_'.$field_data['table_column'].'" type="radio" value="-1" id="rdo'.$widget_id.'_select_'.$field_data['table_column'].'_'.$i.'" onclick="wpl_select_radio'.$widget_id.'(this.value, this.checked, \''.$field_data['table_column'].'\');"><label for="rdo'.$widget_id.'_select_'.$field_data['table_column'].'_'.$i.'">'.__('Any', WPL_TEXTDOMAIN).'</label>';
		
		foreach($property_types as $property_type)
		{
			$i++;
			$html .= '<input '.($current_value == $property_type['id'] ? 'checked="checked"' : '').' name="rdo'.$widget_id.'_select_'.$field_data['table_column'].'" type="radio" value="'.$property_type['id'].'" id="rdo'.$widget_id.'_select_'.$field_data['table_column'].'_'.$i.'" onclick="wpl_select_radio'.$widget_id.'(this.value, this.checked, \''.$field_data['table_column'].'\');"><label for="rdo'.$widget_id.'_select_'.$field_data['table_column'].'_'.$i.'">'.__($property_type['name'], WPL_TEXTDOMAIN).'</label>';
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
		$html .= '<select name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" class="wpl_search_widget_field_'.$field_data['table_column'].' wpl_search_widget_field_'.$field['id'].'" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'" onchange="wpl_property_type_changed'.$widget_id.'(this.value);">';
		if($any) $html .= '<option value="-1">'.__('Any', WPL_TEXTDOMAIN).'</option>';
        
		foreach($property_types as $property_type)
		{
			if(in_array($property_type['id'], $field['extoption'])) $html .= '<option class="wpl_pt_parent wpl_pt_parent'.$property_type['parent'].'" value="'.$property_type['id'].'" '.($current_value == $property_type['id'] ? 'selected="selected"' : '').'>'.__($property_type['name'], WPL_TEXTDOMAIN).'</option>';
		}
		
		$html .= '</select>';
	}
	
	$done_this = true;
}
elseif($type == 'select' and !$done_this)
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
	$current_value = stripslashes(wpl_request::getVar('sf_select_'.$field_data['table_column'], -1));
	
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
		$current_values = explode(',', stripslashes(wpl_request::getVar('sf_multiple_'.$field_data['table_column'])));
        if(trim($current_values[0]) == '') $current_values = array();
        
        $html .= '<div class="wpl_searchwid_'.$field_data['table_column'].'_multiselect_container">
		<select data-placeholder="'.__($field['name'], WPL_TEXTDOMAIN).'" class="wpl_searchmod_'.$field_data['table_column'].'_multiselect" id="sf'.$widget_id.'_multiple_'.$field_data['table_column'].'" name="sf'.$widget_id.'_multiple_'.$field_data['table_column'].'" multiple="multiple">';
		
        foreach($options['params'] as $option)
		{
            $html .= '<option value="'.$option['key'].'" '.(in_array($option['key'], $current_values) ? 'selected="selected"' : '').'>'.__($option['value'], WPL_TEXTDOMAIN).'</option>';
        }
		
        $html .= '</select></div>';
    }
	elseif($show == 'checkboxes')
	{
		/** current value **/
		$current_values = explode(',', stripslashes(wpl_request::getVar('sf_multiple_'.$field_data['table_column'])));
		
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
elseif(in_array($type, array('user_type', 'user_membership')) and !$done_this)
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
	}
	
	/** current value **/
    $raw_options = $type == 'user_type' ? wpl_users::get_user_types(1) : wpl_users::get_wpl_memberships();
    
    $options = array();
    foreach($raw_options as $raw_option) $options[$raw_option->id] = array('key'=>$raw_option->id, 'value'=>(isset($raw_option->membership_name) ? $raw_option->membership_name : $raw_option->name));
    
	$current_value = stripslashes(wpl_request::getVar('sf_select_'.$field_data['table_column'], ''));
	
	if($label) $html .= '<label>'.__($field['name'], WPL_TEXTDOMAIN).'</label>';

	if($show == 'select')
	{
		$html .= '<select name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" class="wpl_search_widget_field_'.$field['id'].'" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'">';
		if($any) $html .= '<option value="">'.__($field['name'], WPL_TEXTDOMAIN).'</option>';
		
		foreach($options as $option) $html .= '<option value="'.$option['key'].'" '.($current_value == $option['key'] ? 'selected="selected"' : '').'>'.__($option['value'], WPL_TEXTDOMAIN).'</option>';
		
		$html .= '</select>';
	}
	elseif($show == 'multiple')
    {
		/** current value **/
		$current_values = explode(',', stripslashes(wpl_request::getVar('sf_multiple_'.$field_data['table_column'])));
	
        $html .= '<div class="wpl_searchwid_'.$field_data['table_column'].'_multiselect_container">
		<select data-placeholder="'.__($field['name'], WPL_TEXTDOMAIN).'" class="wpl_searchmod_'.$field_data['table_column'].'_multiselect" id="sf'.$widget_id.'_multiple_'.$field_data['table_column'].'" name="sf'.$widget_id.'_multiple_'.$field_data['table_column'].'" multiple="multiple">';
		
        foreach($options as $option) $html .= '<option value="'.$option['key'].'" '.(in_array($option['key'], $current_values) ? 'selected="selected"' : '').'>'.__($option['value'], WPL_TEXTDOMAIN).'</option>';
		
        $html .= '</select></div>';
    }
	elseif($show == 'checkboxes')
	{
		/** current value **/
		$current_values = explode(',', stripslashes(wpl_request::getVar('sf_multiple_'.$field_data['table_column'])));
		
		$i = 0;
		foreach($options as $option)
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

		foreach($options as $option)
		{
			$i++;
           	$html .= '<input '.($current_value == $option['key'] ? 'checked="checked"' : '').' name="rdo'.$widget_id.'_select_'.$field_data['table_column'].'" type="radio" value="'.$option['key'].'" id="rdo'.$widget_id.'_select_'.$field_data['table_column'].'_'.$i.'" onclick="wpl_select_radio'.$widget_id.'(this.value, this.checked, \''.$field_data['table_column'].'\');"><label for="rdo'.$widget_id.'_select_'.$field_data['table_column'].'_'.$i.'">'.__($option['value'], WPL_TEXTDOMAIN).'</label>';
		}
		
		$html .= '<input value="'.$current_value.'" type="hidden" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'" name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" />';
	}

	$done_this = true;
}
elseif($type == 'textarea' and !$done_this)
{
	/** current value **/
	$current_value = stripslashes(wpl_request::getVar('sf_text_'.$field_data['table_column'], ''));
	
	$html .= '<label for="sf'.$widget_id.'_text_'.$field_data['table_column'].'">'.__($field['name'], WPL_TEXTDOMAIN).'</label>
				<textarea name="sf'.$widget_id.'_text_'.$field_data['table_column'].'" id="sf'.$widget_id.'_text_'.$field_data['table_column'].'">'.$current_value.'</textarea>';

	$done_this = true;
}
elseif(($type == 'area' or $type == 'price' or $type == 'volume' or $type == 'length') and !$done_this)
{
	$default_min_value = 0;
	
	if($type == 'price')
	{
		$unit_type = 4;
		$default_max_value = 1000000;
		$default_division_value = 1000;
	}
    elseif($type == 'volume')
	{
		$unit_type = 3;
		$default_max_value = 1000;
		$default_division_value = 50;
	}
    elseif($type == 'area')
	{
		$unit_type = 2;
		$default_max_value = 10000;
		$default_division_value = 100;
	}
    elseif($type == 'length')
	{
		$unit_type = 1;
		$default_max_value = 100;
		$default_division_value = 10;
	}
	
	/** get units **/
	$units = wpl_units::get_units($unit_type);
	
	/** MIN/MAX extoptions **/
	$extoptions = explode(',', $field['extoption']);
	
    $min_value = (isset($extoptions[0]) and trim($extoptions[0]) != '') ? $extoptions[0] : 0;
	$max_value = isset($extoptions[1]) ? $extoptions[1] : $default_max_value;
	$division = isset($extoptions[2]) ? $extoptions[2] : $default_division_value;
	$separator = isset($extoptions[3]) ? $extoptions[3] : ',';
	
	switch($field['type'])
	{
		case 'minmax':
			$show = 'minmax';
			$input_type = 'text';
		break;
		
		case 'minmax_slider':
			$show = 'minmax_slider';
			$input_type = 'hidden';
		break;
		
		case 'minmax_selectbox':
			$show = 'minmax_selectbox';
			$any = true;
		break;
		
		case 'minmax_selectbox_plus':
			$show = 'minmax_selectbox_plus';
		break;
    
        case 'minmax_selectbox_minus':
			$show = 'minmax_selectbox_minus';
		break;
    
        case 'minmax_selectbox_range':
			$show = 'minmax_selectbox_range';
		break;
	}
	
	$html .= '<label>'.__($field['name'], WPL_TEXTDOMAIN).'</label>';

	/** current values **/
    $current_min_value = max(stripslashes(wpl_request::getVar('sf_min_'.$field_data['table_column'], $min_value)), $min_value);
	$current_max_value = min(stripslashes(wpl_request::getVar('sf_max_'.$field_data['table_column'], $max_value)), $max_value);
	$current_unit = stripslashes(wpl_request::getVar('sf_unit_'.$field_data['table_column'], $units[0]['id']));
	
    if(count($units) > 1)
    {
        $html .= '<select class="wpl_search_widget_field_unit" name="sf'.$widget_id.'_unit_'.$field_data['table_column'].'" id="sf'.$widget_id.'_unit_'.$field_data['table_column'].'">';
        foreach($units as $unit) $html .= '<option value="'.$unit['id'].'" '.($current_unit == $unit['id'] ? 'selected="selected"' : '').'>'.$unit['name'].'</option>';
        $html .= '</select>';
    }
    elseif(count($units) == 1)
    {
        $html .= '<input type="hidden" class="wpl_search_widget_field_unit" name="sf'.$widget_id.'_unit_'.$field_data['table_column'].'" id="sf'.$widget_id.'_unit_'.$field_data['table_column'].'" value="'.$units[0]['id'].'" />';
    }
	
	if($show == 'minmax')
	{
		if($input_type == 'text') $html .= '<label id="wpl_search_widget_from_label'.$widget_id.'" class="wpl_search_widget_from_label" for="sf'.$widget_id.'_min_'.$field_data['table_column'].'">'.__('Min', WPL_TEXTDOMAIN).'</label>';
		$html .= '<input name="sf'.$widget_id.'_min_'.$field_data['table_column'].'" type="'.$input_type.'" id="sf'.$widget_id.'_min_'.$field_data['table_column'].'" value="'.$current_min_value.'" />';
        
		if($input_type == 'text') $html .= '<label id="wpl_search_widget_to_label'.$widget_id.'" class="wpl_search_widget_to_label" for="sf'.$widget_id.'_max_'.$field_data['table_column'].'">'.__('Max', WPL_TEXTDOMAIN).'</label>';
		$html .= '<input name="sf'.$widget_id.'_max_'.$field_data['table_column'].'" type="'.$input_type.'" id="sf'.$widget_id.'_max_'.$field_data['table_column'].'" value="'.$current_max_value.'" />';
	}
	elseif($show == 'minmax_slider')
	{
		$html .= '<script type="text/javascript">
                wplj(function()
				{
					wplj("#slider'.$widget_id.'_range_'.$field_data['table_column'].'" ).slider(
					{
						step: '.$division.',
						range: true,
						min: '.(is_numeric($min_value) ? $min_value : 0).',
						max: '.$max_value.',
                        field_id: '.$field['id'].',
						values: ['.$current_min_value.', '.$current_max_value.'],
						slide: function(event, ui)
						{
							v1 = wpl_th_sep'.$widget_id.'(ui.values[0]);
							v2 = wpl_th_sep'.$widget_id.'(ui.values[1]);
							wplj("#slider'.$widget_id.'_showvalue_'.$field_data['table_column'].'" ).html(v1+" - "+ v2);
						},
						stop: function(event, ui)
						{
							wplj("#sf'.$widget_id.'_min_'.$field_data['table_column'].'").val(ui.values[0]);
							wplj("#sf'.$widget_id.'_max_'.$field_data['table_column'].'").val(ui.values[1]);
							'.((isset($this->ajax) and $this->ajax == 2) ? 'wpl_do_search_'.$widget_id.'();' : '').'
						}
					});
				});
				</script>';
		
		$html .= '<span class="wpl_search_slider_container">
				<input type="hidden" value="'.$current_min_value.'" name="sf'.$widget_id.'_min_'.$field_data['table_column'].'" id="sf'.$widget_id.'_min_'.$field_data['table_column'].'" /><input type="hidden" value="'.$current_max_value.'" name="sf'.$widget_id.'_max_'.$field_data['table_column'].'" id="sf'.$widget_id.'_max_'.$field_data['table_column'].'" />
				<span class="wpl_slider_show_value" id="slider'.$widget_id.'_showvalue_'.$field_data['table_column'].'">'.number_format((double) $current_min_value, 0, '', $separator).' - '.number_format((double) $current_max_value, 0, '', $separator).'</span>
				<span class="wpl_span_block" style="width: 92%; height: 20px;"><span class="wpl_span_block" id="slider'.$widget_id.'_range_'.$field_data['table_column'].'" ></span></span>
				</span>';
	}
	elseif($show == 'minmax_selectbox')
	{
        $html .= '
        <script type="text/javascript">
        wplj(function()
        {
            wplj("#sf'.$widget_id.'_min_'.$field_data['table_column'].'" ).change(function()
            {
                var min_value = wplj("#sf'.$widget_id.'_min_'.$field_data['table_column'].'" ).val();
                wplj("#sf'.$widget_id.'_max_'.$field_data['table_column'].' option").filter(
                    function () {
                        if(parseInt(this.value) < parseInt(min_value)) wplj(this).hide();
                    }
                );
                try {wplj("#sf'.$widget_id.'_max_'.$field_data['table_column'].'").trigger("chosen:updated");} catch(err) {}
            });
        });
        </script>';
        
        $i = $min_value;
    	$html .= '<select name="sf'.$widget_id.'_min_'.$field_data['table_column'].'" id="sf'.$widget_id.'_min_'.$field_data['table_column'].'">';
		if($any) $html .= '<option value="0" '.($current_min_value == $i ? 'selected="selected"' : '').'>'.__('Min '.$field_data['name'], WPL_TEXTDOMAIN).'</option>';
		
		while($i < $max_value)
		{
			if($i == '0' and $any)
			{
				$i += $division;
				continue;
			}
			
			$html .= '<option value="'.$i.'" '.(($current_min_value == $i and $i != $default_min_value) ? 'selected="selected"' : '').'>'.number_format($i, 0, '.', ',').'</option>';
			$i += $division;
		}
		
		$html .= '<option value="'.$max_value.'">'.number_format($max_value, 0, '.', ',').'</option>';
        $html .= '</select>';
        
        $html .= '<select name="sf'.$widget_id.'_max_'.$field_data['table_column'].'" id="sf'.$widget_id.'_max_'.$field_data['table_column'].'">';
		if($any) $html .= '<option value="999999999999" '.($current_max_value == $i ? 'selected="selected"' : '').'>'.__('Max '.$field_data['name'], WPL_TEXTDOMAIN).'</option>';
		
		$i = $min_value;
		
		while($i < $max_value)
		{
            if($i == '0' and $any)
			{
				$i += $division;
				continue;
			}
            
			$html .= '<option value="'.$i.'" '.(($current_max_value == $i and $i != $default_min_value) ? 'selected="selected"' : '').'>'.number_format($i, 0, '.', ',').'</option>';
			$i += $division;
		}
		
		$html .= '<option value="'.$max_value.'">'.number_format($max_value, 0, '.', ',').'</option>';
        $html .= '</select>';
	}
	elseif($show == 'minmax_selectbox_plus')
	{
        $i = $min_value;
        
		$html .= '<select name="sf'.$widget_id.'_min_'.$field_data['table_column'].'" id="sf'.$widget_id.'_min_'.$field_data['table_column'].'">';
		$html .= '<option value="-1" '.($current_min_value == $i ? 'selected="selected"' : '').'>'.__($field['name'], WPL_TEXTDOMAIN).'</option>';
        
        $selected_printed = false;
        if($current_min_value == $i) $selected_printed = true;
        
		while($i < $max_value)
		{
            if($i == '0')
			{
				$i += $division;
				continue;
			}
            
			$html .= '<option value="'.$i.'" '.(($current_min_value == $i and !$selected_printed) ? 'selected="selected"' : '').'>'.number_format($i, 0, '.', ',').'+</option>';
			$i += $division;
		}
		
		$html .= '<option value="'.$max_value.'" '.($current_min_value == $i ? 'selected="selected"' : '').'>'.number_format($max_value, 0, '.', ',').'+</option>';
        $html .= '</select>';
	}
    elseif($show == 'minmax_selectbox_minus')
	{
        $i = $min_value;
        
		$html .= '<select name="sf'.$widget_id.'_max_'.$field_data['table_column'].'" id="sf'.$widget_id.'_max_'.$field_data['table_column'].'">';
		$html .= '<option value="-1" '.($current_max_value == $i ? 'selected="selected"' : '').'>'.__($field['name'], WPL_TEXTDOMAIN).'</option>';
        
        $selected_printed = false;
        if($current_max_value == $i) $selected_printed = true;
        
		while($i < $max_value)
		{
            if($i == '0')
			{
				$i += $division;
				continue;
			}
            
			$html .= '<option value="'.$i.'" '.(($current_max_value == $i and !$selected_printed) ? 'selected="selected"' : '').'>-'.number_format($i, 0, '.', ',').'</option>';
			$i += $division;
		}
		
		$html .= '<option value="'.$max_value.'" '.($current_max_value == $i ? 'selected="selected"' : '').'>-'.number_format($max_value, 0, '.', ',').'</option>';
        $html .= '</select>';
	}
    elseif($show == 'minmax_selectbox_range')
	{
        $i = $min_value;
        
        $current_between_value = stripslashes(wpl_request::getVar('sf_betweenunit_'.$field_data['table_column'], ''));
        
		$html .= '<select name="sf'.$widget_id.'_betweenunit_'.$field_data['table_column'].'" id="sf'.$widget_id.'_betweenunit_'.$field_data['table_column'].'">';
		$html .= '<option value="-1">'.__($field['name'], WPL_TEXTDOMAIN).'</option>';
        
		while($i < $max_value)
		{
            $range_value = $i.':'.($i+$division);
			$html .= '<option value="'.$range_value.'" '.($current_between_value == $range_value ? 'selected="selected"' : '').'>'.number_format($i, 0, '.', ',').' - '.number_format(($i+$division), 0, '.', ',').'</option>';
			$i += $division;
		}
        
		$html .= '<option value="'.$max_value.'" '.($current_between_value == $max_value ? 'selected="selected"' : '').'>'.number_format($max_value, 0, '.', ',').'+</option>';
        $html .= '</select>';
	}
	
	$done_this = true;
}
elseif($type == 'text' and !$done_this)
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
	$current_value = stripslashes(wpl_request::getVar('sf_'.$query_type.'_'.$field_data['table_column'], ''));

	$html .= '<label for="sf'.$widget_id.'_'.$query_type.'_'.$field_data['table_column'].'">'.__($field['name'], WPL_TEXTDOMAIN).'</label>
				<input name="sf'.$widget_id.'_'.$query_type.'_'.$field_data['table_column'].'" type="text" id="sf'.$widget_id.'_'.$query_type.'_'.$field_data['table_column'].'" value="'.$current_value.'" placeholder="'.__($field['name'], WPL_TEXTDOMAIN).'" />';
	
	$done_this = true;
}
elseif($type == 'textsearch' and !$done_this)
{
	switch($field['type'])
	{
		case 'text':
			$show = 'text';
		break;
		
		case 'textarea':
			$show = 'textarea';
		break;
        
        default:
            $show = 'text';
        break;
	}
	
	/** current value **/
	$current_value = stripslashes(wpl_request::getVar('sf_textsearch_'.$field_data['table_column'], ''));
	
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
elseif($type == 'addon_calendar' and !$done_this)
{
	/** system date format **/
	$date_format_arr = explode(':', wpl_global::get_setting('main_date_format'));
	$jqdate_format = $date_format_arr[1];
	
	$min_value = date("Y-m-d");
	$mindate = explode('-', $min_value);
    $show_icon = 0;
    
	/** current value **/
    $current_checkin_value = stripslashes(wpl_request::getVar('sf_calendarcheckin', ''));
    $current_checkout_value = stripslashes(wpl_request::getVar('sf_calendarcheckout', ''));
    
    /** for opening more details **/
    $current_value = $current_checkin_value;

    $html .= '<div class="wpl_search_widget_calendar_search_container">';
    $html .= '<input type="text" name="sf'.$widget_id.'_calendarcheckin" id="sf'.$widget_id.'_calendarcheckin" value="'.($current_checkin_value != '' ? $current_checkin_value : '').'" placeholder="'.__('Check In', WPL_TEXTDOMAIN).'" />';
    $html .= '<input type="text" name="sf'.$widget_id.'_calendarcheckout" id="sf'.$widget_id.'_calendarcheckout" value="'.($current_checkout_value != '' ? $current_checkout_value : '').'" placeholder="'.__('Check Out', WPL_TEXTDOMAIN).'" />';
    $html .= '</div>';
    
    $html .= '
    <script type="text/javascript">
    wplj(document).ready(function()
    {
        wplj("#sf'.$widget_id.'_calendarcheckin, #sf'.$widget_id.'_calendarcheckout").datepicker(
        {
            dayNamesMin: ["'.addslashes(__('SU', WPL_TEXTDOMAIN)).'", "'.addslashes(__('MO', WPL_TEXTDOMAIN)).'", "'.addslashes(__('TU', WPL_TEXTDOMAIN)).'", "'.addslashes(__('WE', WPL_TEXTDOMAIN)).'", "'.addslashes(__('TH', WPL_TEXTDOMAIN)).'", "'.addslashes(__('FR', WPL_TEXTDOMAIN)).'", "'.addslashes(__('SA', WPL_TEXTDOMAIN)).'"],
            dayNames: 	 ["'.addslashes(__('Sunday', WPL_TEXTDOMAIN)).'", "'.addslashes(__('Monday', WPL_TEXTDOMAIN)).'", "'.addslashes(__('Tuesday', WPL_TEXTDOMAIN)).'", "'.addslashes(__('Wednesday', WPL_TEXTDOMAIN)).'", "'.addslashes(__('Thursday', WPL_TEXTDOMAIN)).'", "'.addslashes(__('Friday', WPL_TEXTDOMAIN)).'", "'.addslashes(__('Saturday', WPL_TEXTDOMAIN)).'"],
            monthNames:  ["'.addslashes(__('January', WPL_TEXTDOMAIN)).'", "'.addslashes(__('February', WPL_TEXTDOMAIN)).'", "'.addslashes(__('March', WPL_TEXTDOMAIN)).'", "'.addslashes(__('April', WPL_TEXTDOMAIN)).'", "'.addslashes(__('May', WPL_TEXTDOMAIN)).'", "'.addslashes(__('June', WPL_TEXTDOMAIN)).'", "'.addslashes(__('July', WPL_TEXTDOMAIN)).'", "'.addslashes(__('August', WPL_TEXTDOMAIN)).'", "'.addslashes(__('September', WPL_TEXTDOMAIN)).'", "'.addslashes(__('October', WPL_TEXTDOMAIN)).'", "'.addslashes(__('November', WPL_TEXTDOMAIN)).'", "'.addslashes(__('December', WPL_TEXTDOMAIN)).'"],
            dateFormat: "'.addslashes($jqdate_format).'",
            gotoCurrent: true,
            minDate: new Date('.$mindate[0].', '.intval($mindate[1]).'-1, '.$mindate[2].'),
            changeYear: true,
            '.($show_icon == '1' ? 'showOn: "both", buttonImage: "'.wpl_global::get_wpl_asset_url('img/system/calendar2.png').'",' : '').'
            buttonImageOnly: true
        });
    });
    </script>';
	
	
	$done_this = true;
}
elseif($type == 'ptcategory' and !$done_this)
{
	switch($field['type'])
	{
		case 'select':
			$show = 'select';
			$any = true;
			$label = true;
		break;
	}
	
	/** current value **/
	$current_value = stripslashes(wpl_request::getVar('sf_ptcategory', -1));
	$categories = wpl_property_types::get_property_type_categories();
    
	if($label) $html .= '<label>'.__($field['name'], WPL_TEXTDOMAIN).'</label>';

	if($show == 'select')
	{
		$html .= '<select name="sf'.$widget_id.'_ptcategory" class="wpl_search_widget_field_'.$field['id'].'" id="sf'.$widget_id.'__ptcategory">';
		if($any) $html .= '<option value="-1">'.__($field['name'], WPL_TEXTDOMAIN).'</option>';
		
		foreach($categories as $category)
			$html .= '<option data-id="'.$category['id'].'" value="'.$category['name'].'" '.(strtolower($current_value) == strtolower($category['name']) ? 'selected="selected"' : '').'>'.__($category['name'], WPL_TEXTDOMAIN).'</option>';
		
		$html .= '</select>';
        
        $html .= '<script type="text/javascript">
        wplj(function()
        {
            wplj("#sf'.$widget_id.'__ptcategory").on("change", function()
            {
                var category_id = wplj("#sf'.$widget_id.'__ptcategory option:selected").data("id");
                if(category_id)
                {
                    wplj("#wpl_search_form_'.$widget_id.' .wpl_search_widget_field_property_type option").hide();
                    wplj("#wpl_search_form_'.$widget_id.' .wpl_search_widget_field_property_type option.wpl_pt_parent"+category_id).show();
                }
                else
                {
                    wplj("#wpl_search_form_'.$widget_id.' .wpl_search_widget_field_property_type option").show();
                }
                
                wplj("#wpl_search_form_'.$widget_id.' .wpl_search_widget_field_property_type").trigger("chosen:updated");
            });
            
            wplj("#sf'.$widget_id.'__ptcategory").trigger("change");
        });
        </script>';
	}
}
elseif($type == 'separator' and !$done_this)
{
	$html .= '<label id="wpl'.$widget_id.'_search_widget_separator_'.$field['id'].'">'.__($field['name'], WPL_TEXTDOMAIN).'</label>';

	$done_this = true;
}