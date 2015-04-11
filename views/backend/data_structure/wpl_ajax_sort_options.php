<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.pagination');
_wpl_import('libraries.sort_options');

class wpl_data_structure_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.data_structure.tmpl';
	public $tpl;
	
	public function display()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$function = wpl_request::getVar('wpl_function');
		
		if($function == 'sort_options')
		{
			$sort_ids = wpl_request::getVar('sort_ids');
			$this->sort_options($sort_ids);
		}
		elseif($function == 'sort_options_enabled_state_change')
		{
			$id = wpl_request::getVar('id');
			$enabled_status = wpl_request::getVar('enabled_status');
            
			$this->update('wpl_sort_options', $id, 'enabled', $enabled_status);
		}
        elseif($function == 'save_sort_option')
        {
            $id = wpl_request::getVar('id');
			$sort_name = wpl_request::getVar('sort_name', '');
            
            $this->update('wpl_sort_options', $id, 'name', $sort_name);
        }
	}
	
	/**
	*{tablename,id,key,value of key}
	* this function call update function in units library and change value of a field
	**/
	private function update($table = 'wpl_sort_options', $id, $key, $value = '')
	{
		$res = wpl_sort_options::update($table, $id, $key, $value);
		
		$res = (int) $res;
		$message = $res ? __('Operation was successful.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);		
		echo json_encode($response);
		exit;
	}
	
	private function sort_options($sort_ids)
	{
		if(trim($sort_ids) == '') $sort_ids = wpl_request::getVar('sort_ids');
		wpl_sort_options::sort_options($sort_ids);		
		exit;
	}
}