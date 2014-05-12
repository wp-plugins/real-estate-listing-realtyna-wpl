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
		$this->uid = wpl_request::getVar('uid', 0);
		if(!$this->uid) $this->uid = wpl_request::getVar('sf_select_user_id', 0);
        
        /** check user id **/
		if(!$this->uid)
		{
			/** import message tpl **/
			$this->message = __("No profile found or it's not available now!", WPL_TEXTDOMAIN);
			return parent::render($this->tpl_path, 'message', false, true);
		}
        
        /** set the user id to search credentials **/
        wpl_request::setVar('sf_select_user_id', $this->uid);
        
        /** trigger event **/
		wpl_global::event_handler('profile_show', array('id'=>$this->uid));
        
		/** import tpl **/
		return parent::render($this->tpl_path, $this->tpl, false, true);
	}
}