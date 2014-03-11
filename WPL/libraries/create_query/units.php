<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($format == 'unit' and !$done_this)
{
	/** importing library **/
	_wpl_import('libraries.units');
	
	if($value != '-1' and trim($value) != '')
	{
		$unit_data = wpl_units::get_unit($value);
		
		$min = wpl_request::getVar('sf_min_'.$table_column);
		$max = wpl_request::getVar('sf_max_'.$table_column);
		
		$si_value_min = $unit_data['tosi'] * $min;
		$si_value_max = $unit_data['tosi'] * $max;
		
		if($si_value_max != 0) $query .= " AND `".$table_column ."_si` <= '".$si_value_max."'";
		if($si_value_min != 0) $query .= " AND `".$table_column ."_si` >= '".$si_value_min."'";
	}
	
	$done_this = true;
}