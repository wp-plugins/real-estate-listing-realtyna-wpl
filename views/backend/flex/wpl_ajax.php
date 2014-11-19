<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.flex');

class wpl_flex_controller extends wpl_controller
{
	var $tpl_path = 'views.backend.flex.tmpl';
	var $tpl;
	
	public function display()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$function = wpl_request::getVar('wpl_function');
		
		if($function == 'save_dbst')
		{
			self::save_dbst();
		}
		elseif($function == 'remove_dbst')
		{
			$dbst_id = wpl_request::getVar('dbst_id');
			self::remove_dbst($dbst_id);
		}
		elseif($function == 'generate_params_page')
		{
			$dbst_id = wpl_request::getVar('dbst_id');
			self::generate_params_page($dbst_id);
		}
		elseif($function == 'enabled')
		{
			$dbst_id = wpl_request::getVar('dbst_id');
			$enabled_status = wpl_request::getVar('enabled_status');
			
			self::enabled($dbst_id, $enabled_status);
		}
		elseif($function == 'sort_flex')
		{
			$sort_ids = wpl_request::getVar('sort_ids');
			
			self::sort_flex($sort_ids);
		}
		elseif($function == 'mandatory')
		{
			$dbst_id = wpl_request::getVar('dbst_id');
			$mandatory_status = wpl_request::getVar('mandatory_status');
			
			self::mandatory($dbst_id, $mandatory_status);
		}
	}
	
	private function mandatory($dbst_id, $mandatory_status)
	{
		$res = wpl_flex::update('wpl_dbst', $dbst_id, 'mandatory', $mandatory_status);
		
		$res = (int) $res;
		$message = $res ? __('Operation was successful.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
	
	private function sort_flex($sort_ids)
	{
		if(trim($sort_ids) == '') $sort_ids = wpl_request::getVar('sort_ids');
		wpl_flex::sort_flex($sort_ids);
		
		exit;
	}
	
	private function save_dbst()
	{
		$dbst_id = wpl_request::getVar('dbst_id', 0);
		$post = wpl_request::get('post');
		
		$mode = 'edit';
		
		/** insert new field **/
		if(!$dbst_id)
		{
			$mode = 'add';
			$dbst_id = wpl_flex::create_default_dbst();
		}
        
		$q = '';
		foreach($post as $field=>$value)
		{
			if(substr($field, 0 ,4) != 'fld_') continue;
			$key = substr($field, 4);
            
			$q .= "`$key`='$value', ";
		}
		
		/** add options to query **/
		$options = wpl_flex::get_encoded_options($post, 'opt_', wpl_flex::get_field_options($dbst_id));
		$q .= "`options`='".wpl_db::escape($options)."', ";
		
		$q = trim($q, ", ");
		$query = "UPDATE `#__wpl_dbst` SET ".$q." WHERE `id`='$dbst_id'";
		
		wpl_db::q($query, 'update');
		
		$dbst_type = wpl_flex::get_dbst_key('type', $dbst_id);
		$dbst_kind = wpl_flex::get_dbst_key('kind', $dbst_id);
		
		/** run queries **/
		if($mode == 'add') wpl_flex::run_dbst_type_queries($dbst_id, $dbst_type, $dbst_kind, 'add');
        
        /** Multilingual **/
		if(wpl_global::check_addon('pro')) wpl_addon_pro::multilingual($dbst_id);
        
		/** trigger event **/
		wpl_global::event_handler('dbst_modified', array('id'=>$dbst_id, 'mode'=>$mode, 'kind'=>$dbst_kind, 'type'=>$dbst_type));
		
		/** echo response **/
		echo json_encode(array('success'=>1, 'message'=>__('Field saved.', WPL_TEXTDOMAIN), 'data'=>NULL));
		exit;
	}
	
	private function generate_params_page($dbst_id)
	{
		$params = array('element_class'=>'wpl_params_cnt', 'js_function'=>'wpl_save_params', 'id'=>$dbst_id, 'table'=>'wpl_dbst', 'html_path_message'=>'dont_show', 'close_fancybox'=>true);
		wpl_global::import_activity('params:default', '', $params);
		exit;
	}
	
	private function remove_dbst($dbst_id)
	{
		$dbst_type = wpl_flex::get_dbst_key('type', $dbst_id);
		$dbst_kind = wpl_flex::get_dbst_key('kind', $dbst_id);
		$is_deletable = wpl_flex::get_dbst_key('deletable', $dbst_id);
		
		if($is_deletable)
		{
			/** delete dbst row **/
			wpl_flex::remove_dbst($dbst_id);
			
			/** run queries **/
			wpl_flex::run_dbst_type_queries($dbst_id, $dbst_type, $dbst_kind, 'delete');
        
			/** trigger event **/
			wpl_global::event_handler('dbst_deleted', array('id'=>$dbst_id, 'kind'=>$dbst_kind, 'type'=>$dbst_type));
			
			$success = 1;
			$message = __('Field saved.', WPL_TEXTDOMAIN);
		}
		else
		{
			$success = 0;
			$message = __('Field is not deletable.', WPL_TEXTDOMAIN);
		}
		
		/** echo response **/
		echo json_encode(array('success'=>$success, 'message'=>$message, 'data'=>NULL));
		exit;
	}
	
	private function enabled($dbst_id, $enabled_status)
	{
		$res = wpl_flex::update('wpl_dbst', $dbst_id, 'enabled', $enabled_status);
		
		$res = (int) $res;
		$message = $res ? __('Operation was successful.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);
		
		echo json_encode($response);
		exit;
	}
}