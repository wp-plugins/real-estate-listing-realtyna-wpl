<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.locations');
_wpl_import('libraries.pagination');
_wpl_import('libraries.settings');

class wpl_location_manager_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.location_manager.tmpl';
	public $tpl;
	
	public function home()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$this->level = trim(wpl_request::getVar('level')) != '' ? wpl_request::getVar('level') : 1;
		$this->parent = trim(wpl_request::getVar('sf_select_parent')) != '' ? wpl_request::getVar('sf_select_parent') : "";
		$this->enabled = trim(wpl_request::getVar('sf_select_enabled')) != '' ? wpl_request::getVar('sf_select_enabled') : 1;
		$this->text_search = trim(wpl_request::getVar('sf_text_name')) != '' ? wpl_request::getVar('sf_text_name') : '';
		$this->admin_url = wpl_global::get_wp_admin_url();
		$this->load_zipcodes = trim(wpl_request::getVar('load_zipcodes')) != '' ? 1 : 0;
		
		/** set show all based on level **/
		if($this->level != 1) $this->enabled = '';
		
		$possible_orders = array('id','name');
		
		$orderby = in_array(wpl_request::getVar('orderby'), $possible_orders) ? wpl_request::getVar('orderby') : $possible_orders[0];
		$order = in_array(strtoupper(wpl_request::getVar('order')), array('ASC','DESC')) ? wpl_request::getVar('order') : 'ASC';
		
		$page_size = trim(wpl_request::getVar('page_size')) != '' ? wpl_request::getVar('page_size') : NULL;
		
		/** create where **/
		$vars = array_merge(wpl_request::get('POST'), wpl_request::get('GET'));
		$vars = array_merge($vars, array('sf_select_parent'=>$this->parent, 'sf_select_enabled'=>$this->enabled));
		$where_query = wpl_db::create_query($vars);
		
		$num_result = wpl_db::num("SELECT COUNT(id) FROM `#__wpl_location".$this->level."` WHERE 1 ".$where_query);
		
		$this->pagination = wpl_pagination::get_pagination($num_result, $page_size);
		$where_query .= " ORDER BY $orderby $order ".$this->pagination->limit_query;
		
		$this->wp_locations = wpl_locations::get_locations((!$this->load_zipcodes ? $this->level : 'zips'), '', '', $where_query);
		$this->zipcode_parent_level = wpl_settings::get('zipcode_parent_level');
		
		/** import tpl **/
		parent::render($this->tpl_path, $this->tpl);
	}
}