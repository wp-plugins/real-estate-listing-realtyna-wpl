<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.pagination');
_wpl_import('libraries.property_types');
_wpl_import('libraries.notices');

class wpl_data_structure_controller extends wpl_controller
{
	var $tpl_path = 'views.backend.data_structure.tmpl';
	var $tpl;
	
	public function display()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$function = wpl_request::getVar('wpl_function');
		
		if($function == 'generate_new_page')
		{
			self::generate_new_page();
		}
		elseif($function == 'set_enabled_property_type')
		{
			$property_type_id = wpl_request::getVar('property_type_id');
			$enabeled_status = wpl_request::getVar('enabeled_status');
			
			self::set_enabled_property_type($property_type_id, $enabeled_status);
		}
		elseif($function == 'remove_property_type')
		{
			$property_type_id = wpl_request::getVar('property_type_id');
			$confirmed = wpl_request::getVar('wpl_confirmed', 0);
			
			self::remove_property_type($property_type_id, $confirmed);
		}
		elseif($function == 'generate_edit_page')
		{
			$property_type_id = wpl_request::getVar('property_type_id');
			self::generate_edit_page($property_type_id);
		}
		elseif($function == 'sort_property_types')
		{
			$sort_ids = wpl_request::getVar('sort_ids');
			self::sort_property_types($sort_ids);
		}
        elseif($function == 'save_property_type')
		{
			self::save_property_type();
		}
		 elseif($function == 'insert_property_type')
		{
			self::insert_property_type();
		}
	}
	
	private function sort_property_types($sort_ids)
	{
		if(trim($sort_ids) == '') $sort_ids = wpl_request::getVar('sort_ids');
		wpl_property_types::sort_property_types($sort_ids);
		exit;
	}
	
	private function remove_property_type($property_type_id, $confirmed = 0)
	{
		if($confirmed) $res = wpl_property_types::remove_property_type($property_type_id);
		else $res = false;
		
		$res = (int) $res;
		$message = $res ? __('Property type removed from WPL successfully.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
	
	private function set_enabled_property_type($property_type_id, $enabeled_status)
	{
		$res = wpl_property_types::update($property_type_id, 'enabled', $enabeled_status);
		
		$res = (int) $res;
		$message = $res ? __('Operation was successful.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
	
	private function generate_edit_page($property_type_id = '')
	{
		if(trim($property_type_id) == '') $property_type_id = wpl_request::getVar('property_type_id');
		
		$this->property_type_id = $property_type_id;
		$this->property_type_data = wpl_property_types::get_property_type($this->property_type_id);
		$this->property_types_category = wpl_property_types::get_property_types_category();
		
		parent::render($this->tpl_path, 'internal_edit_property_types');
		exit;
	}
	
	private function generate_new_page()
	{
		$this->property_type_id = 10000;
		$this->property_type_data = wpl_property_types::get_property_type($this->property_type_id);
		$this->property_types_category = wpl_property_types::get_property_types_category();
		
		parent::render($this->tpl_path, 'internal_edit_property_types');
		exit;
	}
     private function insert_property_type()
    {
		$parent = wpl_request::getVar('parent');
		$name = wpl_request::getVar('name');
		$res = wpl_property_types::insert_property_type($parent,$name);
		$res = (int) $res;
		
		if($res>0) 
		{
			$res = 1;
		}
		else
		{
			 $res = 0;
		}
		$message = $res ? __('Saved.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
    private function save_property_type()
    {
		
		
		$key = wpl_request::getVar('key');
		$value = wpl_request::getVar('value');
		$id = wpl_request::getVar('property_type_id');
		
		$query = "UPDATE `#__wpl_property_types` SET `$key`='$value' WHERE id='$id'";
		$res = wpl_db::q($query);
		
		$res = (int) $res;
		$message = $res ? __('Saved.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
    }
}