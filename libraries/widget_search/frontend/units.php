<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if(($type == 'area' or $type == 'price' or $type == 'volume' or $type == 'length') and !$done_this)
{
	/** importing library **/
	_wpl_import('libraries.units');
	
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
	
	$min_value = isset($extoptions[0]) ? $extoptions[0] : $default_min_value;
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
			$input_type = 'hidden';
		break;
	}
	
	$html .= '<label>'.__($field['name'], WPL_TEXTDOMAIN).'</label>';

	/** current values **/
	$current_min_value = wpl_request::getVar('sf_min_'.$field_data['table_column'], $min_value);
	$current_max_value = wpl_request::getVar('sf_max_'.$field_data['table_column'], $max_value);
	$current_unit = wpl_request::getVar('sf_unit_'.$field_data['table_column'], $units[0]['id']);
	
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
		if($input_type == 'text') $html .= '<label id="wpl_search_widget_from_label'.$widget_id.'" class="wpl_search_widget_from_label" for="sf'.$widget_id.'_min_'.$field_data['table_column'].'">'.__('From', WPL_TEXTDOMAIN).'</label>';
		$html .= '<input name="sf'.$widget_id.'_min_'.$field_data['table_column'].'" type="'.$input_type.'" id="sf'.$widget_id.'_min_'.$field_data['table_column'].'" value="'.$current_min_value.'" />';
        
		if($input_type == 'text') $html .= '<label id="wpl_search_widget_to_label'.$widget_id.'" class="wpl_search_widget_to_label" for="sf'.$widget_id.'_max_'.$field_data['table_column'].'">'.__('To', WPL_TEXTDOMAIN).'</label>';
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
							wplj("#sf'.$widget_id.'_min_'.$field_data['table_column'].'").val(ui.values[0]);
							wplj("#sf'.$widget_id.'_max_'.$field_data['table_column'].'").val(ui.values[1]);
							'.((isset($ajax) and $ajax == 2) ? 'wpl_do_search_'.$widget_id.'();' : '').'
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
		$html .= '<select name="sf'.$widget_id.'_min_'.$field_data['table_column'].'" id="sf'.$widget_id.'_min_'.$field_data['table_column'].'">';
		
		$i = $min_value;
		
		$html .= '<option value="-1" '.($current_min_value == $i ? 'selected="selected"' : '').'>'.__($field['name'], WPL_TEXTDOMAIN).'</option>';
		while($i < $max_value)
		{
            if($i == '0')
			{
				$i += $division;
				continue;
			}
            
			$html .= '<option value="'.$i.'" '.(($current_min_value == $i and $i != $default_min_value) ? 'selected="selected"' : '').'>'.number_format($i, 0, '.', ',').'+</option>';
			$i += $division;
		}
		
		$html .= '<option value="'.$max_value.'" '.($current_min_value == $i ? 'selected="selected"' : '').'>'.number_format($max_value, 0, '.', ',').'+</option>';
        $html .= '</select>';
	}
	
	$done_this = true;
}