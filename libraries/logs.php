<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** Logs Library
** Developed 04/18/2013
**/

class wpl_logs
{
	/**
		Developed by : Howard
		Inputs : log text, section, status, user_id, addon_id, priority and params
		Outputs : log_id
		Date : 2013-04-18
		Description : use this function for inserting logs in the logs table
	**/
	public static function add($log_text, $section = '', $status = 1, $user_id = '', $addon_id = '', $priority = 3, $params = array())
	{
		if(trim($log_text) == '') return false;
		
		/** set parameters **/
		$section = trim($section) != '' ? $section : 'no section';
		$status = trim($status) != '' ? $status : 1;
		$user_id = trim($user_id) != '' ? $user_id : wpl_users::get_cur_user_id();
		$addon_id = trim($addon_id) != '' ? $addon_id : 0;
		$log_date = date("Y-m-d H:i:s");
		$ip = wpl_users::get_current_ip();
		$params = json_encode($params);
		
		$query = "INSERT INTO `#__wpl_logs` (`user_id`,`addon_id`,`section`,`status`,`log_text`,`log_date`,`ip`,`priority`,`params`) VALUES ('$user_id','$addon_id','$section','$status','$log_text','$log_date','$ip','$priority','$params');";
		return wpl_db::q($query, 'insert');
	}
	
	/**
		Developed by : Howard
		Inputs : [log_id], [prior_date], [addon_id]
		Outputs : void
		Date : 2013-04-18
		Description : use this function for deleting logs by id or prior date or addon_id
	**/
	public static function delete($log_id = '', $prior_date = '', $addon_id = '')
	{
		if(trim($log_id) == '' and trim($prior_date) == '' and trim($addon_id) == '') return false;
		
		$where = '';
		if(trim($log_id) != '') $where .= " AND `id`='$log_id'";
		if(trim($prior_date) != '') $where .= " AND `log_date`<'$prior_date'";
		if(trim($addon_id) != '') $where .= " AND `addon_id`='$addon_id'";
		
		if(trim($where) == '') return false;
		
		$query = "DELETE FROM `#__wpl_logs` WHERE 1 ".$where;
		return wpl_db::q($query, 'delete');
	}
	
	/**
		Developed by : Howard
		Inputs : {log_id}
		Outputs : log data
		Date : 2013-04-18
		Description : use this function for get one log
	**/
	public static function get($log_id)
	{
		if(trim($log_id) == '') return false;
		return wpl_db::get('*', 'wpl_logs', 'id', $log_id);
	}
	
	/**
		Developed by : Howard
		Inputs : {log_id}
		Outputs : log data
		Date : 2013-04-18
		Description : use this function for get one log
	**/
	public static function get_logs($condition = '')
	{
		$query = "SELECT * FROM `#__wpl_logs` WHERE 1 ".$condition;
		return wpl_db::select($query, 'select');
	}
}