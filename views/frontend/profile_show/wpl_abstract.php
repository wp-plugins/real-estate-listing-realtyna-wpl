<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.property');
_wpl_import('libraries.render');
_wpl_import('libraries.items');
_wpl_import('libraries.activities');

abstract class wpl_profile_show_controller_abstract extends wpl_controller
{
	public $tpl_path = 'views.frontend.profile_show.tmpl';
	public $tpl;
	public $uid;
	
	public function display($instance = array())
	{
		$this->uid = wpl_request::getVar('uid', 0);
		if(!$this->uid)
        {
            $this->uid = wpl_request::getVar('sf_select_user_id', 0);
            wpl_request::setVar('uid', $this->uid);
        }
        
        /** check user id **/
		if(!$this->uid)
		{
			/** import message tpl **/
			$this->message = __("No profile found or it's not available now!", WPL_TEXTDOMAIN);
			return parent::render($this->tpl_path, 'message', false, true);
		}
        
        /** set the user id to search credentials **/
        wpl_request::setVar('sf_select_user_id', $this->uid);
        
        /** set the kind **/
        $this->kind = wpl_request::getVar('kind', '0');
        wpl_request::setVar('kind', $this->kind);
        
        /** User Type **/
        $this->user_type = wpl_users::get_user_user_type($this->uid);
        
        /** trigger event **/
		wpl_global::event_handler('profile_show', array('id'=>$this->uid, 'kind'=>$this->kind));
        
        /** import tpl **/
        $this->tpl = wpl_users::get_user_type_tpl($this->tpl_path, $this->tpl, $this->user_type);
        
		/** import tpl **/
		return parent::render($this->tpl_path, $this->tpl, false, true);
	}
}