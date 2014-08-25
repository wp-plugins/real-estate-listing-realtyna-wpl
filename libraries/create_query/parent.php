<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($format == 'parent' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
        /** converts listing id to property id **/
        if($value) $value = wpl_property::pid($value);
        
		$query .= " AND `parent` = '".$value."'";
	}
	
	$done_this = true;
}