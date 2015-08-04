<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.units');
_wpl_import('libraries.sort_options');
_wpl_import('libraries.room_types');

class wpl_data_structure_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.data_structure.tmpl';
	public $tpl;
	
	public function home()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$possible_orders = array('index', 'id', 'title');
		
		$this->orderby = in_array(wpl_request::getVar('orderby'), $possible_orders) ? wpl_request::getVar('orderby') : $possible_orders[0];
		$this->order = in_array(strtoupper(wpl_request::getVar('order')), array('ASC','DESC')) ? wpl_request::getVar('order') : 'ASC';
		
		/** import tpl **/
		parent::render($this->tpl_path, $this->tpl);
	}
	
	public function generate_property_types()
	{
		$tpl = 'internal_property_types';
		$this->property_types = wpl_property_types::get_property_types();
		
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
	
	public function generate_sort_options()
	{
		$tpl = 'internal_sort_options';
		$this->sort_options = wpl_sort_options::get_sort_options('0', 0);
		
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
	
	public function generate_room_types()
	{
		$tpl = 'internal_room_types';
		$this->room_types = wpl_room_types::get_room_types("","");
		$folder = WPL_ABSPATH . 'assets' . DS . 'img' . DS . 'rooms';
		$this->icons = wpl_global::get_icons($folder);
		
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
	
	public function generate_listing_types()
	{
		$tpl = 'internal_listing_types';
		$this->listing_types = wpl_listing_types::get_listing_types();
		$this->listing_gicons = wpl_listing_types::get_map_icons();
		$this->get_caption_imgs = wpl_listing_types::get_caption_images();
		
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
	
	public function generate_unit_manager()
	{
		$tpl = 'internal_unit_manager_default';
		$this->unit_types = wpl_units::get_unit_types();
		$this->units = wpl_units::get_units(4, '', '');
		
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
	
	public function generate_currency_page()
	{
		$this->units = wpl_units::get_units(4, '', '');
		
		/** import tpl **/
		parent::render($this->tpl_path, 'internal_unit_manager_currency');
	}
}
