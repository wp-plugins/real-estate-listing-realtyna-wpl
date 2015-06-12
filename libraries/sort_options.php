<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Sort options library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 08/11/2013
 * @package WPL
 */
class wpl_sort_options
{
    /**
     * Gets sort options
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $kind
     * @param int $enabled
     * @param string $condition
     * @param string $output_type
     * @return array
     */
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
     * Sorts sort options
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $sort_ids
     */
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
     * Updates wpl_sort_options table
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $table
     * @param int $id
     * @param string $key
     * @param string $value
     * @return boolean
     */
	public static function update($table = 'wpl_sort_options', $id, $key, $value = '')
	{
		/** first validation **/
		if(trim($table) == '' or trim($id) == '' or trim($key) == '') return false;

		/** trigger event **/
		wpl_global::event_handler('sort_options_updated', array('id'=>$id,'key'=>$value));

		return wpl_db::set($table, $id, $key, $value);
	}
}