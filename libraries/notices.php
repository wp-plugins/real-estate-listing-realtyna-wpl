<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** notice Library
** Developed 04/03/2013
**/

class wpl_notices
{
	/** developed by Howard 04/03/2013 **/
	public function get_notices_by_category($category)
	{
		if(trim($category) == '') return;
		
		$query = "SELECT * FROM `#__wpl_notices` WHERE `category` = '$category'";
		$results = wpl_db::select($query, 'loadObjectList');
		
		return self::get_overrided_notices($results);
	}
	
	/** developed by Howard 04/03/2013 **/
	public function get_notices_by_ids($notice_ids = array())
	{
		if(!is_array($notice_ids)) $notice_ids = array($notice_ids);
		if(count($notice_ids) == 0) return;
		
		$imploded = implode(',', $notice_ids);
		$query = "SELECT * FROM `#__wpl_notices` WHERE `id` IN ($imploded)";
		$results = wpl_db::select($query, 'loadObjectList');
		
		return self::get_overrided_notices($results);
	}
	
	/** developed by Howard 04/03/2013 **/
	public function get_overrided_notices($results)
	{
		$notices = array();
		
		$i = 0;
		foreach($results as $result)
		{
			$notices[$i]['id'] = $result->id;
			$notices[$i]['category'] = $result->category;
			$notices[$i]['title'] = trim($result->title_override) != '' ? $result->title_override : $result->title;
			$notices[$i]['body'] = trim($result->body_override) != '' ? $result->body_override : $result->body;
			
			$i++;
		}
		
		return $notices;
	}
	
	/** developed by Howard 04/03/2013 **/
	public function display_notices($notices, $show_counter = true)
	{
		if(count($notices) == 0) return;
		
		/** find notice tpl path **/
		$notice_tpl_path = _wpl_import('views.basics.notices.default', true, true);
		$string = "";
		$i = 1;
		
		foreach($notices as $notice)
		{
			if(trim($notice['body']) == '') continue;
			
			$rendered = '';
			
			include $notice_tpl_path;
			$string .= $rendered;
		
			$i++;
		}
		
		return $string;
	}
	
	/** developed by Martin 04/03/2013 **/
	public function display_tooltip($notice_id)
	{
		if(!isset($notice_id)) return;
		
		/** find notice tpl path **/
		$notice_tpl_path = _wpl_import('views.basics.notices.tooltip', true, true);
		
		$query = "SELECT * FROM `#__wpl_notices` WHERE `id`=$notice_id";
		$notice = wpl_db::select($query, 'loadAssoc');
		
		if(trim($notice['body']) == '') return;
		
		$rendered = "";	
		include $notice_tpl_path;
		
		return $rendered;
	}
	
	/** developed by Martin 04/03/2013 **/
	public function display_tooltip_by_category($category)
	{
		if(!isset($category)) return;
		
		/** find notice tpl path **/
		$notice_tpl_path = _wpl_import('views.basics.notices.tooltip', true, true);
		
		$query = "SELECT * FROM `#__wpl_notices` WHERE `category`='$category'";
		$notice = wpl_db::select($query, 'loadAssoc');
		
		return $notice;
	}
}