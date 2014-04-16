<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($format == 'gallery' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
		$query .= " AND (`pic_numb`>0)";
	}
	
	$done_this = true;
}