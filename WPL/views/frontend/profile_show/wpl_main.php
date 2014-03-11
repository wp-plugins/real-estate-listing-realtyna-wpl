<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.property');
_wpl_import('libraries.render');
_wpl_import('libraries.items');
_wpl_import('libraries.activities');

class wpl_profile_show_controller extends wpl_controller
{
	var $tpl_path = 'views.frontend.profile_show.tmpl';
	var $tpl;
	var $uid;
	
	public function display($instance = array())
	{
		$this->uid = wpl_request::getVar('uid');
		
		/** import tpl **/
		return parent::display($this->tpl_path, $this->tpl, false, true);
	}
}