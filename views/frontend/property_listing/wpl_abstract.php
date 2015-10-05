<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.pagination');
_wpl_import('libraries.property');
_wpl_import('libraries.render');
_wpl_import('libraries.items');
_wpl_import('libraries.activities');
_wpl_import('libraries.settings');

abstract class wpl_property_listing_controller_abstract extends wpl_controller
{
	public $tpl_path = 'views.frontend.property_listing.tmpl';
	public $tpl;
	public $wpl_properties;
	public $model;
	public $kind;
	
	public function display($instance = array())
	{
        /** check access **/
		if(!wpl_users::check_access('propertylisting'))
		{
			/** import message tpl **/
			$this->message = __("You don't have access to this part!", WPL_TEXTDOMAIN);
			return parent::render($this->tpl_path, 'message', false, true);
		}
        
        $this->tpl = wpl_request::getVar('tpl', 'default');
        $this->method = wpl_request::getVar('wplmethod', NULL);
        
        /** global settings **/
		$this->settings = wpl_settings::get_settings();
		
		/** listing settings **/
        $this->page_number = wpl_request::getVar('wplpage', 1, '', true);
		$this->limit = wpl_request::getVar('limit', $this->settings['default_page_size']);
		$this->start = wpl_request::getVar('start', (($this->page_number-1)*$this->limit), '', true);
		$this->orderby = wpl_request::getVar('wplorderby', $this->settings['default_orderby'], '', true);
		$this->order = wpl_request::getVar('wplorder', $this->settings['default_order'], '', true);
        
        /** Set Property CSS class **/
        $this->property_css_class = wpl_request::getVar('wplpcc', NULL);
        if(!$this->property_css_class) $this->property_css_class = wpl_request::getVar('wplpcc', 'grid_box', 'COOKIE');
        
        $this->property_css_class_switcher = wpl_request::getVar('wplpcc_switcher', '1');
        $this->property_listview = wpl_request::getVar('wplplv', '1'); #Show listview or not
        
        /** RSS Feed Setting **/
        $this->listings_rss_enabled = isset($this->settings['listings_rss_enabled']) ? $this->settings['listings_rss_enabled'] : 0;

        /** detect kind **/
		$this->kind = wpl_request::getVar('kind', 0);
        if(!$this->kind) $this->kind = wpl_request::getVar('sf_select_kind', 0);
        
		if(!in_array($this->kind, wpl_flex::get_valid_kinds()))
		{
			/** import message tpl **/
			$this->message = __('Invalid Request!', WPL_TEXTDOMAIN);
			return parent::render($this->tpl_path, 'message', false, true);
		}
        
        /** pagination types **/
        $this->wplpagination = wpl_request::getVar('wplpagination', 'normal', '', true);
        wpl_request::setVar('wplpagination', $this->wplpagination);
        
		/** property listing model **/
		$this->model = new wpl_property;
		
		/** set page if start var passed **/
		$this->page_number = ($this->start/$this->limit)+1;
		wpl_request::setVar('wplpage', $this->page_number);
		
		$where = array('sf_select_confirmed'=>1, 'sf_select_finalized'=>1, 'sf_select_deleted'=>0, 'sf_select_expired'=>0, 'sf_select_kind'=>$this->kind);
		
        /** Add search conditions to the where **/
        $vars = array_merge(wpl_request::get('POST'), wpl_request::get('GET'));
		$where = array_merge($vars, $where);
        
        /** Save Search in SESSION **/
        wpl_session::set('wpl_listing_criteria', $where);
        wpl_session::set('wpl_listing_orderby', $this->orderby);
        wpl_session::set('wpl_listing_order', $this->order);
        
		/** start search **/
		$this->model->start($this->start, $this->limit, $this->orderby, $this->order, $where, $this->kind);
		$this->model->total = $this->model->get_properties_count();
		
		/** validation for page_number **/
		$this->total_pages = ceil($this->model->total / $this->limit);
		if($this->page_number <= 0 or ($this->page_number > $this->total_pages)) $this->model->start = 0;
		
		/** run the search **/
		$query = $this->model->query();
		$properties = $this->model->search();
        
		/** finish search **/
		$this->model->finish();
		
		$plisting_fields = $this->model->get_plisting_fields('', $this->kind);
		
		$wpl_properties = array();
		foreach($properties as $property)
		{
			$wpl_properties[$property->id] = $this->model->full_render($property->id, $plisting_fields, $property);
		}
		
		/** define current index **/
		$wpl_properties['current'] = array();
		
		/** apply filters (This filter must place after all proccess) **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('property_listing_after_render', array('wpl_properties'=>$wpl_properties)));
		
		$this->pagination = wpl_pagination::get_pagination($this->model->total, $this->limit, true, $this->wplraw);
		$this->wpl_properties = $wpl_properties;
        
        if($this->wplraw and $this->method == 'get_markers')
        {
            $markers = array('markers'=>$this->model->render_markers($wpl_properties), 'total'=>$this->model->total);
            echo json_encode($markers);
            exit;
        }
        elseif($this->wplraw and $this->method == 'get_listings')
        {
        	if($this->return_listings) return $wpl_properties;
        	else
            {
                echo json_encode($wpl_properties);
                exit;
            }
        }
        
		/** import tpl **/
        $this->tpl = wpl_flex::get_kind_tpl($this->tpl_path, $this->tpl, $this->kind);
		return parent::render($this->tpl_path, $this->tpl, false, true);
	}
}