<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_profile_show_controller extends wpl_controller
{
	public function display()
	{
		$function = wpl_request::getVar('wpl_function');
	}
}