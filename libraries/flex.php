<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** Flex Library
** Developed 05/01/2013
**/

class wpl_flex
{
	public static $category_listing_specific_array = array();
	public static $category_property_type_specific_array = array();
	public static $wizard_js_validation = array();
	
	/**
		@input [category], [enabled], [kind], [custom key], [custom value] and [condition]
		@params: key is a custom field for selecting for example "plisting" or "pwizard"
		@params: value is a value of custom key
		@return field objects
	**/
	public static function get_fields($category = '', $enabled = 0, $kind = 0, $key = '', $value = '', $condition = '')
	{
		if(!$condition)
		{
			$condition = '';
			
			if(trim($category) != '') $condition .= " AND `category`='$category'";
			if(trim($enabled) != '') $condition .= " AND `enabled`>='$enabled'";
			if(trim($kind) != '') $condition .= " AND `kind`='$kind'";
			
			if(trim($key) != '') $condition .= " AND `".$key."`>='$value'";
		}
		
		$query = "SELECT * FROM `#__wpl_dbst` WHERE 1 ".$condition." ORDER BY `index` ASC";
		return wpl_db::select($query, 'loadObjectList');
	}
	
	/**
		@input {field_id}
		@return field object
	**/
	public static function get_field($field_id)
	{
		$query = "SELECT * FROM `#__wpl_dbst` WHERE `id`='$field_id'";
		return wpl_db::select($query, 'loadObject');
	}
	
	/**
		@input {dbst_id}
		@return void
	**/
	public static function create_default_dbst($dbst_id = 0, $searchmod = 1)
	{
		if(!$dbst_id) $dbst_id = self::get_new_dbst_id();
		
		$query = "INSERT INTO `#__wpl_dbst` (`id`,`enabled`,`pshow`,`plisting`,`searchmod`,`pwizard`,`index`) VALUES ('$dbst_id','1','1','0','$searchmod','1','$dbst_id');";
		return wpl_db::q($query, 'insert');
	}
	
	/**
		@input [enabled], [kind] and [condition]
		@return array categories
	**/
	public static function get_categories($enabled = 1, $kind = 0, $condition = '')
	{
		if(trim($condition) == '') $condition = " AND `enabled`>='$enabled' AND `kind`='$kind'";
		
		$query = "SELECT * FROM `#__wpl_dbcat` WHERE 1 ".$condition." ORDER BY `index` ASC";
		return wpl_db::select($query, 'loadObjectList');
	}
	
	/**
		@input {category_id}
		@return category object
	**/
	public static function get_category($category_id)
	{
		$query = "SELECT * FROM `#__wpl_dbcat` WHERE `id`='$category_id'";
		return wpl_db::select($query, 'loadObject');
	}
	
	/**
		@input {kind}
		@return kind label
	**/
	public static function get_kind_label($kind = 0)
	{
		$kind_array = array(0=>'property', 1=>'complex', 2=>'user');
		return $kind_array[$kind];
	}
	
	/**
		@input {kind_name}
		@return kind id
	**/
	public static function get_kind_id($kind_name = 'property')
	{
		$kind_array = array('property'=>0, 'complex'=>1, 'user'=>2);
		return $kind_array[$kind_name];
	}
	
	/**
		@input {kind}
		@return kind label
	**/
	public static function get_kind_table($kind = 0)
	{
		$kind_array = array(0=>'wpl_properties', 1=>'wpl_properties', 2=>'wpl_users');
		return $kind_array[$kind];
	}
	
	/**
		@input void
		@return valid kind ids
	**/
	public static function get_valid_kinds()
	{
		return array(0, 1, 2);
	}
    
    /**
		@input void
		@return kind array
	**/
	public static function get_kinds()
	{
		return array(0=>array('id'=>0, 'name'=>'Property'), 1=>array('id'=>1, 'name'=>'Complex'), 2=>array('id'=>2, 'name'=>'User'));
	}
	
	/**
		@input [enabled], [kind] and [condition]
		@return array dbst types
	**/
	public static function get_dbst_types($enabled = 1, $kind = 0, $condition = '')
	{
		if(trim($condition) == '')
		{
			$condition = " AND `enabled`>='$enabled' AND `kind` LIKE '%[$kind]%'";
		}
		
		$query = "SELECT * FROM `#__wpl_dbst_types` WHERE 1 ".$condition." ORDER BY `id` ASC";
		return wpl_db::select($query, 'loadObjectList');
	}
	
	/**
		@input [enabled], [kind] and [type]
		@return object dbst type
	**/
	public static function get_dbst_type($enabled = 1, $kind = 0, $type = '')
	{
		$condition = " AND `enabled`>='$enabled' AND `kind` LIKE '%[$kind]%' AND `type`='$type'";
		
		$query = "SELECT * FROM `#__wpl_dbst_types` WHERE 1 ".$condition;
		return wpl_db::select($query, 'loadObject');
	}
	
	/**
		@input [key] and [dbst_id]
		@return key value
	**/
	public static function get_dbst_key($key, $dbst_id, $kind = 0)
	{
		/** first validation **/
		if(trim($key) == '' or trim($dbst_id) == '') return false;
		
		$dbst_data = self::get_field($dbst_id);
		return $dbst_data->$key;
	}
	
	/**
		@input void
		@return new dbst id for insert new fields
	**/
	public static function get_new_dbst_id()
	{
		$max_dbst_id = wpl_db::get("MAX(`id`)", "wpl_dbst", '', '', '', "`id`<'10000'");
		return max(($max_dbst_id+1), 3000);
	}
	
	/**
		@input {dbst_id}
		@return boolean result
	**/
	public static function remove_dbst($dbst_id)
	{
		/** first validation **/
		if(!$dbst_id) return false;
		
		wpl_db::delete("wpl_dbst", $dbst_id);
	}
	
	/**
		@input {dbst type}, [dbst_id]
		@return void
		@description this function generates dbst modify form
	**/
	public static function generate_modify_form($dbst_type = 'text', $dbst_id = 0, $kind = 0)
	{
		/** first validation **/
		if(!$dbst_type) return;
		$dbst_data = $dbst_id != 0 ? self::get_field($dbst_id) : new stdClass();
		
		$done_this = false;
		$type = $dbst_type;
		$values = $dbst_data;
		$options = isset($values->options) ? json_decode($values->options, true) : array();
		
		$__prefix = 'wpl_flex_modify';
		
		_wpl_import('libraries.listing_types');
		_wpl_import('libraries.property_types');
		
		$dbcats = self::get_categories(0, $kind);
		$listings = wpl_listing_types::get_listing_types();
		$property_types = wpl_property_types::get_property_types();
		
		/** get files **/
		$dbst_modifypath = WPL_ABSPATH . DS . 'libraries' . DS . 'dbst_modify';
		$files = array();
		
		if(wpl_folder::exists($dbst_modifypath))
		{
			$files = wpl_folder::files($dbst_modifypath, '.php$');
			
			foreach($files as $file)
			{
				include($dbst_modifypath .DS. $file);
			}
			
			if(!$done_this)
			{
				/** include default file **/
				$path = _wpl_import('libraries.dbst_modify.main.default', true, true);
				include $path;
			}
		}
	}
	
	/**
		@input {dbst_id}, {dbst type} and {dbst kind}
		@return new dbst id for insert new fields
	**/
	public static function run_dbst_type_queries($dbst_id, $dbst_type, $dbst_kind, $query_type = 'add')
	{
		$dbst_type_data = self::get_dbst_type(0, $dbst_kind, $dbst_type);
		$kind_table = self::get_kind_table($dbst_kind);
		
		/** running all necessary queries **/
		if($query_type == 'add') $queries = $dbst_type_data->queries_add;
		elseif($query_type == 'delete') $queries = $dbst_type_data->queries_delete;
		
		$queries = explode(';', $queries);
		foreach($queries as $query)
		{
			if(trim($query) == '') continue;
			
			$query = str_replace('[TABLE_NAME]', $kind_table, $query);
			$query = str_replace('[FIELD_ID]', $dbst_id, $query);
			
			wpl_db::q($query);
		}
	}
	
	/**
		@input [key] and [dbst_id]
		@return key value
	**/
	public static function get_encoded_options($values, $prefix, $options = array())
	{
		$length = strlen($prefix);
		
		foreach($values as $key=>$value)
		{
			if(substr($key, 0, $length) != $prefix) continue;
			
			$field = substr($key, $length);
			$options[$field] = $value;
		}
        
		return json_encode($options);
	}
	
	/**
		@input {table}, {key}, {dbst_id} and [value]
		@return boolean result
	**/
	public static function update($table = 'wpl_dbst', $dbst_id, $key, $value = '')
	{
		/** first validation **/
		if(trim($table) == '' or trim($dbst_id) == '' or trim($key) == '') return false;
		
		return wpl_db::set($table, $dbst_id, $key, $value);
	}
	
	/**
		@input dbst fields
		@return void
		@description this function generates dbst wizards
	**/
	public function generate_wizard_form($fields, $values, $item_id = 0, &$finds = array())
	{
		/** first validation **/
		if(!$fields) return;
		
		/** get files **/
		$path = WPL_ABSPATH .DS. 'libraries' .DS. 'dbst_wizard';
		$files = array();
		
		if(wpl_folder::exists($path)) $files = wpl_folder::files($path, '.php$', false, false, $finds);
		
		foreach($fields as $key=>$field)
		{
			if(!$field) return;
			
			$done_this = false;
			$type = $field->type;
			$options = json_decode($field->options, true);
			$value = isset($values[$field->table_column]) ? $values[$field->table_column] : NULL;
			$display = '';
			
			if(trim($field->listing_specific) != '')
			{
				$specified_listings = explode(',', trim($field->listing_specific, ', '));
				self::$category_listing_specific_array[$field->id] = $specified_listings;
				if(!in_array($values['listing'], $specified_listings)) $display = 'display: none;';
			}
			elseif(trim($field->property_type_specific) != '')
			{
				$specified_property_types = explode(',', trim($field->property_type_specific, ', '));
				self::$category_property_type_specific_array[$field->id] = $specified_property_types;
				if(!in_array($values['property_type'], $specified_property_types)) $display = 'display: none;';
			}
			
			/** js validation **/
			self::$wizard_js_validation[$field->id] = self::generate_js_validation($field);
			
			if(isset($finds[$type]))
			{
				echo '<div class="prow wpl_listing_field_container prow-'.$type.'" id="wpl_listing_field_container'.$field->id.'" style="'.$display.'">';
				include($path .DS. $finds[$type]);
				echo '</div>';
				
				continue;
			}
			
			echo '<div class="prow wpl_listing_field_container prow-'.$type.'" id="wpl_listing_field_container'.$field->id.'" style="'.$display.'">';
			foreach($files as $file)
			{
				include($path .DS. $file);
				
				if($done_this)
				{
					$finds[$type] = $file;
					break;
				}
			}
			echo '</div>';
		}
	}
	
	/**
		@input {field_object}
		@return js validation string
		@author Howard
	**/
	public static function generate_js_validation($field)
	{
		$field = (object) $field;
		$js_string = '';
		
		$path = WPL_ABSPATH .DS. 'libraries' .DS. 'dbst_wizard' .DS. 'js_validation' .DS. $field->type .'.php';
		$override_path = WPL_ABSPATH .DS. 'libraries' .DS. 'dbst_wizard' .DS. 'js_validation' .DS. 'overrides' .DS. $field->type .'.php';
		
		if(wpl_file::exists($override_path)) $path = $override_path;
		
		/** include file **/
		if(wpl_file::exists($path)) include $path;
		
		return $js_string;
	}
	
	/**
		@input {field_id}
		@return field options
	**/
	public static function get_field_options($field_id, $return_array = true)
	{
		$field = self::get_field($field_id);
		return ($return_array ? json_decode($field->options, true) : $field->options);
	}
	
	/**
		@input dbst sort_ids
		@return void
		@description this function set sort 
	**/
	public static function sort_flex($sort_ids)
	{
		$query = "SELECT DISTINCT  `category`  FROM `#__wpl_dbst` WHERE `id` IN ($sort_ids) ORDER BY `index` ASC";
		$flex_category = wpl_db::select($query, 'loadAssoc');
		
		$conter = 0;
		$ex_sort_ids = explode(',', $sort_ids);
		
		foreach($ex_sort_ids as $ex_sort_id)
		{
			if($conter < 10)
				$index = $flex_category["category"].'.0'.$conter;
			else
				$index = $flex_category["category"].'.'.$conter;
			
			self::update('wpl_dbst', $ex_sort_id, 'index', $index);
			$conter++;
		}
	}
}