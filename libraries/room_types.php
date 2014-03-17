<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** sort options Library
** Developed 08/11/2013
**/

class wpl_room_types
{
	/**
		@input {enabled} and [condition]
		@return array room types
	**/
	public function get_room_types($enabled = 1, $condition = '', $type = '')
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
		@input $sort_ids
	**/
	public function sort_room_types($sort_ids)
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
		@input {table}, {key}, {id} and [value]
		@return boolean result
	**/
	public function update($table = 'wpl_room_types', $id, $key, $value = '')
	{
		/** first validation **/
		if(trim($table) == '' or trim($id) == '' or trim($key) == '') return false;
		return wpl_db::set($table, $id, $key, $value);
	}
	
	/**
		@input {room_id}
		@return boolean result
		@description removing an existing room type
	**/
	public function remove_room_type($room_id)
	{
		$query = "DELETE FROM `#__wpl_room_types` WHERE `id`='$room_id'";
		$result = wpl_db::q($query);
		
		return $result;
	}
	
	/**
		@input {name}
		@return boolean result
		@description adding a new room type
	**/
	public function save_room_type($name)
	{
		$query = "INSERT INTO `#__wpl_room_types` (`name`) VALUES ('$name')";
		$result = wpl_db::q($query);
		
		return $result;
	}
	
	/**
		@input {icon_name}
		@return array icon data
	**/
	public function get_icon($icon_name)
	{
		$url = wpl_global::get_wpl_asset_url('img/rooms/'.$icon_name);
		$path = WPL_ABSPATH. 'assets' .DS. 'img' .DS. 'rooms' .DS. $icon_name;
		list($width, $height) = getimagesize($path);
		
		return array('url'=>$url, 'path'=>$path, 'width'=>$width, 'height'=>$height);
	}
}