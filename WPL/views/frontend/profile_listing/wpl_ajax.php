<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import("libraries.locations");

class wpl_profile_listing_controller extends wpl_controller
{
	public function display()
	{
		$function = wpl_request::getVar('wpl_function');
	}
}