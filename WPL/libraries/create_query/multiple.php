<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($format == 'multiple' and !$done_this)
{
	if(!($value == '' or $value == '-1' or $value == ','))
	{
		$value = rtrim($value, ',');
		if($value != '') $query .= " AND `".$table_column ."` IN (".$value.")";
	}
	
	$done_this = true;
}