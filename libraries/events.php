<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** Events Library
** Developed 04/17/2013
**/

class wpl_events
{
	/**
		Developed by : Howard
		Inputs : trigger and dynamic params
		Outputs : void
		Date : 2013-04-17
		Description : use this function for calling any events
	**/
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
			return call_user_func(array($event->class_name, $event->function_name), $all_params);
		}
	}
	
	/**
		get events by trigger and enabled status
	**/
	public static function get_events($trigger, $enabled = 1)
	{
		$query = "SELECT * FROM `#__wpl_events` WHERE `trigger`='$trigger' AND `enabled`>='$enabled'";
		return wpl_db::select($query);
	}
	
	/**
		Developed by : Howard
		Inputs : event_id and dynamic params
		Outputs : void
		Date : 2013-04-17
		Description : use this function for calling one event
	**/
	public static function trigger_by_id($event_id, $params)
	{
		/** get event **/
		$event = self::get_event($event_id);
		if(!$event) return;
		
		/** generate all params **/
		$all_params = array();
		$all_params[0] = $params;
		$all_params[1] = json_decode($event->params);
		$all_params[2] = $event;
		
		/** import class **/
		_wpl_import($event->class_location);
		
		/** call function **/
		call_user_func(array($event->class_name, $event->function_name), $all_params);
	}
	
	/**
		get events by event_id
	**/
	public static function get_event($event_id)
	{
		return wpl_db::get('*', 'wpl_events', 'id', $event_id);
	}
	
	/**
		Execute all cronjobs
	**/
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
			call_user_func(array($cronjob->class_name, $cronjob->function_name), $all_params);
			
			/** update cronjob latest run **/
			self::update_cronjob_latest_run($cronjob->id);
		}
	}
	
	/**
		get cronjobs
	**/
	public static function get_cronjobs($enabled = 1)
	{
		$query = "SELECT * FROM `#__wpl_cronjobs` WHERE DATE_ADD(`latest_run`, INTERVAL `period` HOUR)<NOW() AND `enabled`>='$enabled'";
		return wpl_db::select($query);
	}
	
	/**
		Update latest run of cronjobs
	**/
	public static function update_cronjob_latest_run($cronjob_id)
	{
		/** first validation **/
		if(!trim($cronjob_id)) return false;
		
		$query = "UPDATE `#__wpl_cronjobs` SET `latest_run`=NOW() WHERE `id`='$cronjob_id'";
		wpl_db::q($query);
	}
}