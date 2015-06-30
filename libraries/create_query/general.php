<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($format == 'select' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
		$query .= " AND `".$table_column."` = '".$value."'";
	}
	
	$done_this = true;
}
elseif($format == 'ptcategory' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
        $category_id = wpl_db::select("SELECT `id` FROM `#__wpl_property_types` WHERE LOWER(name)='".strtolower($value)."' AND `parent`='0'", 'loadResult');
        $property_types = wpl_db::select("SELECT `id` FROM `#__wpl_property_types` WHERE `parent`='$category_id'", 'loadAssocList');
		
        $property_types_str = '';
        if(count($property_types))
        {
            foreach($property_types as $property_type) $property_types_str .= $property_type['id'].',';
            $property_types_str = trim($property_types_str, ', ');
        }
        
        if(trim($property_types_str)) $query .= " AND `property_type` IN ($property_types_str)";
	}
	
	$done_this = true;
}
elseif($format == 'ltcategory' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
        $category_id = wpl_db::select("SELECT `id` FROM `#__wpl_listing_types` WHERE LOWER(name)='".strtolower($value)."' AND `parent`='0'", 'loadResult');
        $listing_types = wpl_db::select("SELECT `id` FROM `#__wpl_listing_types` WHERE `parent`='$category_id'", 'loadAssocList');
		
        $listing_types_str = '';
        if(count($listing_types))
        {
            foreach($listing_types as $listing_type) $listing_types_str .= $listing_type['id'].',';
            $listing_types_str = trim($listing_types_str, ', ');
        }
        
        if(trim($listing_types_str)) $query .= " AND `listing` IN ($listing_types_str)";
	}
	
	$done_this = true;
}
elseif($format == 'datemin' and !$done_this)
{
	_wpl_import('libraries.render');
	
	if(trim($value) != '')
	{
		$value = wpl_render::derender_date($value);
		$query .= " AND DATE(`".$table_column."`) >= '".$value."'";
	}
    
	$done_this = true;
}
elseif($format == 'datemax' and !$done_this)
{
	_wpl_import('libraries.render');
	
	if(trim($value) != '')
	{
		$value = wpl_render::derender_date($value);
		$query .= " AND DATE(`".$table_column."`) <= '".$value."'";
	}
    
	$done_this = true;
}
elseif($format == 'rawdatemin' and !$done_this)
{
	if(trim($value) != '') $query .= " AND DATE(`".$table_column."`) >= '".$value."'";
	$done_this = true;
}
elseif($format == 'rawdatemax' and !$done_this)
{
	if(trim($value) != '') $query .= " AND DATE(`".$table_column."`) <= '".$value."'";
	$done_this = true;
}
elseif($format == 'gallery' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
		$query .= " AND (`pic_numb`>0)";
	}
	
	$done_this = true;
}
elseif($format == 'tmin' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
		$min = $value;
		$max = isset($vars['sf_tmax_'.$table_column]) ? $vars['sf_tmax_'.$table_column] : 999999999999;
		
		$query .= " AND `".$table_column."` >= ".$min." AND `".$table_column."` <= ".$max."";
	}
	
	$done_this = true;
}
elseif($format == 'multiple' and !$done_this)
{
	if(!($value == '' or $value == '-1' or $value == ','))
	{
		$value = rtrim($value, ',');
		if($value != '')
        {
            $values_ex = explode(',', $value);
            $value_str = '';
            foreach($values_ex as $value_ex) $value_str .= "'$value_ex',";
            
            $query .= " AND `".$table_column."` IN (".trim($value_str, ', ').")";
        }
	}
	
	$done_this = true;
}
elseif($format == 'notselect' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
		$query .= " AND `".$table_column."` != '".$value."'";
	}
	
	$done_this = true;
}
elseif($format == 'parent' and !$done_this)
{
	if($value != '-1' and trim($value) != '')
	{
        /** converts listing id to property id **/
        if($value) $value = wpl_property::pid($value);
        
		$query .= " AND `parent` = '".$value."'";
	}
	
	$done_this = true;
}
elseif($format == 'textsearch' and !$done_this)
{
	if(trim($value) != '')
	{
        /** If the field is multilingual or it is textsearch field **/
        if(wpl_global::check_multilingual_status() and (wpl_addon_pro::get_multiligual_status_by_column($table_column, wpl_request::getVar('kind', 0)) or $table_column == 'textsearch')) $table_column = wpl_addon_pro::get_column_lang_name($table_column, wpl_global::get_current_language(), false);
        
		$query .= " AND `".$table_column."` LIKE '%".$value."%'";
	}
	
	$done_this = true;
}
elseif($format == 'text' and !$done_this)
{
	if(trim($value) != '')
	{
		$query .= " AND `".$table_column."` LIKE '%".$value."%'";
	}
	
	$done_this = true;
}
elseif($format == 'unit' and !$done_this)
{
	/** importing library **/
	_wpl_import('libraries.units');
	
	if($value != '-1' and trim($value) != '')
	{
		$unit_data = wpl_units::get_unit($value);
		
		$min = isset($vars['sf_min_'.$table_column]) ? $vars['sf_min_'.$table_column] : 0;
		$max = isset($vars['sf_max_'.$table_column]) ? $vars['sf_max_'.$table_column] : 0;
		
		$si_value_min = $unit_data['tosi'] * $min;
		$si_value_max = $unit_data['tosi'] * $max;
		
		if($si_value_max != 0) $query .= " AND `".$table_column."_si` <= '".$si_value_max."'";
		if($si_value_min != 0) $query .= " AND `".$table_column."_si` >= '".$si_value_min."'";
	}
	
	$done_this = true;
}