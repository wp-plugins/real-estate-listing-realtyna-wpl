<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'date' and !$done_this)
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
		
    	$html .= '<div class="wpl_search_widget_from_container"><label for="sf'.$widget_id.'_datemin_'.$field_data['table_column'].'">'.__('FROM', WPL_TEXTDOMAIN).'</label><input type="text" name="sf'.$widget_id.'_datemin_'.$field_data['table_column'].'" id="sf'.$widget_id.'_datemin_'.$field_data['table_column'].'" value="'.($current_min_value != '' ? $current_min_value : '').'" /></div>';
    	$html .= '<div class="wpl_search_widget_to_container"><label for="sf'.$widget_id.'_datemax_'.$field_data['table_column'].'">'.__('TO', WPL_TEXTDOMAIN).'</label><input type="text" name="sf'.$widget_id.'_datemax_'.$field_data['table_column'].'" id="sf'.$widget_id.'_datemax_'.$field_data['table_column'].'" value="'.($current_max_value != '' ? $current_max_value : '').'" /></div>';
		
		$html .= '
		<script type="text/javascript">
		wplj(document).ready(function()
		{
			wplj("#sf'.$widget_id.'_datemax_'.$field_data['table_column'].'").datepicker(
			{ 
				dayNamesMin: ["'.__('SU', WPL_TEXTDOMAIN).'", "'.__('MO', WPL_TEXTDOMAIN).'", "'.__('TU', WPL_TEXTDOMAIN).'", "'.__('WE', WPL_TEXTDOMAIN).'", "'.__('TH', WPL_TEXTDOMAIN).'", "'.__('FR', WPL_TEXTDOMAIN).'", "'.__('SA', WPL_TEXTDOMAIN).'"],
				dayNames: 	 ["'.__('Sunday', WPL_TEXTDOMAIN).'", "'.__('Monday', WPL_TEXTDOMAIN).'", "'.__('Tuesday', WPL_TEXTDOMAIN).'", "'.__('Wednesday', WPL_TEXTDOMAIN).'", "'.__('Thursday', WPL_TEXTDOMAIN).'", "'.__('Friday', WPL_TEXTDOMAIN).'", "'.__('Saturday', WPL_TEXTDOMAIN).'"],
				monthNames:  ["'.__('January', WPL_TEXTDOMAIN).'", "'.__('February', WPL_TEXTDOMAIN).'", "'.__('March', WPL_TEXTDOMAIN).'", "'.__('April', WPL_TEXTDOMAIN).'", "'.__('May', WPL_TEXTDOMAIN).'", "'.__('June', WPL_TEXTDOMAIN).'", "'.__('July', WPL_TEXTDOMAIN).'", "'.__('August', WPL_TEXTDOMAIN).'", "'.__('September', WPL_TEXTDOMAIN).'", "'.__('October', WPL_TEXTDOMAIN).'", "'.__('November', WPL_TEXTDOMAIN).'", "'.__('December', WPL_TEXTDOMAIN).'"],
				dateFormat: "'.$jqdate_format.'",
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
				dayNamesMin: ["'.__('SU', WPL_TEXTDOMAIN).'", "'.__('MO', WPL_TEXTDOMAIN).'", "'.__('TU', WPL_TEXTDOMAIN).'", "'.__('WE', WPL_TEXTDOMAIN).'", "'.__('TH', WPL_TEXTDOMAIN).'", "'.__('FR', WPL_TEXTDOMAIN).'", "'.__('SA', WPL_TEXTDOMAIN).'"],
				dayNames: 	 ["'.__('Sunday', WPL_TEXTDOMAIN).'", "'.__('Monday', WPL_TEXTDOMAIN).'", "'.__('Tuesday', WPL_TEXTDOMAIN).'", "'.__('Wednesday', WPL_TEXTDOMAIN).'", "'.__('Thursday', WPL_TEXTDOMAIN).'", "'.__('Friday', WPL_TEXTDOMAIN).'", "'.__('Saturday', WPL_TEXTDOMAIN).'"],
				monthNames:  ["'.__('January', WPL_TEXTDOMAIN).'", "'.__('February', WPL_TEXTDOMAIN).'", "'.__('March', WPL_TEXTDOMAIN).'", "'.__('April', WPL_TEXTDOMAIN).'", "'.__('May', WPL_TEXTDOMAIN).'", "'.__('June', WPL_TEXTDOMAIN).'", "'.__('July', WPL_TEXTDOMAIN).'", "'.__('August', WPL_TEXTDOMAIN).'", "'.__('September', WPL_TEXTDOMAIN).'", "'.__('October', WPL_TEXTDOMAIN).'", "'.__('November', WPL_TEXTDOMAIN).'", "'.__('December', WPL_TEXTDOMAIN).'"],
				dateFormat: "'.$jqdate_format.'",
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