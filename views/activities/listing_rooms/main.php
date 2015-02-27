<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.room_types');

/** activity class **/
class wpl_activity_main_listing_rooms extends wpl_activity
{
    public $tpl_path = 'views.activities.listing_rooms.tmpl';
	
	public function start($layout, $params)
	{
		$room_types = wpl_global::return_in_id_array(wpl_room_types::get_room_types());
		
		/** include layout **/
		$layout_path = _wpl_import($layout, true, true);
		include $layout_path;
	}
}