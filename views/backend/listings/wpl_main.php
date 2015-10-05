<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.property');
_wpl_import('libraries.pagination');
_wpl_import('libraries.settings');
_wpl_import('libraries.items');
_wpl_import('libraries.images');
_wpl_import('libraries.activities');

class wpl_listings_controller extends wpl_controller
{
    public $tpl_path = 'views.backend.listings.tmpl';
    public $tpl;

    public function manager($instance = array())
    {
		/** check access **/
		if(!wpl_users::check_access('propertymanager'))
		{
			/** import message tpl **/
			$this->message = __("You don't have access to this part!", WPL_TEXTDOMAIN);
			return parent::render($this->tpl_path, 'message');
		}
		
        $init = $this->init_page();
        if(!$init) return false;
        
		$this->tpl = wpl_flex::get_kind_tpl($this->tpl_path, wpl_request::getVar('tpl', 'manager'), $this->kind);
        parent::render($this->tpl_path, $this->tpl);
    }
    
    /**
     * written by Francis
     * description: initialize pagination and properties for property manager page
     */
    private function init_page()
    {
        /** global settings **/
        $settings = wpl_settings::get_settings();

        /** listing settings **/
		$this->page_number = wpl_request::getVar('wplpage', 1);
        $limit = wpl_request::getVar('limit', $settings['default_page_size']);
        $start = wpl_request::getVar('start', (($this->page_number-1)*$limit));
        $orderby = wpl_request::getVar('orderby', $settings['default_orderby']);
        $order = wpl_request::getVar('order', $settings['default_order']);
		$current_user_id = wpl_users::get_cur_user_id();
        $where = array();
		
		/** set page if start var passed **/
		$this->page_number = ($start/$limit)+1;
		wpl_request::setVar('wplpage', $this->page_number);
		
        $this->model = new wpl_property;
		
        /** detect kind **/
        $this->kind = wpl_request::getVar('kind', 0);
        if(!in_array($this->kind, wpl_flex::get_valid_kinds()))
		{
			/** import message tpl **/
			$this->message = __('Invalid Request!', WPL_TEXTDOMAIN);
			parent::render($this->tpl_path, 'message');
            
            return false;
		}
        
        /** Access **/
        $access = true;
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('listing_manager_access', array('kind'=>$this->kind, 'user_id'=>$current_user_id)));
        
        if(!$access)
		{
			/** import message tpl **/
			$this->message = __("You don't have access to this page!", WPL_TEXTDOMAIN);
			parent::render($this->tpl_path, 'message');
            
            return false;
		}
        
        /** load user properties **/
		if(!wpl_users::is_administrator($current_user_id))
		{
			$where['sf_select_user_id'] = $current_user_id;
		}
        
        /** Multisite **/
		if(wpl_global::is_multisite())
		{
            $current_blog_id = wpl_global::get_current_blog_id();
			$where['sf_fschild'] = $current_blog_id;
		}
        
        $this->kind_label = wpl_flex::get_kind_label($this->kind);
        $where['sf_select_kind'] = $this->kind;
        
		/** Add search conditions to the where **/
        $vars = array_merge(wpl_request::get('POST'), wpl_request::get('GET'));
		$where = array_merge($vars, $where);
		
        $this->model->start($start, $limit, $orderby, $order, $where, $this->kind);
        
        $query = $this->model->query();
        $properties = $this->model->search($query);
        $this->model->finish();
		
		/** get the number of all properties according to our query **/
        $properties_count = $this->model->get_properties_count();
		
        /** set pagination according to the number of items and limit **/
        $this->pagination = wpl_pagination::get_pagination($properties_count, $limit);
		$plisting_fields = $this->model->get_plisting_fields();
        
        $wpl_properties = array();
		foreach($properties as $property)
		{
			$wpl_properties[$property->id] = $this->model->full_render($property->id, $plisting_fields, $property);
		}
		
        $this->wpl_properties = $wpl_properties;
        $this->client = wpl_global::get_client();
        
        if($this->client)
        {
            $this->backend = true;
            $this->frontend = false;
            
            $this->add_link = wpl_global::add_qs_var('kind', $this->kind, wpl_global::get_wpl_admin_menu('wpl_admin_add_listing'));
        }
        else
        {
            $this->backend = false;
            $this->frontend = true;
            
            $this->add_link = wpl_global::add_qs_var('kind', $this->kind, wpl_global::add_qs_var('wplmethod', 'wizard'));
        }
        
        return true;
    }
    
    public function generate_search_form()
    {
        $this->property_types = wpl_global::get_property_types();
        $this->listings = wpl_global::get_listings();
        $this->users = wpl_users::get_wpl_users();
        
        parent::render($this->tpl_path, 'internal_search_form');
    }
    
    protected function include_tabs()
    {
        $this->kinds = wpl_flex::get_kinds();
        
        /** include the layout **/
		parent::render($this->tpl_path, 'internal_tabs');
    }
}