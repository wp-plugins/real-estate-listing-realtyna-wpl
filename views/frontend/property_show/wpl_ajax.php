<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_property_show_controller extends wpl_controller
{
	var $tpl_path = 'views.frontend.property_show.tmpl';
	var $tpl;
	
	public function display()
	{
		$function = wpl_request::getVar('wpl_function');
	}
}