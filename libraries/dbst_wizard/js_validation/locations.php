<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($field->type == 'locations')
{
	$js_string .=
	'
	var location_temp = true;
	wplj("#wpl_listing_all_location_container'.$field->id.' select.wpl_location_indicator_selectbox").each(function(ind, elm)
	{
		if((elm.value <= 0) && wplj("#wpl_listing_all_location_container'.$field->id.'").css("display") != "none" && elm.length > 1)
		{
			location_temp = false;
		}
	});
	
	if(!location_temp)
	{
		wpl_alert("'.__('Location data is mandatory', WPL_TEXTDOMAIN).'!");
		return false;
	}
	';
}