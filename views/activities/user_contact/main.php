<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** activity class **/
class wpl_activity_main_user_contact extends wpl_activity
{
    public $tpl_path = 'views.activities.user_contact.tmpl';
	
	public function start($layout, $params)
	{
		/** include layout **/
		$layout_path = _wpl_import($layout, true, true);
		include $layout_path;
	}
}