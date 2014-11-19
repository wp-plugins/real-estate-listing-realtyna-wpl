<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.pagination');
_wpl_import('libraries.flex');
_wpl_import('libraries.activities');

class wpl_users_controller extends wpl_controller
{
	var $tpl_path = 'views.backend.users.tmpl';
	var $tpl;
	
	public function user_manager()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$possible_orders = array('u.id', 'u.user_registered');
		
		$orderby = in_array(wpl_request::getVar('orderby'), $possible_orders) ? wpl_request::getVar('orderby') : $possible_orders[0];
		$order = in_array(strtoupper(wpl_request::getVar('order')), array('ASC','DESC')) ? wpl_request::getVar('order') : 'ASC';
		
		$page_size = trim(wpl_request::getVar('page_size')) != '' ? wpl_request::getVar('page_size') : NULL;
		
		$where_query = wpl_db::create_query();
		$num_result = wpl_db::num("SELECT COUNT(id) FROM `#__users` WHERE 1 $where_query");
        
		$this->pagination = wpl_pagination::get_pagination($num_result, $page_size);
		
		$where_query .= " ORDER BY $orderby $order ".$this->pagination->limit_query;
		$this->wp_users = wpl_users::get_wp_users($where_query);
		$this->memberships = wpl_users::get_wpl_memberships();
		
		/** import tpl **/
		parent::render($this->tpl_path, $this->tpl);
	}
	
	public function profile($instance = array())
	{
		/** check access **/
		if(!wpl_users::check_access('profilewizard'))
		{
			/** import message tpl **/
			$this->message = __("You don't have access to this part!", WPL_TEXTDOMAIN);
			return parent::render($this->tpl_path, 'message');
		}
		
		_wpl_import('libraries.flex');
		$this->tpl = 'profile';
		$this->kind = wpl_flex::get_kind_id('user');
        $this->user_id = wpl_users::get_cur_user_id();
        
        if(wpl_users::is_administrator($this->user_id) and wpl_request::getVar('id', 0))
        {
            $this->user_id = wpl_request::getVar('id');
        }
        
		$this->user_fields = wpl_flex::get_fields('', 1, $this->kind);
		$this->user_data = (array) wpl_users::get_wpl_data($this->user_id);
		
		/** import tpl **/
		parent::render($this->tpl_path, $this->tpl);
	}
}