<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.locations');
_wpl_import('libraries.pagination');

class wpl_location_manager_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.location_manager.tmpl';
	public $tpl;
	
	public function display()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$function = wpl_request::getVar('wpl_function');
		
		if($function == 'generate_modify_page')
		{
			$level = wpl_request::getVar('level');
			$parent = wpl_request::getVar('parent');
			$location_id = wpl_request::getVar('location_id');
			
			$this->generate_modify_page($level, $parent, $location_id);
		}
		elseif($function == 'set_enabled_location')
		{
			$location_id = wpl_request::getVar('location_id');
			$enabeled_status = wpl_request::getVar('enabeled_status');
			
			$this->set_enabled_location($location_id, $enabeled_status);
		}
		elseif($function == 'save_location')
		{
			$name = wpl_request::getVar('name');
            $abbr = wpl_request::getVar('abbr');
			$level = wpl_request::getVar('level');
			$parent = wpl_request::getVar('parent');
			$location_id = wpl_request::getVar('location_id');
			
			$this->save_location($name, $abbr, $level, $parent, $location_id);
		}
		elseif($function == 'delete_location')
		{
			$level = wpl_request::getVar('level');
			$location_id = wpl_request::getVar('location_id');
			
			$this->delete_location($level, $location_id);
		}
		elseif($function == 'generate_params_page')
		{
			$level = wpl_request::getVar('level');
			$location_id = wpl_request::getVar('location_id');
			
			$this->generate_params_page($level, $location_id);
		}
	}
	
	private function save_location($name, $abbr, $level, $parent, $location_id = '')
	{
		/** edit method **/
		if(trim($location_id) != '') $res = wpl_locations::edit_location($name, $abbr, $level, $location_id);
		/** add method **/
		else $res = wpl_locations::add_location($name, $abbr, $level, $parent);
		
		/** trigger event **/
		wpl_global::event_handler('location_modified', array('name'=>$name, 'level'=>$level, 'parent'=>$parent, 'location_id'=>$location_id));
		
		$res = 1;
		$message = $res ? __('Location saved.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
	
	private function set_enabled_location($location_id, $enabeled_status)
	{
		$res = wpl_locations::update_location($location_id, 'enabled', $enabeled_status, 1);
		
		$res = (int) $res;
		$message = $res ? __('Operation was successful.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
	
	private function generate_modify_page($level = '1', $parent = '0', $location_id = '')
	{
		if(trim($level) == '') $level = wpl_request::getVar('level');
		if(trim($parent) == '') $parent = wpl_request::getVar('parent');
		if(trim($location_id) == '') $location_id = wpl_request::getVar('location_id');
		
		$this->location_data = '';
		$this->level = $level;
		$this->parent = $parent;
		$this->location_id = $location_id;
		
		/** get location data for edit **/
		if(trim($location_id) != '') $this->location_data = wpl_locations::get_location($location_id, $level);
		
		parent::render($this->tpl_path, 'edit');
		exit;
	}
	
	private function generate_params_page($level, $location_id)
	{
		$params = array('element_class'=>'wpl_params_cnt', 'js_function'=>'wpl_save_params', 'id'=>$location_id, 'table'=>'wpl_location'.$level, 'html_path_message'=>'dont_show', 'close_fancybox'=>true);
		wpl_global::import_activity('params:default', '', $params);
		exit;
	}
	
	private function delete_location($level, $location_id)
	{
		/** trigger event **/
		wpl_global::event_handler('location_deleted', array('level'=>$level, 'location_id'=>$location_id));

		$res = wpl_locations::delete_location($location_id, $level, true);
		
		$res = $res ? 1 : 0;
		$message = $res ? __('Location(s) Deleted.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
}