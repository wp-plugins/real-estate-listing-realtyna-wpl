<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'gallery' and !$done_this)
{
	/** current value **/
	$current_value = wpl_request::getVar('sf_gallery', -1);
	
	$html .= '<input value="1" '.($current_value == 1 ? 'checked="checked"' : '').' name="sf'.$widget_id.'_gallery" type="checkbox" id="sf'.$widget_id.'_gallery" />
				<label for="sf'.$widget_id.'_gallery">'.__($field['name'], WPL_TEXTDOMAIN).'</label>';

	$done_this = true;
}