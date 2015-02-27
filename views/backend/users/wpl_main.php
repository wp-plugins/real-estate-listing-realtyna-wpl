<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.pagination');
_wpl_import('libraries.flex');
_wpl_import('libraries.activities');

class wpl_users_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.users.tmpl';
	public $tpl;
	
	public function user_manager()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$possible_orders = array('u.id', 'u.user_registered');
		
		$orderby = in_array(wpl_request::getVar('orderby'), $possible_orders) ? wpl_request::getVar('orderby') : $possible_orders[0];
		$order = in_array(strtoupper(wpl_request::getVar('order')), array('ASC','DESC')) ? wpl_request::getVar('order') : 'ASC';
		
		$page_size = trim(wpl_request::getVar('page_size')) != '' ? wpl_request::getVar('page_size') : NULL;
        $this->show_all = wpl_request::getVar('show_all', 0);
		$this->filter = wpl_request::getVar('filter', '');
        $this->membership_id = wpl_request::getVar('membership_id', '');
        
		$where_query = wpl_db::create_query();
        if(trim($this->filter)) $where_query = " AND (`user_login` LIKE '%".$this->filter."%' OR `user_email` LIKE '%".$this->filter."%' OR `first_name` LIKE '%".$this->filter."%' OR `last_name` LIKE '%".$this->filter."%')";
        if(trim($this->membership_id)) $where_query = " AND `membership_id`='".$this->membership_id."'";
        
		$num_result = wpl_db::num("SELECT COUNT(u.ID) FROM `#__users` AS u ".($this->show_all ? 'LEFT' : 'INNER')." JOIN `#__wpl_users` AS wpl ON u.ID = wpl.id WHERE 1 $where_query");
        
		$this->pagination = wpl_pagination::get_pagination($num_result, $page_size);
		$where_query .= " ORDER BY $orderby $order ".$this->pagination->limit_query;
		
        if($this->show_all) $this->wp_users = wpl_users::get_wp_users($where_query);
        else $this->wp_users = wpl_users::get_wpl_users($where_query);
        
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
        
		$this->user_fields = wpl_flex::get_fields('', 1, $this->kind, 'pwizard', 1);
		$this->user_data = (array) wpl_users::get_wpl_data($this->user_id);
		
		/** import tpl **/
		parent::render($this->tpl_path, $this->tpl);
	}
}