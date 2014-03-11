<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'separator' and !$done_this)
{
	$html .= '<label id="wpl'.$widget_id.'_search_widget_separator_'.$field['id'].'">'.__($field['name'], WPL_TEXTDOMAIN).'</label>';

	$done_this = true;
}