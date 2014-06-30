<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.property');
_wpl_import('libraries.pagination');
_wpl_import('libraries.settings');
_wpl_import('libraries.items');
_wpl_import('libraries.images');
_wpl_import('libraries.activities');

class wpl_listings_controller extends wpl_controller {

    var $tpl_path = 'views.backend.listings.tmpl';
    var $tpl;

    public function manager($instance = array())
    {
		/** check access **/
		if(!wpl_users::check_access('propertymanager'))
		{
			/** import message tpl **/
			$this->message = __("You don't have access to this part!", WPL_TEXTDOMAIN);
			return parent::render($this->tpl_path, 'message');
		}
		
        $this->init_page();  
        $this->tpl = 'manager';
		
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
		
		/** load user properties **/
		if(!wpl_users::is_administrator($current_user_id))
		{
			$where['sf_select_user_id'] = $current_user_id;
		}
		
        $this->model->start($start, $limit, $orderby, $order, $where);
        $query = $this->model->query();
        $properties = $this->model->search($query);
        $this->model->finish();
		
		/** get the number of all properties according to our query **/
        $properties_count = $this->model->get_properties_count();
		
        /** set pagination according to the number of items and limit **/
        $this->pagination = wpl_pagination::get_pagination($properties_count, $limit);
		
        $wpl_properties = array();
        foreach($properties as $property)
        {
            $raw_data = $this->model->get_property_raw_data($property->id);
            $rendered = json_decode($raw_data['rendered'], true);

            $wpl_properties[$property->id]['data'] = (array) $property;

            /** render data **/
            $wpl_properties[$property->id]['rendered'] = $rendered['rendered'];

            $wpl_properties[$property->id]['items'] = wpl_items::get_items($property->id, '', $property->kind, '', 1);
            $wpl_properties[$property->id]['raw'] = $raw_data;

            /** location text **/
            if($rendered['location_text']) $wpl_properties[$property->id]['location_text'] = $rendered['location_text'];
            else $wpl_properties[$property->id]['location_text'] = $this->model->generate_location_text($raw_data);
        }
		
        $this->wpl_properties = $wpl_properties;
    }
    
    public function generate_search_form()
    {
        $this->property_types = wpl_global::get_property_types();
        $this->listings = wpl_global::get_listings();
        $this->users = wpl_users::get_wpl_users();
        
        parent::render($this->tpl_path, 'search_form');
    }
}