<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'number' and !$done_this)
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
	}
	
	/** MIN/MAX extoptions **/
	$extoptions = isset($field['extoption']) ? explode(',', $field['extoption']) : array();
    
	$min_value = (isset($extoptions[0]) and trim($extoptions[0])) ? $extoptions[0] : 0;
	$max_value = (isset($extoptions[1]) and trim($extoptions[1])) ? $extoptions[1] : 100000;
	$division = (isset($extoptions[2]) and trim($extoptions[2])) ? $extoptions[2] : 1000;
	$separator = (isset($extoptions[3]) and trim($extoptions[3])) ? $extoptions[3] : ',';
	
    $html .= '<label>'.__($field['name'], WPL_TEXTDOMAIN).'</label>';

	/** current values **/
	$current_min_value = wpl_request::getVar('sf_tmin_'.$field_data['table_column'], $min_value);
	$current_max_value = wpl_request::getVar('sf_tmax_'.$field_data['table_column'], $max_value);
	
	if($show == 'text')
	{
		/** current values **/
		$current_value = wpl_request::getVar('sf_text_'.$field_data['table_column'], '');
		
    	$html .= '<input name="sf'.$widget_id.'_text_'.$field_data['table_column'].'" type="text" id="sf'.$widget_id.'_text_'.$field_data['table_column'].'" value="'.$current_value.'" />';
	}
	elseif($show == 'exacttext')
	{
		/** current values **/
		$current_value = wpl_request::getVar('sf_select_'.$field_data['table_column'], '');
		
    	$html .= '<input name="sf'.$widget_id.'_select_'.$field_data['table_column'].'" type="text" id="sf'.$widget_id.'_select_'.$field_data['table_column'].'" value="'.$current_value.'" />';
	}
    elseif($show == 'minmax')
	{	
		$html .= '<label for="sf'.$widget_id.'_tmin_'.$field_data['table_column'].'">'.__('From', WPL_TEXTDOMAIN).'</label>';
		$html .= '<input name="sf'.$widget_id.'_tmin_'.$field_data['table_column'].'" type="text" id="sf'.$widget_id.'_tmin_'.$field_data['table_column'].'" value="'.$current_min_value.'" />';
        
		$html .= '<label for="sf'.$widget_id.'_tmax_'.$field_data['table_column'].'">'.__('To', WPL_TEXTDOMAIN).'</label>';
		$html .= '<input name="sf'.$widget_id.'_tmax_'.$field_data['table_column'].'" type="text" id="sf'.$widget_id.'_tmax_'.$field_data['table_column'].'" value="'.$current_max_value.'" />';
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
						min: '.$min_value.',
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
							wplj("#sf'.$widget_id.'_tmin_'.$field_data['table_column'].'").val(ui.values[0]);
							wplj("#sf'.$widget_id.'_tmax_'.$field_data['table_column'].'").val(ui.values[1]);
							'.((isset($ajax) and $ajax == 2) ? 'wpl_do_search_'.$widget_id.'();' : '').'
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
		$html .= '<option value="0" '.($current_min_value == $i ? 'selected="selected"' : '').'>'.__('From', WPL_TEXTDOMAIN).'</option>';
        
		while($i < $max_value)
		{
			if($i == '0')
			{
				$i += $division;
				continue;
			}
			
			$html .= '<option value="'.$i.'">'.$i.'</option>';
			$i += $division;
		}
		
		$html .= '<option value="'.$max_value.'">'.$max_value.'</option>';
        $html .= '</select>';
        
        $html .= '<select name="sf'.$widget_id.'_tmax_'.$field_data['table_column'].'" id="sf'.$widget_id.'_tmax_'.$field_data['table_column'].'">';
        
        $i = $min_value;
		$html .= '<option value="999999999999" '.($current_max_value == $i ? 'selected="selected"' : '').'>'.__("To", WPL_TEXTDOMAIN).'</option>';
		
		while($i < $max_value)
		{
			$html .= '<option value="'.$i.'">'.$i.'</option>';
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
		
		while($i < $max_value)
		{
			$html .= '<option value="'.$i.'">'.$i.'+</option>';
			$i += $division;
		}
		
		$html .= '<option value="'.$max_value.'">'.$max_value.'+</option>';
        $html .= '</select>';
    }
	
	$done_this = true;
}