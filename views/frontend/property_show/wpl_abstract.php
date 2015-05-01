<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.property');
_wpl_import('libraries.render');
_wpl_import('libraries.items');
_wpl_import('libraries.activities');

abstract class wpl_property_show_controller_abstract extends wpl_controller
{
	public $tpl_path = 'views.frontend.property_show.tmpl';
	public $tpl;
	public $wpl_properties;
	public $pid;
	public $kind;
	public $property;
	public $model;
	public $pshow_fields;
	
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
			return parent::render($this->tpl_path, 'message', false, true);
		}
		
        $this->tpl = wpl_request::getVar('tpl', 'default');
        
		/** property listing model **/
		$this->model = new wpl_property;
		$this->pid = wpl_request::getVar('pid', 0);
		
		$listing_id = wpl_request::getVar('mls_id', 0);
		if(trim($listing_id))
        {
            $this->pid = wpl_property::pid($listing_id);
            wpl_request::setVar('pid', $this->pid);
        }
		
		$property = $this->model->get_property_raw_data($this->pid);
		
		/** no property found **/
		if(!$property or $property['finalized'] == 0 or $property['confirmed'] == 0 or $property['deleted'] == 1 or $property['expired'] >= 1)
		{
			/** import message tpl **/
			$this->message = __("No property found or it's not available now!", WPL_TEXTDOMAIN);
			return parent::render($this->tpl_path, 'message', false, true);
		}
		
        /** global settings **/
		$this->settings = wpl_settings::get_settings();
        
		$this->pshow_fields = $this->model->get_pshow_fields('', $property['kind']);
		$this->pshow_categories = wpl_flex::get_categories('', '', " AND `enabled`>='1' AND `kind`='".$property['kind']."' AND `pshow`='1'");
		$wpl_properties = array();
        
		/** define current index **/
		$wpl_properties['current']['data'] = (array) $property;
		$wpl_properties['current']['raw'] = (array) $property;
        
        $find_files = array();
		$rendered_fields = $this->model->render_property($property, $this->pshow_fields, $find_files, true);
        
		$wpl_properties['current']['rendered_raw'] = $rendered_fields['ids'];
        $wpl_properties['current']['materials'] = $rendered_fields['columns'];
		
		foreach($this->pshow_categories as $pshow_category)
		{
			$pshow_cat_fields = $this->model->get_pshow_fields($pshow_category->id, $property['kind']);
			$wpl_properties['current']['rendered'][$pshow_category->id]['self'] = (array) $pshow_category;
			$wpl_properties['current']['rendered'][$pshow_category->id]['data'] = $this->model->render_property($property, $pshow_cat_fields);
		}
		
		$wpl_properties['current']['items'] = wpl_items::get_items($this->pid, '', $property['kind'], '', 1);
		/** property location text **/ $wpl_properties['current']['location_text'] = $this->model->generate_location_text((array) $property);
		/** property full link **/ $wpl_properties['current']['property_link'] = $this->model->get_property_link((array) $property);
        /** property page title **/ $wpl_properties['current']['property_page_title'] = $this->model->update_property_page_title($property);
        /** property title **/ $wpl_properties['current']['property_title'] = $this->model->update_property_title($property);
		
		/** apply filters (This filter must place after all proccess) **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('property_listing_after_render', array('wpl_properties'=>$wpl_properties)));
		
		$this->wpl_properties = $wpl_properties;
		$this->kind = $property['kind'];
		$this->property = $wpl_properties['current'];
		
		/** updating the visited times and etc **/
		wpl_property::property_visited($this->pid);
		
        /** trigger event **/
		wpl_global::event_handler('property_show', array('id'=>$this->pid));
        
		/** import tpl **/
        $this->tpl = wpl_flex::get_kind_tpl($this->tpl_path, $this->tpl, $this->kind);
		return parent::render($this->tpl_path, $this->tpl, false, true);
	}
}