<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'date' and !$done_this)
{
	$return['field_id'] = $field->id;
	$return['type'] = $field->type;
	$return['name'] = __($field->name, WPL_TEXTDOMAIN);
	$return['value'] = wpl_render::render_date($value);
	
	$done_this = true;
}
