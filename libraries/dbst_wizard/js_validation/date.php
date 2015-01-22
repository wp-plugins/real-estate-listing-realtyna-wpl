<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if(in_array($mandatory, array(1, 2)))
{
	$js_string .=
	'
    if(wplj.trim(wplj("#wpl_c_'.$field->id.'").val()) == "" && wplj("#wpl_listing_field_container'.$field->id.'").css("display") != "none")
    {
        wpl_alert("'.sprintf(__('Enter a valid %s!', WPL_TEXTDOMAIN), __($label, WPL_TEXTDOMAIN)).'");
        return false;
    }
	';
}