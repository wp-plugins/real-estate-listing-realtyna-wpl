<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($format == 'ptcategory' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
        $category_id = wpl_db::select("SELECT `id` FROM `#__wpl_property_types` WHERE LOWER(name)='".strtolower($value)."' AND `parent`='0'", 'loadResult');
		$query .= " AND `property_type` IN (SELECT `id` FROM `#__wpl_property_types` WHERE `parent`='$category_id')";
	}
	
	$done_this = true;
}
elseif($format == 'ltcategory' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
        $category_id = wpl_db::select("SELECT `id` FROM `#__wpl_listing_types` WHERE LOWER(name)='".strtolower($value)."' AND `parent`='0'", 'loadResult');
		$query .= " AND `listing` IN (SELECT `id` FROM `#__wpl_listing_types` WHERE `parent`='$category_id')";
	}
	
	$done_this = true;
}