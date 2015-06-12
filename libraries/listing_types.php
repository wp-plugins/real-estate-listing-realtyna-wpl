<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Listing types Library
 * @author Howard R <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 04/9/2013
 * @package WPL
 */
class wpl_listing_types
{
    /**
     *
     * @var array
     */
	public $listing_types;
	
    /**
     * Removes a listing type
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $listing_type_id
     * @return boolean
     */
	public static function remove_listing_type($listing_type_id)
	{
        /** trigger event **/
		wpl_global::event_handler('listing_type_removed', array('id'=>$listing_type_id));

		$query = "DELETE FROM `#__wpl_listing_types` WHERE `id`='$listing_type_id'";
		$result = wpl_db::q($query);
		
		return $result;	
	}
	
    /**
     * Deprecated -> Use wpl_global::get_listings instead.
     * @author Howard R <howard@realtyna.com>
     * @static
     * @deprecated
     * @param int $listing_type_id
     * @return array
     */
	public static function get_listing_type($listing_type_id)
	{
		return wpl_global::get_listings($listing_type_id);
	}
	
    /**
     * Add a new listing type
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param type $parent
     * @param type $name
     * @param type $gicon
     * @return type
     */
	public static function insert_listing_type($parent, $name, $gicon = NULL)
	{
		$query = "INSERT INTO `#__wpl_listing_types` (`parent`,`enabled`,`editable`,`gicon`,`name`) VALUES ('$parent','1','2','$gicon','$name')";
		$id = wpl_db::q($query, 'insert');
		
		$query = "UPDATE `#__wpl_listing_types` SET `index`='$id.00' WHERE `id`='$id'";
		wpl_db::q($query);

		/** trigger event **/
		wpl_global::event_handler('listing_type_added', array('name'=>$name));
		
		return $id;
	}
    
    /**
     * Updates a listing type
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $id
     * @param string $key
     * @param string $value
     * @return boolean
     */
	public static function update($id, $key, $value = '')
	{
		/** first validation **/
		if(trim($id) == '' or trim($key) == '') return false;
		return wpl_db::set('wpl_listing_types', $id, $key, $value);
	}
	
    /**
     * Sorts listing types
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $sort_ids
     * @return int
     */
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
	
    /**
     * Deprecated -> Use wpl_global::get_listings instead.
     * @author Howard R <howard@realtyna.com>
     * @static
     * @deprecated
     * @return array
     */
	public static function get_listing_types()
	{
		return wpl_global::get_listings('', 0);
	}
	
    /**
     * Gets listing types category
     * @author Howard R <howard@realtyna.com>
     * @static
     * @return array
     */
	public static function get_listing_type_categories()
	{
		$query = "SELECT * FROM `#__wpl_listing_types` WHERE `parent` = '0' ORDER BY `index` ASC";
		return wpl_db::select($query, 'loadAssocList');
	}
	
    /**
     * Gets caption images
     * @author Howard R <howard@realtyna.com>
     * @static
     * @return array
     */
	public static function get_caption_images()
	{
		$path = WPL_ABSPATH. 'assets' .DS. 'img' .DS. 'listing_types' .DS. 'caption_img';
		return wpl_global::get_icons($path);
	}
	
    /**
     * Gets map icons
     * @author Howard R <howard@realtyna.com>
     * @static
     * @return array
     */
	public static function get_map_icons()
	{
		$path = WPL_ABSPATH. 'assets' .DS. 'img' .DS. 'listing_types' .DS. 'gicon';
		return wpl_global::get_icons($path);
	}
    
    /**
     * CHecks if a listing type has properties or not
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $listing_type_id
     * @return type
     */
	public static function have_properties($listing_type_id)
	{
		$query = "SELECT count(`id`) as 'id' FROM `#__wpl_properties` WHERE `listing`='$listing_type_id'";
		$res = wpl_db::select($query, 'loadAssoc');
        
		return $res['id'];
	}
}
