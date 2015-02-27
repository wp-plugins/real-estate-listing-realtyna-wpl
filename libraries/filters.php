<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Filters Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 06/15/2013
 * @package WPL
 */
class wpl_filters
{
    /**
     * Use this function for applying any filter
     * @author Howard <howard@realtyna.com>
     * @param string $trigger
     * @param array $params
     * @return mixed
     */
	public static function apply($trigger, $params = array())
	{
		/** fetch filters **/
		$filters = self::get_filters($trigger, 1);
		if(count($filters) == 0) return $params;
		
		foreach($filters as $filter)
		{
			/** generate all params **/
			$all_params = array();
			$all_params[0] = $params;
			$all_params[1] = json_decode($filter->params, true);
			$all_params[2] = $filter;
			
			/** import class **/
			$path = _wpl_import($filter->class_location, true, true);
			if(!wpl_file::exists($path)) continue;
			
			include_once $path;
			
			/** call function **/
			$params = call_user_func(array($filter->class_name, $filter->function_name), $all_params);
		}
		
		return $params;
	}
    
    /**
     * Gets filters by trigger and enabled status
     * @author Howard <howard@realtyna.com>
     * @param string $trigger
     * @param int $enabled
     * @return array
     */
	public static function get_filters($trigger, $enabled = 1)
	{
		$query = "SELECT * FROM `#__wpl_filters` WHERE `trigger`='$trigger' AND `enabled`>='$enabled'";
		return wpl_db::select($query);
	}
}