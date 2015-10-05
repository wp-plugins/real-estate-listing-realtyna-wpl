<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($format == 'locationtextsearch' and !$done_this)
{
    $value = stripslashes($value);
    $values_raw = array_reverse(explode(',', $value));
    
	$values = array();
    
    $l = 0;
	foreach($values_raw as $value_raw)
	{
        $l++;
		if(trim($value_raw) == '') continue;
        
        $value_raw = trim($value_raw);
        if(strlen($value_raw) == 2 and $l <= 2) $value_raw = wpl_locations::get_location_name_by_abbr($value_raw, $l);
        
        $ex_space = explode(' ', $value_raw);
        foreach($ex_space as $value_raw) array_push($values, $value_raw);
	}
	
	if(count($values))
	{
		$qqq = array();
        $qq = array();
        
        $column = 'textsearch';
        
        /** Multilingual location text search **/
        if(wpl_global::check_multilingual_status()) $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);
        
        foreach($values as $val) $qq[] = " `$column` LIKE '%LOC-".wpl_db::escape($val)."%' ";
        $qqq[] = '('.implode(' AND ', $qq).')';
        
        /** It might be search by Listing ID **/
        if(count($values) == 1) $qqq[] = '('.implode(' AND ', array(" `mls_id` LIKE '%".wpl_db::escape($values[0])."%' ")).')';
        
        $query .= " AND (".implode(' OR ', $qqq).") AND `show_address`='1'";
	}
	
	$done_this = true;
}
elseif($format == 'multiplelocationtextsearch' and !$done_this)
{
    $values_raw = explode(':', $value);
	$multiple_values = array();
	
	foreach($values_raw as $value_raw)
	{
		if(trim($value_raw) != '') array_push($multiple_values, trim($value_raw));
	}
	
	$multiple_values = array_reverse($multiple_values);
    
    if(count($multiple_values))
	{
        $qqqq = array();
        
        foreach($multiple_values as $value)
        {
            $values_raw = array_reverse(explode(',', $value));
    
            $values = array();

            $l = 0;
            foreach($values_raw as $value_raw)
            {
                $l++;
                if(trim($value_raw) == '') continue;

                $value_raw = trim($value_raw);
                if(strlen($value_raw) == 2 and $l <= 2) $value_raw = wpl_locations::get_location_name_by_abbr($value_raw, $l);
                
                $ex_space = explode(' ', $value_raw);
                foreach($ex_space as $value_raw) array_push($values, $value_raw);
            }

            if(count($values))
            {
                $qqq = array();
                $qq = array();
                
                $column = 'textsearch';

                /** Multilingual location text search **/
                if(wpl_global::check_multilingual_status()) $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);

                foreach($values as $val) $qq[] = " `$column` LIKE '%LOC-".wpl_db::escape($val)."%' ";
                $qqq[] = '('.implode(' AND ', $qq).')';
                
                /** It might be search by Listing ID **/
                if(count($values) == 1) $qqq[] = '('.implode(' AND ', array(" `mls_id` LIKE '%".wpl_db::escape($values[0])."%' ")).')';
        
                $qqqq[] = '('.implode(' OR ', $qqq).')';
            }
        }
        
        $query .= " AND (".implode(' OR ', $qqqq).") AND `show_address`='1'";
	}
    
    $done_this = true;
}