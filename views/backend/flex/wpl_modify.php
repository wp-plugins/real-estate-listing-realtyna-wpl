<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.flex');

class wpl_flex_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.flex.tmpl';
	public $tpl;
	
	public function display()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$function = wpl_request::getVar('wpl_function');
		
		if($function == 'generate_modify_page')
		{
			$field_id = wpl_request::getVar('field_id', 0);
			$field_type = wpl_request::getVar('field_type', 'text');
			$kind = wpl_request::getVar('kind', 0);
			
			self::generate_modify_page($field_type, $field_id, $kind);
		}
	}
	
	private function generate_modify_page($field_type, $field_id, $kind = 0)
	{
		if(trim($field_type) == '') $field_type = wpl_request::getVar('field_type', 0);
		if(trim($field_id) == '') $field_id = wpl_request::getVar('field_id', 0);
		
		$this->field_type = $field_type;
		$this->field_id = $field_id;
		$this->kind = $kind;
		
		parent::render($this->tpl_path, 'internal_modify');
		exit;
	}
}