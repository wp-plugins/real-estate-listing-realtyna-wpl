<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($format == 'tmin' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
		$min = $value;
		$max = isset($vars['sf_tmax_'.$table_column]) ? $vars['sf_tmax_'.$table_column] : 999999999999;
		
		$query .= " AND `".$table_column ."` >= '".$min."' AND `".$table_column ."` <= '".$max."'";
	}
	
	$done_this = true;
}