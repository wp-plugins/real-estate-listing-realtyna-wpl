<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Room Types Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 08/11/2013
 * @package WPL
 */
class wpl_room_types
{
    /**
     * Get room types
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $enabled
     * @param string $condition
     * @param string $type
     * @return array
     */
	public static function get_room_types($enabled = 1, $condition = '', $type = '')
	{
		if(trim($condition) == '')
		{
			$condition = '';
			
			if(trim($type) != '') $condition .= " AND `type`='$type'";
			if(trim($enabled) != '') $condition .= " AND `enabled`>='$enabled'";
		}
		
		$query = "SELECT * FROM `#__wpl_room_types` WHERE 1 ".$condition." ORDER BY `index` ASC";
		return wpl_db::select($query, 'loadAssocList');
	}
	
    /**
     * Sorts room types
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $sort_ids
     */
	public static function sort_room_types($sort_ids)
	{
		$query = "SELECT `id`,`index` FROM `#__wpl_room_types` WHERE `id` IN ($sort_ids) ORDER BY `index` ASC";
		$options = wpl_db::select($query, 'loadAssocList');
		
		$counter = 1;
		$ex_sort_ids = explode(',', $sort_ids);
		
		foreach($ex_sort_ids as $ex_sort_id)
		{
			self::update('wpl_room_types', $ex_sort_id, 'index', $counter);
			$counter++;
		}
	}
	
    /**
     * Updates a room type
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $table
     * @param int $id
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
	public static function update($table = 'wpl_room_types', $id, $key, $value = '')
	{
		/** first validation **/
		if(trim($table) == '' or trim($id) == '' or trim($key) == '') return false;
		return wpl_db::set($table, $id, $key, $value);
	}
	
    /**
     * Removes room type
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $room_id
     * @return mixed
     */
	public static function remove_room_type($room_id)
	{
		$query = "DELETE FROM `#__wpl_room_types` WHERE `id`='$room_id'";
		$result = wpl_db::q($query);
		
		return $result;
	}
	
    /**
     * Adds a new room type
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $name
     * @return int
     */
	public static function save_room_type($name)
	{
		$query = "INSERT INTO `#__wpl_room_types` (`name`,`icon`) VALUES ('$name', 'default.png')";
		$id = wpl_db::q($query, 'insert');
		
        /** trigger event **/
		wpl_global::event_handler('room_type_added', array('id'=>$id));
        
		return $id;
	}
    
    /**
     * Get a room type by name
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $name
     * @return array
     */
    public static function get_room_type($name)
	{
		$query = "SELECT * FROM `#__wpl_room_types` WHERE `name`='$name' LIMIT 1";
		return wpl_db::select($query, 'loadAssoc');
	}
	
    /**
     * Returns icon details
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $icon_name
     * @return array
     */
	public static function get_icon($icon_name)
	{
		$url = wpl_global::get_wpl_asset_url('img/rooms/'.$icon_name);
		$path = WPL_ABSPATH. 'assets' .DS. 'img' .DS. 'rooms' .DS. $icon_name;
		list($width, $height) = getimagesize($path);
		
		return array('url'=>$url, 'path'=>$path, 'width'=>$width, 'height'=>$height);
	}
}