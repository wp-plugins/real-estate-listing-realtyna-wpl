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
        {
            if($field->multilingual and wpl_global::check_multilingual_status())
            {
                $current_language = wpl_global::get_current_language();
                $lang_column = wpl_addon_pro::get_column_lang_name($field->table_column, $current_language, false);
                
                if(isset($values[$lang_column])) $value = $values[$lang_column];
            }
            
            $return['value'] = $value;
        }
        
        if(isset($options['if_zero']) and $options['if_zero'] == 2 and $value == 0) $return['value'] = __($options['call_text'], WPL_TEXTDOMAIN);
        if(isset($options['if_zero']) and !$options['if_zero'] and $value == 0) $return = array();
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
        
        if($field->multilingual and wpl_global::check_multilingual_status())
        {
            $current_language = wpl_global::get_current_language();
            $lang_column = wpl_addon_pro::get_column_lang_name($field->table_column, $current_language, false);

            if(isset($values[$lang_column])) $value = $values[$lang_column];
        }
        
        $value = stripslashes($value);
        if(in_array($field->id, array(308, 1160))) $value = apply_filters('the_content', $value);
        $value = do_shortcode($value);
        
        $return['value'] = $value;
	}
	
	$done_this = true;
}
elseif($type == 'select' and !$done_this) //////////////////////////// select ////////////////////////////
{
	if(trim($value) and trim($value) != '-1')
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		
		foreach($options['params'] as $field_option)
		{
			if($value == $field_option['key']) $return['value'] = __($field_option['value'], WPL_TEXTDOMAIN);
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
        
        if(isset($options['if_zero']) and $options['if_zero'] == 2 and $value == 0) $return['value'] = __($options['call_text'], WPL_TEXTDOMAIN);
        if(isset($options['if_zero']) and !$options['if_zero'] and $value == 0) $return = array();
	}
	
	$done_this = true;
}
elseif($type == 'mmnumber' and !$done_this) //////////////////////////// Min/Max numbers ////////////////////////////
{
    if(trim($value) != '')
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		$return['value'] = $value;
        
        if(trim($values[$field->table_column.'_max'])) $return['value'] .= ' - '. $values[$field->table_column.'_max'];
        
        if(isset($options['if_zero']) and $options['if_zero'] == 2 and $value == 0 and $values[$field->table_column.'_max'] == 0) $return['value'] = __($options['call_text'], WPL_TEXTDOMAIN);
        if(isset($options['if_zero']) and !$options['if_zero'] and $value == 0 and $values[$field->table_column.'_max'] == 0) $return = array();
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
elseif(($type == 'volume' or $type == 'area' or $type == 'length') and !$done_this) //////////////////////////// Volume, Area, Length ////////////////////////////
{
	if(trim($value) != '')
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		$return['value'] = ($value == round($value) ? number_format($value, 0) : number_format($value, 2));
        
		/** adding unit **/
		$unit = wpl_units::get_unit($values[$field->table_column.'_unit']);
		if($unit) $return['value'] .= ' '.$unit['name'];
        
        if(isset($options['if_zero']) and $options['if_zero'] == 2 and $value == 0) $return['value'] = __($options['call_text'], WPL_TEXTDOMAIN);
        if(isset($options['if_zero']) and !$options['if_zero'] and $value == 0) $return = array();
	}
	
	$done_this = true;
}
elseif(($type == 'mmvolume' or $type == 'mmarea' or $type == 'mmlength') and !$done_this) //////////////////////////// Min/Max Volume, Area, Length ////////////////////////////
{
	if(trim($value) != '' or trim($values[$field->table_column.'_max']) != '')
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		$return['value'] = ($value == round($value) ? number_format($value, 0) : number_format($value, 2));
		
        if(trim($values[$field->table_column.'_max'])) $return['value'] .= ' - '.($values[$field->table_column.'_max'] == round($values[$field->table_column.'_max']) ? number_format($values[$field->table_column.'_max'], 0) : number_format($values[$field->table_column.'_max'], 2));
        
		/** adding unit **/
		$unit = wpl_units::get_unit($values[$field->table_column.'_unit']);
		if($unit) $return['value'] .= ' '.$unit['name'];
        
        if(isset($options['if_zero']) and $options['if_zero'] == 2 and $value == 0 and $values[$field->table_column.'_max'] == 0) $return['value'] = __($options['call_text'], WPL_TEXTDOMAIN);
        if(isset($options['if_zero']) and !$options['if_zero'] and $value == 0 and $values[$field->table_column.'_max'] == 0) $return = array();
	}
	
	$done_this = true;
}
elseif($type == 'price' and !$done_this)  //////////////////////////// Price ////////////////////////////
{
	$return['field_id'] = $field->id;
	$return['type'] = $field->type;
	$return['name'] = __($field->name, WPL_TEXTDOMAIN);
    
	$rendered_price = wpl_render::render_price($value, $values[$field->table_column.'_unit']);
    $return['value'] = $rendered_price;
    $return['price_only'] = $rendered_price;
	
    $price_period = array();
    if(isset($values[$field->table_column.'_period'])) $price_period = wpl_property::render_field($values[$field->table_column.'_period'], wpl_flex::get_dbst_id($field->table_column.'_period', $field->kind));
    if(isset($price_period['value'])) $return['value'] .= ' '.$price_period['value'];
    
    if(isset($options['if_zero']) and $options['if_zero'] == 2 and $value == 0) $return['value'] = __($options['call_text'], WPL_TEXTDOMAIN);
    if(isset($options['if_zero']) and !$options['if_zero'] and $value == 0) $return = array();
        
	$done_this = true;
}
elseif($type == 'mmprice' and !$done_this)  //////////////////////////// Min/Max Price ////////////////////////////
{
	$return['field_id'] = $field->id;
	$return['type'] = $field->type;
	$return['name'] = __($field->name, WPL_TEXTDOMAIN);
    
	$rendered_price = wpl_render::render_price($value, $values[$field->table_column.'_unit']);
	
    if(trim($values[$field->table_column.'_max']))
    {
        $rendered_price .= ' - '.wpl_render::render_price($values[$field->table_column.'_max'], $values[$field->table_column.'_unit']);
    }
    
    $return['value'] = $rendered_price;
    $return['price_only'] = $rendered_price;
    
    $price_period = wpl_property::render_field($values['price_period'], wpl_flex::get_dbst_id('price_period', $field->kind));
    if(isset($price_period['value'])) $return['value'] .= ' '.$price_period['value'];
    
    if(isset($options['if_zero']) and $options['if_zero'] == 2 and $value == 0 and $values[$field->table_column.'_max'] == 0) $return['value'] = __($options['call_text'], WPL_TEXTDOMAIN);
    if(isset($options['if_zero']) and !$options['if_zero'] and $value == 0 and $values[$field->table_column.'_max'] == 0) $return = array();
            
	$done_this = true;
}
elseif($type == 'url' and !$done_this)  //////////////////////////// URL ////////////////////////////
{
    if(trim($value) != '')
	{
        $return['field_id'] = $field->id;
        $return['type'] = $field->type;
        $return['name'] = __($field->name, WPL_TEXTDOMAIN);

        $title = (isset($options['link_title']) and trim($options['link_title']) != '') ? $options['link_title'] : $value;
        $target = (isset($options['link_target']) and trim($options['link_target']) != '') ? $options['link_target'] : '_blank';

        $return['value'] = '<a href="'.$value.'" target="'.$target.'">'.$title.'</a>';
    }
    
	$done_this = true;
}
elseif($type == 'parent' and !$done_this)  //////////////////////////// Parent ////////////////////////////
{
    if(trim($value))
	{
        $return['field_id'] = $field->id;
        $return['type'] = $field->type;
        $return['name'] = __($field->name, WPL_TEXTDOMAIN);
        $return['value'] = wpl_property::update_property_title(NULL, $value);
    }
    
	$done_this = true;
}