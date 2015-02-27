<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.flex');

class wpl_flex_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.flex.tmpl';
	public $tpl;
	
	public function home()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$this->kind = trim(wpl_request::getVar('kind')) != '' ? wpl_request::getVar('kind') : 0;
        
        if(!in_array($this->kind, wpl_flex::get_valid_kinds()))
        {
            $this->message = __('Invalid Kind!', WPL_TEXTDOMAIN);
            
            /** import tpl **/
            return parent::render($this->tpl_path, 'message');
        }
        
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
		
		$this->fields = wpl_flex::get_fields($category->id, 0, $this->kind, 'flex', 1);
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
    
    protected function include_tabs()
    {
        $this->kinds = wpl_flex::get_kinds(NULL);
        
        /** include the layout **/
		parent::render($this->tpl_path, 'internal_tabs');
    }
}