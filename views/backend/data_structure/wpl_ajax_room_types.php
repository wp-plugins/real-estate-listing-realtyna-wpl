<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.pagination');
_wpl_import('libraries.file');
_wpl_import('libraries.room_types');

class wpl_data_structure_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.data_structure.tmpl';
	public $tpl;
	
	public function display()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$function = wpl_request::getVar('wpl_function');
		
		if($function == 'sort_rooms')
		{
			$sort_ids = wpl_request::getVar('sort_ids');
			$this->sort_rooms($sort_ids);
		}
		if($function == 'generate_new_room_type')
		{
			$this->generate_new_room_type();
		}
		elseif($function == 'room_types_enabled_state_change')
		{
			$id = wpl_request::getVar('id');
			$enabled_status = wpl_request::getVar('enabled_status');			
			$this->update('wpl_room_types', $id, 'enabled', $enabled_status);
		}
		elseif($function == 'remove_room_type')
		{
			/** check permission **/
			wpl_global::min_access('administrator');
			
			$room_type_id = wpl_request::getVar('room_type_id');
			$confirmed = wpl_request::getVar('wpl_confirmed', 0);
			
			$this->remove_room_type($room_type_id, $confirmed);
		}
		elseif($function == 'change_room_type_name')
		{
			$id = wpl_request::getVar('id');
			$name = wpl_request::getVar('name');			
			$this->update('wpl_room_types', $id, 'name', $name);
		}
		elseif($function == 'save_room_type')
		{
			$name = wpl_request::getVar('name');
			$this->save_room_type($name);
		}		
	}	
	
	/**
	*{tablename,id,key,value of key}
	**/
	private function update($table = 'wpl_room_types', $id, $key, $value = '')
	{
		$res = wpl_room_types::update($table, $id, $key, $value);
		
		$res = (int) $res;
		$message = $res ? __('Operation was successful.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);		
		echo json_encode($response);
		exit;
	}
	
	private function remove_room_type($id, $confirmed = 0)
	{
		if($confirmed) $res = wpl_room_types::remove_room_type($id);
		else $res = false;
		
		
		$res = (int) $res;
		$message = $res ? __('Room type removed from WPL successfully.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
	
	private function sort_rooms($sort_ids)
	{
		if(trim($sort_ids) == '') $sort_ids = wpl_request::getVar('sort_ids');
		wpl_room_types::sort_room_types($sort_ids);
		exit;
	}

	private function generate_new_room_type()
	{
		parent::render($this->tpl_path, 'internal_new_room_type');
		exit;
	}
	
	private function save_room_type($name)
	{
		$res = wpl_room_types::save_room_type($name);
		
		$res = (int) $res;
		$message = $res ? __('Operation was successful.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
	
}
