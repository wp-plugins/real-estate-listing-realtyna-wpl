<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Property types Library
 * @author Howard R <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 04/9/2013
 * @package WPL
 */
class wpl_property_types
{
    /**
     *
     * @var array
     */
	public $property_types;
	
    /**
     * Removes property type
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $property_type_id
     * @return boolean
     */
	public static function remove_property_type($property_type_id)
	{
        /** trigger event **/
		wpl_global::event_handler('property_type_removed', array('id'=>$property_type_id));

		$query = "DELETE FROM `#__wpl_property_types` WHERE `id`='$property_type_id'";
		$result = wpl_db::q($query);
		
        
		return $result;	
	}
	
    /**
     * Deprecated -> Use wpl_global::get_property_types instead.
     * @author Howard R <howard@realtyna.com>
     * @static
     * @deprecated
     * @param int $property_type_id
     * @return array
     */
	public static function get_property_type($property_type_id)
	{
		return wpl_global::get_property_types($property_type_id);
	}
	
    /**
     * Add a new property type
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $parent
     * @param string $name
     * @return int
     */
	public static function insert_property_type($parent, $name)
	{
		$query = "INSERT INTO `#__wpl_property_types`(`parent`,`enabled`,`editable`,`index`,`listing`,`name`) VALUES ('$parent', '1', '2', '00.00', '0', '$name')";
		$id = wpl_db::q($query, 'insert');
        
		$query = "UPDATE `#__wpl_property_types` SET `index`='$id.00' WHERE `id`='$id'";
		wpl_db::q($query);
        
		return $id;
	}
	
    /**
     * Sorts property types
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $sort_ids
     * @return int
     */
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
	
    /**
     * Updates a property type
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
		return wpl_db::set('wpl_property_types', $id, $key, $value);
	}
    
    /**
     * Deprecated -> Use wpl_global::get_property_types instead.
     * @author Howard R <howard@realtyna.com>
     * @static
     * @deprecated
     * @return array
     */
	public static function get_property_types()
	{
		return wpl_global::get_property_types('', 0);
	}
	
    /**
     * Gets property types category
     * @author Howard R <howard@realtyna.com>
     * @static
     * @return array
     */
	public static function get_property_type_categories()
	{
		$query = "SELECT * FROM `#__wpl_property_types` WHERE `parent` = '0' ORDER BY `index` ASC";
		return wpl_db::select($query, 'loadAssocList');
	}
    
    /**
     * Checks if a property type has properties or not
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $property_type_id
     * @return int
     */
	public static function have_properties($property_type_id)
	{
		$query = "SELECT count(`id`) as 'id' FROM `#__wpl_properties` WHERE `property_type`='$property_type_id'";
		$res = wpl_db::select($query, 'loadAssoc');
		return $res['id'];
	}
}