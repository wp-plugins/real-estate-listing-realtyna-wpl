<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'number' and !$done_this) //////////////////////////// number ////////////////////////////
{
	if(trim($value) != '')
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		$return['value'] = $value;
        
        if(isset($options['if_zero']) and $options['if_zero'] == 2 and !trim($value)) $return['value'] = __($options['call_text'], WPL_TEXTDOMAIN);
        if(isset($options['if_zero']) and !$options['if_zero'] and !trim($value)) $return = array();
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
		
		if($field->table_column == 'googlemap_ln') #Longitude
			$return['value'] = wpl_render::render_longitude($value);
		elseif($field->table_column == 'googlemap_lt') #Latitude
			$return['value'] = wpl_render::render_latitude($value);
		else
        {
            if(isset($field->multilingual) and $field->multilingual and wpl_global::check_multilingual_status())
            {
                $current_language = wpl_global::get_current_language();
                $lang_column = wpl_addon_pro::get_column_lang_name($field->table_column, $current_language, false);
                
                if(isset($values[$lang_column])) $value = $values[$lang_column];
            }
            
            $return['value'] = $value;
        }
        
        if(isset($options['if_zero']) and $options['if_zero'] == 2 and $value == '0') $return['value'] = __($options['call_text'], WPL_TEXTDOMAIN);
        if(isset($options['if_zero']) and !$options['if_zero'] and $value == '0') $return = array();
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
elseif($type == 'textarea' and !$done_this) //////////////////////////// textarea ////////////////////////////
{
	if(trim($value) != '')
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
        
        if(isset($field->multilingual) and $field->multilingual and wpl_global::check_multilingual_status())
        {
            $current_language = wpl_global::get_current_language();
            $lang_column = wpl_addon_pro::get_column_lang_name($field->table_column, $current_language, false);

            if(isset($values[$lang_column])) $value = $values[$lang_column];
        }
        
        $value = stripslashes($value);
        if($field->table_column == 'field_308') $value = apply_filters('the_content', $value);
        $value = wpl_global::do_shortcode($value);
        
        $return['value'] = $value;
	}
	
	$done_this = true;
}
elseif($type == 'feature' and !$done_this) //////////////////////////// Features ////////////////////////////
{
	if($values[$field->table_column] != 0) 
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		
		/** options of property column **/
		$column_options = $values[$field->table_column.'_options'];
		$column_values = explode(',', trim($column_options, ', '));
        if(count($column_values) == 1 and trim($column_values[0]) == '') $column_values = array();
        
		$i = 0;
		if(isset($options['values']))
		{
			foreach($options['values'] as $field_option)
			{
				if(in_array($field_option['key'], $column_values))
				{
					$return['values'][$i] = __($field_option['value'], WPL_TEXTDOMAIN);
					$i++;
				}
			}
		}
		else
		{
			$return['value'] = 1;
		}
	}
	
	$done_this = true;       
}
elseif($type == 'neighborhood' and !$done_this) //////////////////////////// Neighborhood ////////////////////////////
{
	if($values[$field->table_column] == '1')
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		
		if($values[$field->table_column.'_distance'] != 0 and $values[$field->table_column.'_distance_by'] != 0)
		{
			$return['distance'] = $values[$field->table_column.'_distance'];
			
			if($values[$field->table_column.'_distance_by'] == '1')
            {
                $return['vehicle_type'] = 'Walk';
                $return['by'] = __('Walk', WPL_TEXTDOMAIN);
            }
			elseif($values[$field->table_column.'_distance_by'] == '2')
            {
                $return['vehicle_type'] = 'Car';
                $return['by'] = __('Car', WPL_TEXTDOMAIN);
            }
			elseif($values[$field->table_column.'_distance_by'] == '3')
            {
                $return['vehicle_type'] = 'Train';
                $return['by'] = __('Train', WPL_TEXTDOMAIN);
            }
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
elseif($type == 'property_types' and !$done_this) //////////////////////////// property types ////////////////////////////
{
	if(trim($value) != '0' or trim($value) != '-1')
	{
		/** get property type **/
		$property_type = wpl_global::get_property_types($value);
        
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		$return['value'] = is_object($property_type) ? __($property_type->name, WPL_TEXTDOMAIN) : NULL;
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
		$return['value'] = is_object($listing_type) ? __($listing_type->name, WPL_TEXTDOMAIN) : NULL;
	}
	
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
elseif($type == 'mmnumber' and !$done_this) //////////////////////////// Min/Max numbers ////////////////////////////
{
    if(trim($value) != '')
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		$return['value'] = $value;
        
        if(trim($values[$field->table_column.'_max'])) $return['value'] .= ' - '. $values[$field->table_column.'_max'];
        
        if(isset($options['if_zero']) and $options['if_zero'] == 2 and !trim($value) and !trim($values[$field->table_column.'_max'])) $return['value'] = __($options['call_text'], WPL_TEXTDOMAIN);
        if(isset($options['if_zero']) and !$options['if_zero'] and !trim($value) and !trim($values[$field->table_column.'_max'])) $return = array();
	}
	
	$done_this = true;
}
elseif($type == 'locations' and !$done_this) //////////////////////////// Locations ////////////////////////////
{
	_wpl_import('libraries.locations');
	$location_settings = wpl_global::get_settings('3'); # location settings
	
	$return['field_id'] = $field->id;
	$return['type'] = $field->type;
	$return['name'] = __($field->name, WPL_TEXTDOMAIN);
	
	for($i=1; $i<=7; $i++)
	{
		$location_id = isset($values['location'.$i.'_id']) ? $values['location'.$i.'_id'] : NULL;
		if(!isset($values['location'.$i.'_name'])) continue;
		if(!trim($values['location'.$i.'_name'])) continue;
		
		$return['location_ids'][$i] = $location_id;
		$return['locations'][$i] = __($values['location'.$i.'_name'], WPL_TEXTDOMAIN);
        $return['raw'][$i] = $values['location'.$i.'_name'];
		$return['keywords'][$i] = __($location_settings['location'.$i.'_keyword'], WPL_TEXTDOMAIN);
	}
	
	if(isset($values['zip_name']) and trim($values['zip_name']))
	{
		$return['location_ids']['zips'] = $values['zip_id'];
		$return['locations']['zips'] = __($values['zip_name'], WPL_TEXTDOMAIN);
        $return['raw']['zips'] = $values['zip_name'];
		$return['keywords']['zips'] = __($location_settings['locationzips_keyword'], WPL_TEXTDOMAIN);
	}
	
	$done_this = true;
}
elseif(($type == 'checkbox' or $type == 'tag') and !$done_this) //////////////////////////// Checkbox, Tag ////////////////////////////
{
	if($value != '0')
	{
		$return['field_id'] = $field->id;
		$return['type'] = $field->type;
		$return['name'] = __($field->name, WPL_TEXTDOMAIN);
		$return['value'] = __('Yes', WPL_TEXTDOMAIN);
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
        
        if(isset($options['if_zero']) and $options['if_zero'] == 2 and !trim($value)) $return['value'] = __($options['call_text'], WPL_TEXTDOMAIN);
        if(isset($options['if_zero']) and !$options['if_zero'] and !trim($value)) $return = array();
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
        
        if(isset($options['if_zero']) and $options['if_zero'] == 2 and !trim($value) and !trim($values[$field->table_column.'_max'])) $return['value'] = __($options['call_text'], WPL_TEXTDOMAIN);
        if(isset($options['if_zero']) and !$options['if_zero'] and !trim($value) and !trim($values[$field->table_column.'_max'])) $return = array();
	}
	
	$done_this = true;
}
elseif($type == 'price' and !$done_this) //////////////////////////// Price ////////////////////////////
{
	$return['field_id'] = $field->id;
	$return['type'] = $field->type;
	$return['name'] = __($field->name, WPL_TEXTDOMAIN);
    
	$rendered_price = wpl_render::render_price($value, $values[$field->table_column.'_unit']);
    $return['value'] = $rendered_price;
    $return['price_only'] = $rendered_price;
	
    $price_period = array();
    if(isset($values[$field->table_column.'_period'])) $price_period = wpl_property::render_field($values[$field->table_column.'_period'], wpl_flex::get_dbst_id($field->table_column.'_period', $field->kind));
    if(isset($price_period['value']))
    {
        $return['value'] .= ' '.$price_period['value'];
        $return['price_period'] = $price_period['value'];
    }
    
    /** Add "From" to Vacation Rental Properties **/
    if($field->table_column == 'price' and wpl_global::check_addon('calendar'))
    {
        $listing_types = wpl_global::get_listing_types_by_parent(3);
        foreach($listing_types as $listing) $vacational_listing_types[] = $listing['id'];

        if(is_array($vacational_listing_types) and array_key_exists('listing', $values) and in_array($values['listing'], $vacational_listing_types))
        {
            $return['value'] = __('From', WPL_TEXTDOMAIN).' '.$return['value'];
        }
    }
    
    if(isset($options['if_zero']) and $options['if_zero'] == 2 and !trim($value)) $return['value'] = __($options['call_text'], WPL_TEXTDOMAIN);
    if(isset($options['if_zero']) and !$options['if_zero'] and !trim($value)) $return = array();
        
	$done_this = true;
}
elseif($type == 'mmprice' and !$done_this) //////////////////////////// Min/Max Price ////////////////////////////
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
    if(isset($price_period['value']))
    {
        $return['value'] .= ' '.$price_period['value'];
        $return['price_period'] = $price_period['value'];
    }
    
    if(isset($options['if_zero']) and $options['if_zero'] == 2 and !trim($value) and !trim($values[$field->table_column.'_max'])) $return['value'] = __($options['call_text'], WPL_TEXTDOMAIN);
    if(isset($options['if_zero']) and !$options['if_zero'] and !trim($value) and !trim($values[$field->table_column.'_max'])) $return = array();
            
	$done_this = true;
}
elseif($type == 'url' and !$done_this) //////////////////////////// URL ////////////////////////////
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
elseif($type == 'parent' and !$done_this) //////////////////////////// Parent ////////////////////////////
{
    if(trim($value))
	{
        $return['field_id'] = $field->id;
        $return['type'] = $field->type;
        $return['name'] = __($field->name, WPL_TEXTDOMAIN);
        
        $parents_ids = wpl_render::render_parent($value, (isset($options['key']) ? $options['key'] : 'parent'), true);
        
        $value_str = '';
        $parents = array_reverse(explode(',', $parents_ids));
        foreach($parents as $parent) $value_str .= '<a href="'.wpl_property::get_property_link(NULL, $parent).'">'.trim(wpl_property::update_property_title(NULL, $parent), ', ').'</a> / ';

        $return['value'] = trim($value_str, '/ ');
        
        $parents_html_str = '';
        $parents = array_reverse(explode(',', $parents_ids));
        foreach($parents as $parent) $parents_html_str .= '<a href="'.wpl_property::get_property_link(NULL, $parent).'"><b>'.trim(wpl_property::update_property_title(NULL, $parent), ', ').'</b></a> / ';

        $return['html'] = trim($parents_html_str, '/ ');
    }
    
	$done_this = true;
}
elseif($type == 'date' and !$done_this) //////////////////////////// Date ////////////////////////////
{
    if(trim($value))
	{
        $return['field_id'] = $field->id;
        $return['type'] = $field->type;
        $return['name'] = __($field->name, WPL_TEXTDOMAIN);
        $return['value'] = wpl_render::render_date($value);
    }
	
	$done_this = true;
}
elseif($type == 'datetime' and !$done_this) //////////////////////////// Date Time ////////////////////////////
{
    if(trim($value))
	{
        $return['field_id'] = $field->id;
        $return['type'] = $field->type;
        $return['name'] = __($field->name, WPL_TEXTDOMAIN);
        $return['value'] = wpl_render::render_datetime($value);
    }
	
	$done_this = true;
}