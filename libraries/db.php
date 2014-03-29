<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** DB Library
** Developed 02/10/2013
**/

class wpl_db
{
	/**
		Developed by : Howard
		Inputs : complete query and type of query
		Outputs : result of query
		Date : 2013-02-16
		Description : use this function for runnig INSERT, UPDATE and DELETE queries, also set type if you need any result.
	**/
	public static function q($query, $type = '')
	{
		/** db prefix **/
		$query = self::_prefix($query);
		
		/** convert type to lowercase **/
		$type = strtolower($type);
		
		/** call select function if query type if select **/
		if($type == 'select') return self::select($query);
		
		/** db object **/
		$database = self::get_DBO();
		
		if($type == 'insert')
		{
			$database->query($query);
			return $database->insert_id;
		}
		
		return $database->query($query);
	}
	
	/**
		Developed by : Howard
		Inputs : complete query
		Outputs : num of result
		Date : 2013-03-06
		Description : use this function getting num of result
	**/
	public function num($query, $table = '')
	{
		if(trim($table) != '')
		{
			$query = "SELECT COUNT(*) FROM `#__$table`";
		}
		
		/** db prefix **/
		$query = self::_prefix($query);
		
		/** db object **/
		$database = self::get_DBO();
		return $database->get_var($query);
	}
	
	/**
		Developed by : Howard
		Inputs : vars array
		Outputs : query
		Date : 2013-03-06
		Description : use this function for creating query
	**/
	public function create_query($vars = '', $needle_str = 'sf_')
	{
		if(!$vars)
		{
			$vars = array_merge(wpl_request::get('POST'), wpl_request::get('GET'));
		}
		
		/** clean vars **/
		$vars = wpl_global::clean($vars);
		
		$query = '';
		
		/** this is to include any customized and special form fields conditions **/
		$path = WPL_ABSPATH .DS. 'libraries' .DS. 'create_query';
		$path_exists = wpl_folder::exists($path);
		$find_files = array();
		
		if($path_exists) $files = wpl_folder::files($path, '.php$');
		
		foreach($vars as $key=>$value)
		{
			/** escape value **/
			$value = wpl_db::escape($value);
			
			if(strpos($key, $needle_str) === false) continue;
			$ex = explode('_', $key);
			
			$format = $ex[1];
			$table_column = str_replace($needle_str.$format.'_', '', $key);
			
			$done_this = false;
			
			/** using detected files **/
			if(isset($find_files[$format]))
			{
				include($path .DS. $find_files[$format]);
				continue;
			}
			
			foreach($files as $file)
			{
				include($path .DS. $file);
				
				if($done_this)
				{
					/** add to detected files **/
					$find_files[$format] = $file;
					break;
				}
			}
		}
		
		return $query = trim($query, ' ,');
	}
	
	/**
		Developed by : Howard
		Inputs : complete query and type of result
		Outputs : result of query
		Date : 2013-02-16
		Description : use this function for runnig SELECT queries, also you can change type of result if need.
	**/
	public static function select($query, $result = 'loadObjectList')
	{
		/** db prefix **/
		$query = self::_prefix($query);
		
		/** db object **/
		$database = self::get_DBO();
		
		if($result == 'loadObjectList') return $database->get_results($query, OBJECT_K);
		elseif($result == 'loadObject') return $database->get_row($query, OBJECT);
		elseif($result == 'loadAssocList') return $database->get_results($query, ARRAY_A);
		elseif($result == 'loadAssoc') return $database->get_row($query, ARRAY_A);
		elseif($result == 'loadResult') return $database->get_var($query);
		elseif($result == 'loadColumn') return $database->get_col($query);
		else return $database->get_results($query, OBJECT_K);
	}
	
	/**
		Developed by : Howard
		Inputs : select parameters, name of table without #__ , name and value of field for creating where, return type and custom condition
		Outputs : result of query
		Date : 2013-02-16
		Description : use this function for runnig SELECT queries just for 1 record. it creats query automatically.
	**/
	public static function get($selects, $table, $field, $value, $return_object = true, $condition = '')
	{
		$fields = '';
		
		if(is_array($selects))
		{
			foreach($selects as $select) $fields .= '`'.$select.'`,';
			$fields = trim($fields, ' ,');
		}
		else
		{
			$fields = $selects;
		}
		
		if(trim($condition) == '') $condition = "`$field`='$value'";
		$query = "SELECT $fields FROM `#__$table` WHERE $condition";
		
		/** db prefix **/
		$query = self::_prefix($query);
		
		/** db object **/
		$database = self::get_DBO();
		
		if($selects != '*' and !is_array($selects)) return $database->get_var($query);
		elseif($return_object)
		{
			return $database->get_row($query);
		}
		elseif(!$return_object)
		{
			return $database->get_row($query, ARRAY_A);
		}
		else
		{
			return $database->get_row($query);
		}
	}
	
	/**
		Developed by : Howard
		Inputs : {table_name}, {id} and [condition]
		Outputs : result of query
		Date : 2013-05-07
		Description : use this function for runnig DELETE commands
	**/
	public function delete($table, $id, $condition = '')
	{
		/** first validation **/
		if(trim($table) == '' or (trim($id) == '' and trim($condition) == '')) return false;
		
		if(trim($condition) == '') $condition = " AND `id`='$id'";
		if(trim($condition) == '') return false;
		
		$query = "DELETE FROM `#__$table` WHERE 1 ".$condition;
		return self::q($query, 'delete');
	}
	
	/**
		@input {table}, {key}, {id} and [value]
		@return boolean result
		@author Howard
	**/
	public static function set($table, $where_value, $key, $value = '', $where_key = 'id')
	{
		/** first validation **/
		if(trim($table) == '' or trim($where_value) == '' or trim($key) == '' or trim($where_key) == '') return false;
		
		$query = "UPDATE `#__$table` SET `$key`='$value' WHERE `$where_key`='$where_value'";
		return wpl_db::q($query);
	}
	
	/**
		@input {table}, {key}, {where_key}, [where_value] and [value]
		@return boolean result
		@author Howard
	**/
	public function update($table, $params = array(), $where_key = 'id', $where_value = '')
	{
		/** first validation **/
		if(trim($table) == '' or trim($where_value) == '' or trim($where_key) == '' or !is_array($params)) return false;
		if(count($params) == 0) return false;
		
		$update_str = '';
		foreach($params as $field=>$value)
		{
			$update_str .= "`$field`='$value', ";
		}
		
		$update_str = trim($update_str, ', ');
		
		$query = "UPDATE `#__$table` SET ".$update_str." WHERE `$where_key`='$where_value'";
		return wpl_db::q($query, 'update');
	}
	
	/** get table columns howard 12/30/2012 **/
	public function columns($table = 'wpl_properties')
	{
		$query = "SHOW COLUMNS FROM `#__".$table."`";
		$results = wpl_db::q($query, "select");
		
		$array = array();
		foreach($results as $key=>$result)
		{
			$array[] = $result->Field;
		}
		
		return $array;
	}
	
	/**
		Developed by : Howard
		Inputs : {parameter}
		Outputs : escaped parameter
		Date : 2013-05-07
		Description : use this function for escaping any variable
     **/
    public static function escape($parameter)
    {
        /** db object **/
        $database = self::get_DBO();
		$return_data = '';
		$wp_version = wpl_global::wp_version();
		
        if(is_array($parameter)) // Added by Kevin for Escape Array Items
        {
			$return_data = array();
			
            foreach($parameter as $key=>$value)
            {
                $return_data[$key] = self::escape($value);
            }
        }
        else
		{
            if(version_compare($wp_version, '3.6', '<')) $return_data = $database->escape($parameter);
			else $return_data = esc_sql($parameter);
		}

        return $return_data;
    }

    /**
		Developed by : Howard
		Inputs : {query}
		Outputs : query
		Date : 2013-05-07
		Description : use this function for replacing fake prefix with real one
	**/
	public function _prefix($query)
	{
		$database = self::get_DBO();
		
		$query = str_replace('#__users', $database->base_prefix.'users', $query);
		$query = str_replace('#__blogs', $database->base_prefix.'blogs', $query);
		$query = str_replace('#__', $database->prefix, $query);
		
		return $query;
	}
	
	/**
		Developed by : Howard
		Inputs : null
		Outputs : database object
		Date : 2013-02-16
		Description : use this function for getting database object
	**/
	public function get_DBO()
	{
		global $wpdb;
		return $wpdb;
	}
	
}