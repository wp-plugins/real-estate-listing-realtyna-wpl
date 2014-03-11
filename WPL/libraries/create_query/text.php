<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($format == 'text' and !$done_this)
{
	if(trim($value) != '')
	{
		$query .= " AND `".$table_column ."` LIKE '%".$value."%'";
	}
	
	$done_this = true;
}