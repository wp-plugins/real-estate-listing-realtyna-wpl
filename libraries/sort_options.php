<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** sort options Library
** Developed 08/11/2013
**/

class wpl_sort_options
{
	/**
		return sort options
	**/
	public static function get_sort_options($kind = '', $enabled = 1, $condition = '', $output_type = 'loadAssocList')
	{
		if(trim($condition) == '')
		{
			$condition = "";
			
			if(trim($enabled) != '') $condition .= " AND `enabled`>='$enabled'";
			if(trim($kind) != '') $condition .= " AND `kind` LIKE '%[$kind]%'";
			$condition .= " ORDER BY `index` ASC";
		}
		
		$query = "SELECT * FROM `#__wpl_sort_options` WHERE 1 ".$condition;
		return wpl_db::select($query, $output_type);
	}
	
	/**
		@input $sort_ids
	**/
	public static function sort_options($sort_ids)
	{
		$query = "SELECT `id`,`index` FROM `#__wpl_sort_options` WHERE `id` IN ($sort_ids) ORDER BY `index` ASC";
		$options = wpl_db::select($query, 'loadAssocList');
		
		$conter = 0;
		$ex_sort_ids = explode(',', $sort_ids);
		
		foreach($ex_sort_ids as $ex_sort_id)
		{
			self::update('wpl_sort_options', $ex_sort_id, 'index', $options[$conter]["index"]);
			$conter++;
		}
	}
	
	/**
		@input {table}, {key}, {id} and [value]
		@return boolean result
	**/
	public static function update($table = 'wpl_sort_options', $id, $key, $value = '')
	{
		/** first validation **/
		if(trim($table) == '' or trim($id) == '' or trim($key) == '') return false;
		return wpl_db::set($table, $id, $key, $value);
	}
}