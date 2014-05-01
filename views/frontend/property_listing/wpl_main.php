<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.pagination');
_wpl_import('libraries.property');
_wpl_import('libraries.render');
_wpl_import('libraries.items');
_wpl_import('libraries.activities');
_wpl_import('libraries.settings');

class wpl_property_listing_controller extends wpl_controller
{
	var $tpl_path = 'views.frontend.property_listing.tmpl';
	var $tpl;
	var $wpl_properties;
	var $model;
	var $kind;
	
	public function display($instance = array())
	{
		/** property listing model **/
		$this->model = new wpl_property;
		
		/** global settings **/
		$settings = wpl_settings::get_settings();
		
		/** listing settings **/
		$this->page_number = wpl_request::getVar('wplpage', 1, '', true);
		$limit = wpl_request::getVar('limit', $settings['default_page_size']);
		$start = wpl_request::getVar('start', (($this->page_number-1)*$limit), '', true);
		$orderby = wpl_request::getVar('wplorderby', $settings['default_orderby'], '', true);
		$order = wpl_request::getVar('wplorder', $settings['default_order'], '', true);
		
		/** set page if start var passed **/
		$this->page_number = ($start/$limit)+1;
		wpl_request::setVar('wplpage', $this->page_number);
		
		/** detect kind **/
		$this->kind = wpl_request::getVar('kind', 0);
		if(!in_array($this->kind, wpl_flex::get_kinds()))
		{
			/** import message tpl **/
			$this->message = __('Invalid Request!', WPL_TEXTDOMAIN);
			return parent::render($this->tpl_path, 'message', false, true);
		}
		
		$where = array('sf_select_confirmed'=>1, 'sf_select_finalized'=>1, 'sf_select_deleted'=>0, 'sf_select_kind'=>$this->kind);
		
		/** start search **/
		$this->model->start($start, $limit, $orderby, $order, $where);
		$this->model->total = $this->model->get_properties_count();
		
		/** validation for page_number **/
		$max_page = ceil($this->model->total / $limit);
		if($this->page_number <= 0 or ($this->page_number > $max_page)) $this->model->start = 0;
		
		/** run the search **/
		$query = $this->model->query();
		$properties = $this->model->search();
		
		/** finish search **/
		$this->model->finish();
		
		$plisting_fields = $this->model->get_plisting_fields();
		
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
		
		$this->pagination = wpl_pagination::get_pagination($this->model->total, $limit, true);
		$this->wpl_properties = $wpl_properties;
		
		/** import tpl **/
		return parent::render($this->tpl_path, $this->tpl, false, true);
	}
}