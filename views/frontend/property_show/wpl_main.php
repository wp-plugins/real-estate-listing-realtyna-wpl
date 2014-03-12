<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.property');
_wpl_import('libraries.render');
_wpl_import('libraries.items');
_wpl_import('libraries.activities');

class wpl_property_show_controller extends wpl_controller
{
	var $tpl_path = 'views.frontend.property_show.tmpl';
	var $tpl;
	var $wpl_properties;
	var $pid;
	var $kind;
	var $property;
	var $model;
	var $pshow_fields;
	
	public function display($instance = array())
	{
		/** do cronjobs **/
		_wpl_import('libraries.events');
		wpl_events::do_cronjobs();
		
		/** check access **/
		if(!wpl_users::check_access('propertyshow'))
		{
			/** import message tpl **/
			$this->message = __("You don't have access to this part!", WPL_TEXTDOMAIN);
			return parent::display($this->tpl_path, 'message', false, true);
		}
		
		/** property listing model **/
		$this->model = new wpl_property;
		$this->pid = wpl_request::getVar('pid', 0);
		
		$listing_id = wpl_request::getVar('mls_id', 0);
		if(trim($listing_id)) $this->pid = wpl_property::pid($listing_id);
		
		$property = $this->model->get_property_raw_data($this->pid);
		
		/** no property found **/
		if(!$property or $property['finalized'] == 0 or $property['confirmed'] == 0 or $property['deleted'] == 1)
		{
			/** import message tpl **/
			$this->message = __("No property found or it's not available now!", WPL_TEXTDOMAIN);
			return parent::display($this->tpl_path, 'message', false, true);
		}
		
		$this->pshow_fields = $this->model->get_pshow_fields('', $property['kind']);
		$this->pshow_categories = wpl_flex::get_categories('', '', " AND `enabled`>='1' AND `kind`='".$property['kind']."' AND `pshow`='1'");
		$wpl_properties = array();
		
		/** define current index **/
		$wpl_properties['current']['data'] = (array) $property;
		$wpl_properties['current']['raw'] = (array) $property;
		$wpl_properties['current']['rendered_raw'] = $this->model->render_property($property, $this->pshow_fields);
		
		foreach($this->pshow_categories as $pshow_category)
		{
			$pshow_cat_fields = $this->model->get_pshow_fields($pshow_category->id, $property['kind']);
			$wpl_properties['current']['rendered'][$pshow_category->id]['self'] = (array) $pshow_category;
			$wpl_properties['current']['rendered'][$pshow_category->id]['data'] = $this->model->render_property($property, $pshow_cat_fields);
		}
		
		$wpl_properties['current']['items'] = wpl_items::get_items($this->pid, '', $property['kind'], '', 1);
		/** property location text **/ $wpl_properties['current']['location_text'] = $this->model->generate_location_text((array) $property);
		/** property full link **/ $wpl_properties['current']['property_link'] = $this->model->get_property_link((array) $property);
		
		/** apply filters (This filter must place after all proccess) **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('property_listing_after_render', array('wpl_properties'=>$wpl_properties)));
		
		$this->wpl_properties = $wpl_properties;
		$this->kind = $property['kind'];
		$this->property = $wpl_properties['current'];
		
		/** updating the visited times and etc **/
		wpl_property::property_visited($this->pid);
		
		/** import tpl **/
		return parent::display($this->tpl_path, $this->tpl, false, true);
	}
}