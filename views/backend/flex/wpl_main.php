<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.flex');

class wpl_flex_controller extends wpl_controller
{
	var $tpl_path = 'views.backend.flex.tmpl';
	var $tpl;
	
	public function home()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$this->kind = trim(wpl_request::getVar('sf_select_kind')) != '' ? wpl_request::getVar('sf_select_kind') : 0;
		
		$this->field_categories = wpl_flex::get_categories(0, $this->kind);
		$this->kind_label = wpl_flex::get_kind_label($this->kind);
		$this->dbst_types = wpl_flex::get_dbst_types(1, $this->kind);
		$this->new_dbst_id = wpl_flex::get_new_dbst_id();
		
		/** import tpl **/
		parent::render($this->tpl_path, $this->tpl);
	}
	
	protected function generate_slide($category)
	{
		$tpl = 'internal_slide';
		
		$this->fields = wpl_flex::get_fields($category->id, 0, $this->kind);
		$this->field_category = $category;
		
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
	
	protected function generate_sidebar($sidebar)
	{
		$tpl = 'internal_sidebar'.$sidebar;
		
		$this->sidebar = $sidebar;
		
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
}