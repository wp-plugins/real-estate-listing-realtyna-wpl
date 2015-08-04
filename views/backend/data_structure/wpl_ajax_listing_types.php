<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.pagination');

class wpl_data_structure_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.data_structure.tmpl';
	public $tpl;
	
	public function display()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$function = wpl_request::getVar('wpl_function');
		
		if($function == 'generate_new_page')
		{
			$this->generate_new_page();
		}
		elseif($function == 'generate_delete_page')
		{
			$this->generate_delete_page();
		}
		elseif($function == 'set_enabled_listing_type')
		{
			$listing_type_id = wpl_request::getVar('listing_type_id');
			$enabled_status = wpl_request::getVar('enabled_status');
			
			$this->set_enabled_listing_type($listing_type_id, $enabled_status);
		}
		elseif($function == 'remove_listing_type')
		{
			$listing_type_id = wpl_request::getVar('listing_type_id');
			$confirmed = wpl_request::getVar('wpl_confirmed', 0);
			
			$this->remove_listing_type($listing_type_id, $confirmed);
		}
		elseif($function == 'generate_edit_page')
		{
			$listing_type_id = wpl_request::getVar('listing_type_id');
			$this->generate_edit_page($listing_type_id);
		}
		elseif($function == 'sort_listing_types')
		{
			$sort_ids = wpl_request::getVar('sort_ids');
			$this->sort_listing_types($sort_ids);
		}
		elseif($function == 'gicon_delete')
		{
			$icon = wpl_request::getVar('icon');
			$this->gicon_delete($icon);
		}
		elseif($function == 'gicon_upload_file')
		{
			$this->gicon_upload_file();
		}
        elseif($function == 'save_listing_type')
        {
            $this->save_listing_type();
        }
		elseif($function == 'insert_listing_type')
		{
			$this->insert_listing_type();
		}
		elseif($function == 'can_remove_listing_type')
		{
			$this->can_remove_listing_type();
		}
        elseif($function == 'purge_related_property')
		{
			$this->purge_related_property();
		}
		elseif($function == 'assign_related_properties')
		{
			$this->assign_related_properties();
		}
	}
	
	private function gicon_upload_file()
	{
		$fileElementName = 'wpl_gicon_file';
		$file = wpl_request::getVar($fileElementName, '','FILES');
		
		$ext_array = array('jpg','png','gif','jpeg');
		$error = "";
		$msg = "";
		
		if((!empty($file['error'])) or (empty($file['tmp_name']) or $file['tmp_name'] == 'none'))
		{
			$error = __("An error occurred uploading your file!", WPL_TEXTDOMAIN);
		}
		else 
		{
			$extention = strtolower(wpl_file::getExt($file['name']));
			$name = strtolower(wpl_file::stripExt(wpl_file::getName($file['name'])));
			
			if(!in_array($extention, $ext_array))
			{
				$error = __("File extension should be jpg, png or gif", WPL_TEXTDOMAIN);
			}
			
			/** check the file size **/
			$filesize = @filesize($file['tmp_name']);
			
			if($filesize> 500*1024)
			{
				$error .= __("Icons should not be bigger than 500KB!", WPL_TEXTDOMAIN);
				@unlink($file);
			}
		
			if($error == "")
			{
				$dest = WPL_ABSPATH . 'assets' . DS . 'img' . DS . 'listing_types' . DS . 'gicon' . DS . $name . '.' .$extention;
				
				while(wpl_file::exists($dest))
				{
					$name .= '_copy';
					$dest = WPL_ABSPATH . 'assets' . DS . 'img' . DS . 'listing_types' . DS . 'gicon' . DS . $name . '.' .$extention;
				}
				
				wpl_file::upload($file['tmp_name'], $dest);
			}
		}
		
		$message = '';
		$response = array('error'=>$error, 'message'=>$message);
		
		echo json_encode($response);
		exit;
	}
	
	private function gicon_delete($icon)
	{
		if(trim($icon) == '') $icon = wpl_request::getVar('icon');
		$dest = WPL_ABSPATH . 'assets' . DS . 'img' . DS . 'listing_types' . DS . 'gicon' . DS . $icon;
		
		if (wpl_file::exists($dest)) wpl_file::delete($dest);
        
        /** trigger event **/
		wpl_global::event_handler('gicon_removed', array('icon'=>$icon));
		exit;
	}
	
	private function sort_listing_types($sort_ids)
	{
		if(trim($sort_ids) == '') $sort_ids = wpl_request::getVar('sort_ids');
		
		$res = wpl_listing_types::sort_listing_types($sort_ids);
		$message = $res ? __('Operation was successful.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$response = array('success'=>1, 'message'=>$message, 'data'=>NULL);
		
		echo json_encode($response);
		exit;
	}
	
	private function remove_listing_type($listing_type_id, $confirmed = 0)
	{
		if($confirmed) $res = wpl_listing_types::remove_listing_type($listing_type_id);
		else $res = false;
		
		$res = (int) $res;
		$message = $res ? __('Listing type removed from WPL successfully.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
	
	private function set_enabled_listing_type($listing_type_id, $enabled_status)
	{
		$res = wpl_listing_types::update($listing_type_id, 'enabled', $enabled_status);
		
		$res = (int) $res;
		$message = $res ? __('Operation was successful.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
	
	private function generate_edit_page($listing_type_id = '')
	{
		if(trim($listing_type_id) == '') $listing_type_id = wpl_request::getVar('listing_type_id');
		
		$this->listing_type_id = $listing_type_id;
		$this->listing_type_data = wpl_listing_types::get_listing_type($this->listing_type_id);
		$this->listing_types_category = wpl_listing_types::get_listing_type_categories();
		$this->listing_gicons = wpl_listing_types::get_map_icons();
		
		parent::render($this->tpl_path, 'internal_edit_listing_types');
		exit;
	}
	
	private function generate_new_page()
	{
		$this->listing_type_id = 10000;
		$this->listing_type_data = wpl_listing_types::get_listing_type($this->listing_type_id);
		$this->listing_types_category = wpl_listing_types::get_listing_type_categories();
		$this->listing_gicons = wpl_listing_types::get_map_icons();
		
		parent::render($this->tpl_path, 'internal_edit_listing_types');
		exit;
	}
	
	private function generate_delete_page()
	{
		$this->listing_type_id = wpl_request::getVar('listing_type_id');
		$this->listing_type_data = wpl_listing_types::get_listing_type($this->listing_type_id);
		$this->listing_types = wpl_listing_types::get_listing_types();
        
		parent::render($this->tpl_path, 'internal_delete_listing_types');
		exit;
	}
	
    private function insert_listing_type()
    {
		$parent = wpl_request::getVar('parent');
		$name = wpl_request::getVar('name');
		$gicon = wpl_request::getVar('gicon');
		
		$res = wpl_listing_types::insert_listing_type($parent, $name, $gicon);
		$res = (int) $res;
		
		if($res > 0) $res = 1;
		else $res = 0;
		
		$message = $res ? __('Saved.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
    
    private function save_listing_type()
    {
		$key = wpl_request::getVar('key');
		$value = wpl_request::getVar('value');
		$id = wpl_request::getVar('listing_type_id');
		
		$query = "UPDATE `#__wpl_listing_types` SET `$key`='$value' WHERE id='$id'";
		$res = wpl_db::q($query);
		
		$res = (int) $res;
		$message = $res ? __('Saved.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
    }
    
	private function can_remove_listing_type()
	{
		$listing_type_id = wpl_request::getVar('listing_type_id');
		$res = wpl_listing_types::have_properties($listing_type_id);
		$res = (int) $res;
        
		if($res > 0) $res = 0;
		else $res = 1;
        
		echo $res;
		exit;
	}
    
	private function purge_related_property()
	{
		$listing_type_id = wpl_request::getVar('listing_type_id');
		$properties_list = wpl_property::get_properties_list('listing', $listing_type_id);
        
		foreach($properties_list as $property) wpl_property::purge($property['id']);
		$this->remove_listing_type($listing_type_id, 1);
	}
    
	private function assign_related_properties()
	{
		$listing_type_id = wpl_request::getVar('listing_type_id');
		$select_id = wpl_request::getVar('select_id');
        
		$j = wpl_property::update_properties('listing', $listing_type_id, $select_id);
		$this->remove_listing_type($listing_type_id, 1);
	}
}