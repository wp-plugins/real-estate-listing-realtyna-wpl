<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($format == 'datemin' and !$done_this)
{
	_wpl_import('libraries.render');
	
	$min = $value;
	$max = isset($vars['sf_datemax_'.$table_column]) ? $vars['sf_datemax_'.$table_column] : '';

	if(trim($min) != '')
	{
		$min = wpl_render::derender_date($min);
		$query .= " AND `".$table_column ."` >= '".$min."'";
	}
	
	if(trim($max) != '')
	{
		$max = wpl_render::derender_date($max);
		$query .= " AND `".$table_column ."` <= '".$max."'";
	}
	
	$done_this = true;
}
