<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * WPL DB library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 02/10/2013
 * @package WPL
 */
class wpl_db
{
    /**
     * Use this function for runnig INSERT, UPDATE and DELETE queries, also set type if you need any result.
     * @author Howard <howard@realtyna.com>
     * @param string $query
     * @param string $type
     * @return mixed result of query based on $type parameter
     */
	public static function q($query, $type = '')
	{
		/** convert type to lowercase **/
		$type = strtolower($type);
		
		/** call select function if query type if select **/
		if($type == 'select') return self::select($query);
		
        /** db prefix **/
		$query = self::_prefix($query);
        
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
     * Use this function getting num of result
     * @author Howard <howard@realtyna.com>
     * @param string $query
     * @param string $table
     * @return int
     */
	public static function num($query, $table = '')
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
     * Use this function for creating query
     * @author Howard <howard@realtyna.com>
     * @param array $vars
     * @param string $needle_str
     * @return string $query
     */
	public static function create_query($vars = '', $needle_str = 'sf_')
	{
		if(!$vars)
		{
			$vars = array_merge(wpl_request::get('POST'), wpl_request::get('GET'));
		}
		
		/** Clean and Escape vars **/
		$vars = wpl_global::clean($vars);
		
		$query = '';
		
		/** this is to include any customized and special form fields conditions **/
		$path = WPL_ABSPATH .DS. 'libraries' .DS. 'create_query';
		$path_exists = wpl_folder::exists($path);
		$find_files = array();
		
		if($path_exists) $files = wpl_folder::files($path, '.php$');
		
		foreach($vars as $key=>$value)
		{
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
     * Use this function for runnig SELECT queries, also you can change type of result if need.
     * @author Howard <howard@realtyna.com>
     * @param string $query
     * @param string $result
     * @return mixed
     */
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
     * Use this function for runnig SELECT queries just for 1 record. it creats query automatically.
     * @author Howard <howard@realtyna.com>
     * @param string $selects
     * @param string $table
     * @param string $field
     * @param string $value
     * @param boolean $return_object
     * @param string $condition
     * @return mixed
     */
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
     * Use this function for runnig DELETE commands
     * @author Howard <howard@realtyna.com>
     * @param string $table
     * @param int $id
     * @param string $condition
     * @return mixed
     */
	public static function delete($table, $id, $condition = '')
	{
		/** first validation **/
		if(trim($table) == '' or (trim($id) == '' and trim($condition) == '')) return false;
		
		if(trim($condition) == '') $condition = " AND `id`='$id'";
		if(trim($condition) == '') return false;
		
		$query = "DELETE FROM `#__$table` WHERE 1 ".$condition;
		return self::q($query, 'delete');
	}
    
    /**
     * Using this function you can update one column from some records in a certain table
     * @author Howard <howard@realtyna.com>
     * @param string $table
     * @param string $where_value
     * @param string $key
     * @param string $value
     * @param string $where_key
     * @return mixed
     */
	public static function set($table, $where_value, $key, $value = '', $where_key = 'id')
	{
		/** first validation **/
		if(trim($table) == '' or trim($where_value) == '' or trim($key) == '' or trim($where_key) == '') return false;
		
		$query = "UPDATE `#__$table` SET `$key`='$value' WHERE `$where_key`='$where_value'";
		return wpl_db::q($query, 'update');
	}
	
    /**
     * For updating some columns from some records in a certain table you can use this function
     * @author Howard <howard@realtyna.com>
     * @param string $table
     * @param array $params
     * @param string $where_key
     * @param string $where_value
     * @return mixed
     */
	public static function update($table, $params = array(), $where_key = 'id', $where_value = '')
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
    
    /**
     * Fetch list of table columns or check existence of a column in a table
     * @author Howard <howard@realtyna.com>
     * @param string $table
     * @param string $column
     * @return mixed
     */
	public static function columns($table = 'wpl_properties', $column = NULL)
	{
		$query = "SHOW COLUMNS FROM `#__".$table."`";
		$results = wpl_db::q($query, "select");
		
		$columns = array();
		foreach($results as $key=>$result) $columns[] = $result->Field;
		
        if(trim($column) and in_array($column, $columns)) return true;
        elseif(trim($column)) return false;
        
		return $columns;
	}
	
    /**
     * Use this function for checking existence of a record on a table
     * @author Howard <howard@realtyna.com>
     * @since 1.9.0
     * @param mixed $value
     * @param string $table
     * @param xtring $column
     * @return int
     */
	public static function exists($value, $table, $column = 'id')
	{
		$query = "SELECT COUNT(*) FROM `#__$table` WHERE `$column`='$value'";
        return self::num($query);
	}
    
    /**
     * Use this function for escaping any variable
     * @author Howard <howard@realtyna.com>
     * @param mixed $parameter
     * @return mixed
     */
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
     * Checks for invalid UTF-8, Convert single < characters to entity, strip all tags, remove line breaks, tabs and extra white space, strip octets. 
     * @author Chris <chris@realtyna.com>
     * @param mixed $input
     * @return mixed
     */
	public static function sanitize($input)
	{
		return sanitize_text_field($input);
	}
    
    /**
     * Returns MySQL Version
     * @author Howard <howard@realtyna.com>
     * @static
     * @return string
     */
    public static function version()
	{
		$query = "SELECT VERSION();";
        return wpl_db::q($query, 'loadResult');
	}
    
    /**
     * Use this function for replacing fake prefix with real one
     * @author Howard <howard@realtyna.com>
     * @param string $query
     * @return string
     */
	public static function _prefix($query)
	{
		$database = self::get_DBO();
		
        if(class_exists('wpl_sql_parser'))
        {
            $sqlParser = wpl_sql_parser::getInstance();
            if($sqlParser->enabled) $query = $sqlParser->parse($query);
            
            $query = str_replace('#__usermeta', $database->base_prefix.'usermeta', $query);
            $query = str_replace('#__users', $database->base_prefix.'users', $query);
            $query = str_replace('#__blogs', $database->base_prefix.'blogs', $query);
            $query = str_replace('#__wpl', $database->base_prefix.'wpl', $query);
            $query = str_replace('#__', $database->prefix, $query);
        }
        else
        {
            $query = str_replace('#__', $database->prefix, $query);
        }
        
		return $query;
	}
	
    /**
     * Use this function for getting database object
     * @author Howard <howard@realtyna.com>
     * @global type $wpdb
     * @return object
     */
	public static function get_DBO()
	{
		global $wpdb;
		return $wpdb;
	}
}