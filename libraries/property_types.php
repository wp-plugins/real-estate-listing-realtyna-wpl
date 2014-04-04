<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** Data Structure Library
** Developed 04/9/2013
**/

class wpl_property_types
{
	var $property_types;
	
	public static function remove_property_type($property_type_id)
	{
		$query = "DELETE FROM `#__wpl_property_types` WHERE `id`='$property_type_id'";
		$result = wpl_db::q($query);
		
		return $result;	
	}
	
	public static function clear_empty_property_types()
	{
		$query = "DELETE FROM `#__wpl_property_types` WHERE `name`=''";
		$result = wpl_db::q($query);
		
		return $result;
	}
	
	/** Deprecated :: use wpl_global::get_property_types instead. **/
	public static function get_property_type($property_type_id)
	{
		return wpl_global::get_property_types($property_type_id);
	}
	
	public static function insert_property_type()
	{
		$query = "INSERT INTO `#__wpl_property_types`(`parent`, `enabled`, `editable`, `listing`) VALUE ('1','1','2','0')";
		$id = wpl_db::q($query, 'insert');
		$query = "UPDATE `#__wpl_property_types` SET `index`='$id.00' WHERE id=$id";
		wpl_db::q($query);
		
		return $id;
	}
	
	public static function sort_property_types($sort_ids)
	{
		$query = "SELECT `id`, `index` FROM `#__wpl_property_types` WHERE `id` IN ($sort_ids) ORDER BY `index` ASC";
		$property_types = wpl_db::select($query, 'loadAssocList');
		
		$conter = 0;
		$ex_sort_ids = explode(',', $sort_ids);
		
		foreach($ex_sort_ids as $ex_sort_id)
		{
			self::update($ex_sort_id, 'index', $property_types[$conter]['index']);
			$conter++;
		}
		
		return $conter;
	}
	
	public static function update($id, $key, $value = '')
	{
		/** first validation **/
		if(trim($id) == '' or trim($key) == '') return false;
		return wpl_db::set('wpl_property_types', $id, $key, $value);
	}
	
	/** Deprecated :: use wpl_global::get_property_types instead. **/
	public static function get_property_types()
	{
		return wpl_global::get_property_types('', 0);
	}
	
	public static function get_property_types_category()
	{
		$query = "SELECT * FROM `#__wpl_property_types` WHERE `parent` = '0' ORDER BY `index` ASC";
		return wpl_db::select($query, 'loadAssocList');
	}
}