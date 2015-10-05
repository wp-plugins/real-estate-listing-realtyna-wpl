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
		
		if($function == 'save_dbst') $this->save_dbst();
		elseif($function == 'remove_dbst')
		{
			$dbst_id = wpl_request::getVar('dbst_id');
			$this->remove_dbst($dbst_id);
		}
		elseif($function == 'generate_params_page')
		{
			$dbst_id = wpl_request::getVar('dbst_id');
			$this->generate_params_page($dbst_id);
		}
		elseif($function == 'enabled')
		{
			$dbst_id = wpl_request::getVar('dbst_id');
			$enabled_status = wpl_request::getVar('enabled_status');
			
			$this->enabled($dbst_id, $enabled_status);
		}
		elseif($function == 'sort_flex')
		{
			$sort_ids = wpl_request::getVar('sort_ids');
			
			$this->sort_flex($sort_ids);
		}
		elseif($function == 'mandatory')
		{
			$dbst_id = wpl_request::getVar('dbst_id');
			$mandatory_status = wpl_request::getVar('mandatory_status');
			
			$this->mandatory($dbst_id, $mandatory_status);
		}
        elseif($function == 'convert_dbst') $this->convert_dbst();
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
		
        // Field should be added to network
        $multisite_modify_status = wpl_request::getVar('fld_multisite_modify_status', 0);
        $current_blog_id = wpl_global::get_current_blog_id();
        
		/** insert new field **/
		if(!$dbst_id)
		{
			$mode = 'add';
			$dbst_id = wpl_flex::create_default_dbst();
		}
        
        $available_columns = wpl_db::columns('wpl_dbst');
        
		$q = '';
		foreach($post as $field=>$value)
		{
			if(substr($field, 0 ,4) != 'fld_') continue;
			$key = substr($field, 4);
            if(trim($key) == '') continue;
            if(!in_array($key, $available_columns)) continue;
            
			$q .= "`$key`='$value', ";
		}
		
		/** add options to query **/
		$options = wpl_flex::get_encoded_options($post, 'opt_', wpl_flex::get_field_options($dbst_id));
		$q .= "`options`='".wpl_db::escape($options)."', ";
        
        if($mode == 'add' and $multisite_modify_status and wpl_global::is_multisite()) $q .= "`source_id`='".$dbst_id.':'.$current_blog_id."', ";
        
		$q = trim($q, ", ");
		$query = "UPDATE `#__wpl_dbst` SET ".$q." WHERE `id`='$dbst_id'";
		
		wpl_db::q($query, 'update');
		
		$dbst_type = wpl_flex::get_dbst_key('type', $dbst_id);
		$dbst_kind = wpl_flex::get_dbst_key('kind', $dbst_id);
		
		/** run queries **/
		if($mode == 'add') wpl_flex::run_dbst_type_queries($dbst_id, $dbst_type, $dbst_kind, 'add');
        
        $table_column = wpl_flex::get_dbst_key('table_column', $dbst_id);
        
        /** Multilingual **/
		if(wpl_global::check_addon('pro')) wpl_addon_pro::multilingual($dbst_id);
        
		/** trigger event **/
		wpl_global::event_handler('dbst_modified', array('id'=>$dbst_id, 'mode'=>$mode, 'kind'=>$dbst_kind, 'type'=>$dbst_type));
        
        if($multisite_modify_status and wpl_global::is_multisite())
        {
            $q .= ", `table_column`='".$table_column."', ";
            $q = trim($q, ', ');
            
            $blogs = wpl_db::select("SELECT `blog_id` FROM `#__blogs`", 'loadColumn');
            foreach($blogs as $blog_id)
            {
                if($blog_id == $current_blog_id) continue;
                
                switch_to_blog($blog_id);
                
                if($mode == 'add')
                {
                    $dbst_id = wpl_flex::create_default_dbst();
                    $where = "`id`='$dbst_id'";
                }
                elseif($mode == 'edit') $where = "`table_column`='".$table_column."'";
                
                wpl_db::q("UPDATE `#__wpl_dbst` SET ".$q." WHERE ".$where, 'update');
            }

            switch_to_blog($current_blog_id);
        }
        
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
		
		if($is_deletable and wpl_users::is_super_admin())
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
        elseif($is_deletable and wpl_global::is_multisite())
        {
            wpl_db::q("UPDATE `#__wpl_dbst` SET `enabled`='0' AND `flex`='0' WHERE `id`='$dbst_id'", "UPDATE");
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
    
    private function convert_dbst()
	{
		$dbst_id = wpl_request::getVar('dbst_id', 0);
        $new_type = wpl_request::getVar('type', 'select');
        
        $field_data = wpl_flex::get_field($dbst_id);
        
        $dbst_type = $field_data->type;
		$dbst_kind = $field_data->kind;
        $table_column = $field_data->table_column;
        $table_name = $field_data->table_name;
        
        $multilingual_status = wpl_global::check_multilingual_status();
        if($field_data->multilingual and $multilingual_status)
        {
            $table_column = wpl_addon_pro::get_column_lang_name($table_column, wpl_global::get_current_language(), false);
        }
        
        $values = wpl_db::select("SELECT `$table_column` FROM `#__$table_name` WHERE `kind`='$dbst_kind' AND `$table_column`!='' GROUP BY `$table_column` ORDER BY `$table_column` ASC", 'loadColumn');
        
        $options = array();
        $options['params'] = array();
        
        $i = 0;
        foreach($values as $value)
        {
            if(trim($value) == '') continue;
            
            $i++;
            $options['params'][$i] = array('key'=>$i, 'enabled'=>1, 'value'=>$value);
            
            if($field_data->multilingual  and $multilingual_status)
            {
                $columns = wpl_global::get_multilingual_columns(array($table_column), true, $table_name);
                foreach($columns as $column)
                {
                    wpl_db::q("UPDATE `#__$table_name` SET `$column`='$i' WHERE `kind`='$dbst_kind' AND `$column`='$value'");
                }
            }
            else wpl_db::q("UPDATE `#__$table_name` SET `$table_column`='$i' WHERE `kind`='$dbst_kind' AND `$table_column`='$value'");
        }
        
        wpl_db::q("UPDATE `#__wpl_dbst` SET `options`='".json_encode($options)."', `type`='$new_type' WHERE `id`='$dbst_id'");
        
		/** trigger event **/
		wpl_global::event_handler('dbst_converted', array('id'=>$dbst_id, 'new_type'=>$new_type, 'kind'=>$dbst_kind, 'previous_type'=>$dbst_type));
		
		/** echo response **/
		echo json_encode(array('success'=>1, 'message'=>__('Field converted.', WPL_TEXTDOMAIN), 'data'=>NULL));
		exit;
	}
}