<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Events Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 04/17/2013
 * @package WPL
 */
class wpl_events
{
    /**
     * Used for caching in get_events function
     * @static
     * @var array
     */
    public static $wpl_events = NULL;
    
    /**
     * Triggers a event
     * @author Howard <howard@realtyna.com>
     * @param string $trigger
     * @param array $params
     * @return boolean
     */
	public static function trigger($trigger, $params = array())
	{
		/** fetch events **/
		$events = self::get_events($trigger, 1);
		if(count($events) == 0) return;
        
		foreach($events as $event)
		{
			/** generate all params **/
			$all_params = array();
			$all_params[0] = $params;
			$all_params[1] = json_decode($event->params, true);
			$all_params[2] = $event;
			
			/** import class **/
			$path = _wpl_import($event->class_location, true, true);
			if(!wpl_file::exists($path)) continue;
			
			include_once $path;
			
			/** call function **/
            $event_obj = new $event->class_name();
			call_user_func(array($event_obj, $event->function_name), $all_params);
		}
        
        return true;
	}
    
    /**
     * Gets events by trigger from database
     * @author Howard <howard@realtyna.com>
     * @param string $trigger
     * @param int $enabled
     * @return array
     */
	public static function get_events($trigger, $enabled = 1)
	{
        /** return from cache if exists **/
		if(is_array(self::$wpl_events) and isset(self::$wpl_events[$trigger])) return self::$wpl_events[$trigger];
        elseif(is_array(self::$wpl_events)) return array();
        
		$query = "SELECT * FROM `#__wpl_events` WHERE `enabled`>='$enabled' ORDER BY `id` ASC";
		$results = wpl_db::select($query);
        
        $events = array();
        foreach($results as $result)
        {
            if(!isset($events[$result->trigger])) $events[$result->trigger] = array();
            $events[$result->trigger][] = $result;
        }
        
        /** add to cache **/
		self::$wpl_events = $events;
        return isset(self::$wpl_events[$trigger]) ? self::$wpl_events[$trigger] : array();
	}
    
    /**
     * Triggers one event by id
     * @author Howard <howard@realtyna.com>
     * @param int $event_id
     * @param array $params
     * @return boolean
     */
	public static function trigger_by_id($event_id, $params)
	{
		/** get event **/
		$event = self::get_event($event_id);
		if(!$event) return false;
		
		/** generate all params **/
		$all_params = array();
		$all_params[0] = $params;
		$all_params[1] = json_decode($event->params);
		$all_params[2] = $event;
		
		/** import class **/
		_wpl_import($event->class_location);
		
		/** call function **/
        $event_obj = new $event->class_name();
		call_user_func(array($event_obj, $event->function_name), $all_params);
        
        return true;
	}
    
    /**
     * Gets a single event by event_id
     * @author Howard <howard@realtyna.com>
     * @param string $event_id
     * @return array
     */
	public static function get_event($event_id)
	{
		return wpl_db::get('*', 'wpl_events', 'id', $event_id);
	}
    
    /**
     * Executes all cronjobs
     * @author Howard <howard@realtyna.com>
     * @param array $params
     * @return boolean
     */
	public static function do_cronjobs($params = array())
	{
		$cronjobs = self::get_cronjobs(1);
		
		foreach($cronjobs as $cronjob)
		{
			/** generate all params **/
			$all_params = array();
			$all_params[0] = $params;
			$all_params[1] = json_decode($cronjob->params, true);
			$all_params[2] = $cronjob;
			
			/** import class **/
			$path = _wpl_import($cronjob->class_location, true, true);
			if(!wpl_file::exists($path)) continue;
			
			include_once $path;
			
			/** call function **/
            $cron_obj = new $cronjob->class_name();
			call_user_func(array($cron_obj, $cronjob->function_name), $all_params);
			
			/** update cronjob latest run **/
			self::update_cronjob_latest_run($cronjob->id);
		}
        
        return true;
	}
	
    /**
     * Gets cronjobs
     * @author Howard <howard@realtyna.com>
     * @param int $enabled
     * @return array
     */
	public static function get_cronjobs($enabled = 1)
	{
		$query = "SELECT * FROM `#__wpl_cronjobs` WHERE DATE_ADD(`latest_run`, INTERVAL `period` HOUR)<'".date("Y-m-d H:i:s")."' AND `enabled`>='$enabled'";
		return wpl_db::select($query);
	}
	
    /**
     * Update latest run of cronjobs
     * @author Howard <howard@realtyna.com>
     * @param type $cronjob_id
     * @return boolean
     */
	public static function update_cronjob_latest_run($cronjob_id)
	{
		/** first validation **/
		if(!trim($cronjob_id)) return false;
		
		$query = "UPDATE `#__wpl_cronjobs` SET `latest_run`='".date("Y-m-d H:i:s")."' WHERE `id`='$cronjob_id'";
		wpl_db::q($query);
	}
}