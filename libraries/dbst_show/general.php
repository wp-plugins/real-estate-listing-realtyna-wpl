<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'property_types' and !$done_this) //////////////////////////// property types ////////////////////////////
{
	if(trim($value) != '0' or trim($value) != '-1')
	{
		/** get property type **/
		$property_type = wpl_global::get_property_types($value);
		
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		$return['value'] = __($property_type->name, WPL_TEXTDOMAIN);
	}
	
	$done_this = true;
}
elseif($type == 'listings' and !$done_this) //////////////////////////// listings ////////////////////////////
{
	if(trim($value) != '0' or trim($value) != '-1')
	{
		/** get listing type **/
		$listing_type = wpl_global::get_listings($value);
		
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		$return['value'] = __($listing_type->name, WPL_TEXTDOMAIN);
	}
	
	$done_this = true;
}
elseif($type == 'text' and !$done_this) //////////////////////////// text ////////////////////////////
{
	if(trim($value) != '')
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		
		if($field->id == '51') #Longitude
			$return['value'] = wpl_render::render_longitude($value);
		elseif($field->id == '52') #Latitude
			$return['value'] = wpl_render::render_latitude($value);
		else
			$return['value'] = $value;
	}
	
	$done_this = true;
}
elseif($type == 'textarea' and !$done_this) //////////////////////////// textarea ////////////////////////////
{
	if(trim($value) != '')
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		$return['value'] = $value;
	}
	
	$done_this = true;
}
elseif($type == 'select' and !$done_this) //////////////////////////// select ////////////////////////////
{
	if(trim($value) != '0')
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		
		foreach($options['params'] as $field_option)
		{
			if($value == $field_option['key']) $return['value'] = $field_option['value'];
		}
	}
	
	$done_this = true;
}
elseif($type == 'separator' and !$done_this) //////////////////////////// separator ////////////////////////////
{
	$return['field_id'] = $field->id;
	$return['type'] = $field->type;
	$return['name'] = __($field->name, WPL_TEXTDOMAIN);
	
	$done_this = true;
}
elseif($type == 'email' and !$done_this) //////////////////////////// email ////////////////////////////
{
	if(trim($value) != '') 
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		$return['value'] = $value;
	}
	
	$done_this = true;
}
elseif($type == 'number' and !$done_this) //////////////////////////// number ////////////////////////////
{
	if(trim($value) != '')
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		$return['value'] = $value;
	}
	
	$done_this = true;
}
elseif($type == 'checkbox' and !$done_this) //////////////////////////// checkbox ////////////////////////////
{
	if($value != '0') 
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		$return['value'] = $value;
	}
	
	$done_this = true;
}
elseif(($type == 'volume' or $type == 'area' or $type == 'length') and !$done_this) //////////////////////////// volume, area, length ////////////////////////////
{
	if(trim($value) != '0')
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		$return['value'] = ($value == round($value) ? $value : number_format($value, 2));
		
		/** adding unit **/
		$unit = wpl_units::get_unit($values[$field->table_column.'_unit']);
		if($unit) $return['value'] .= ' '.$unit['name'];
	}
	
	$done_this = true;
}
elseif($type == 'price' and !$done_this)  //////////////////////////// Price ////////////////////////////
{
	$return['field_id'] = $field->id;
	$return['type'] = $field->type;
	$return['name'] = __($field->name, WPL_TEXTDOMAIN);
	$return['value'] = wpl_render::render_price($value, $values[$field->table_column.'_unit']);
	
	$done_this = true;
}
elseif($type == 'url' and !$done_this)  //////////////////////////// URL ////////////////////////////
{
	$return['field_id'] = $field->id;
	$return['type'] = $field->type;
	$return['name'] = __($field->name, WPL_TEXTDOMAIN);
    
    $title = (isset($options['link_title']) and trim($options['link_title']) != '') ? $options['link_title'] : $value;
    $target = (isset($options['link_target']) and trim($options['link_target']) != '') ? $options['link_target'] : '_blank';
    
	$return['value'] = '<a href="'.$value.'" target="'.$target.'">'.$title.'</a>';
	
	$done_this = true;
}