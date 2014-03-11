<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($format == 'notselect' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
		$query .= " AND `".$table_column ."` != '".$value."'";
	}
	
	$done_this = true;
}