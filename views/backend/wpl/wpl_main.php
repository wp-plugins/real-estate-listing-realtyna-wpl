<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_wpl_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.wpl.tmpl';
	public $tpl;
	
	public function admin_home()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		$this->submenus = wpl_global::get_menus('submenu', 'backend', 1, 1);
		$this->settings = wpl_global::get_settings();
		
		/** import tpl **/
		parent::render($this->tpl_path, $this->tpl);
	}
	
	public function generate_addons()
	{
		$tpl = 'internal_addons';
		$this->addons = wpl_db::select("SELECT * FROM `#__wpl_addons` ORDER BY `id` ASC", 'loadAssocList');
		
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
	
	public function not_installed_addons()
	{
		$tpl = 'internal_ni_addons';
		
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
	
	public function support()
	{
		$tpl = 'internal_support';
		
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
	
	public function announce()
	{
		$tpl = 'internal_announce';
		
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
    
    public function statistic()
	{
		$tpl = 'internal_statistic';
		
		/** import tpl **/
		parent::render($this->tpl_path, $tpl);
	}
}