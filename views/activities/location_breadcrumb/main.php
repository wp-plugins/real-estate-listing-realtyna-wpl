<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** activity class **/
class wpl_activity_main_location_breadcrumb extends wpl_activity
{
    public $tpl_path = 'views.activities.location_breadcrumb.tmpl';
    
	public function start($layout, $params)
	{
		/** include layout **/
		$layout_path = _wpl_import($layout, true, true);
		include $layout_path;
	}
}