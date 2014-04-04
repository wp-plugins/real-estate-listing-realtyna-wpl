<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** Data Structure Library
** Developed 04/9/2013
**/

class wpl_listing_types
{
	var $listing_types;
	
	public static function remove_listing_type($listing_type_id)
	{
		$query = "DELETE FROM `#__wpl_listing_types` WHERE `id`='$listing_type_id'";
		$result = wpl_db::q($query);
		
		return $result;	
	}
	
	public static function clear_empty_listing_types()
	{
		$query = "DELETE FROM `#__wpl_listing_types` WHERE `name`=''";
		$result = wpl_db::q($query);
		
		return $result;
	}
	
	/** Deprecated :: use wpl_global::get_listings instead. **/
	public static function get_listing_type($listing_type_id)
	{
		return wpl_global::get_listings($listing_type_id);
	}
	
	public static function insert_listing_type()
	{
		$query = "INSERT INTO `#__wpl_listing_types` (`parent`, `enabled`, `editable`) VALUE ('1','1','2')";
		$id = wpl_db::q($query,'insert');
		
		$query = "UPDATE `#__wpl_listing_types` SET `index`='$id.00' WHERE id=$id";
		wpl_db::q($query);
		
		return $id;
	}

	public static function update($id, $key, $value = '')
	{
		/** first validation **/
		if(trim($id) == '' or trim($key) == '') return false;
		return wpl_db::set('wpl_listing_types', $id, $key, $value);
	}
	
	public static function sort_listing_types($sort_ids)
	{
		$query = "SELECT `id`, `index` FROM `#__wpl_listing_types` WHERE `id` IN ($sort_ids) ORDER BY `index` ASC";
		$listing_types = wpl_db::select($query, 'loadAssocList');
		
		$conter = 0;
		$ex_sort_ids = explode(',', $sort_ids);
		
		foreach($ex_sort_ids as $ex_sort_id)
		{
			self::update($ex_sort_id, 'index', $listing_types[$conter]["index"]);
			$conter++;
		}
		
		return $conter;	
	}
	
	/** Deprecated :: use wpl_global::get_listings instead. **/
	public static function get_listing_types()
	{
		return wpl_global::get_listings('', 0);
	}
	
	public static function get_listing_types_category()
	{
		$query = "SELECT * FROM `#__wpl_listing_types` WHERE `parent` = '0' ORDER BY `index` ASC";
		return wpl_db::select($query, 'loadAssocList');
	}
	
	public static function get_caption_images()
	{
		$path = WPL_ABSPATH. 'assets' .DS. 'img' .DS. 'listing_types' .DS. 'caption_img';
		return wpl_global::get_icons($path);
	}
	
	public static function get_map_icons()
	{
		$path = WPL_ABSPATH. 'assets' .DS. 'img' .DS. 'listing_types' .DS. 'gicon';
		return wpl_global::get_icons($path);
	}
}
