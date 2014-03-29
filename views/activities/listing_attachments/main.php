<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.room_types');

/** activity class **/
class wpl_activity_main_listing_attachments extends wpl_activity
{
    var $tpl_path = 'views.activities.listing_attachments.tmpl';
	
	public function start($layout, $params)
	{
		/** include layout **/
		$layout_path = _wpl_import($layout, true, true);
		include $layout_path;
	}
}