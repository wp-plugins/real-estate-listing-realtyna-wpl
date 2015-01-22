<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if(in_array($mandatory, array(1, 2)))
{
	$js_string .=
	'
	val1 = Number(wplj.trim(wpl_de_thousand_sep(wplj("#wpl_c_'.$field->id.'").val())));
	val2 = Number(wplj.trim(wpl_de_thousand_sep(wplj("#wpl_c_'.$field->id.'_max").val())));
	
	if((val1 > val2 || val2 == 0) && wplj("#wpl_listing_field_container'.$field->id.'").css("display") != "none")
	{
		wpl_alert("'.__('Enter a valid', WPL_TEXTDOMAIN).' '.__($label, WPL_TEXTDOMAIN).'!");
		return false;
	}
	';
}