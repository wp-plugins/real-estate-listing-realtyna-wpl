<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Settings Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 03/01/2013
 * @package WPL
 */
class wpl_settings
{
    /**
     * Used for caching in get_settings function
     * @static
     * @var array 
     */
	public static $wpl_settings = array();
	
    /**
     * Get settings
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $category
     * @param int $showable
     * @param boolean $return_records
     * @return array
     */
	public static function get_settings($category = '', $showable = 0, $return_records = false)
	{
		/** return from cache if exists **/
		$cache_key = trim($category) != '' ? $category : 'all';
		if(isset(self::$wpl_settings[$cache_key]) and !$return_records) return self::$wpl_settings[$cache_key];
		
		$condition = '';
		if(trim($category) != '')
		{
			if(!is_numeric($category)) $category = wpl_settings::get_category_id($category);
			$condition .= " AND `category`='$category'";
		}
		
		$condition .= " AND `showable`>='$showable'";
		
		$query = "SELECT * FROM `#__wpl_settings` WHERE 1 ".$condition." ORDER BY `index` ASC";
		$records = wpl_db::select($query);
		
		if($return_records)
		{
			return $records;
		}
		
		$settings = array();
		foreach($records as $record)
		{
			$settings[$record->setting_name] = $record->setting_value;
		}
		
		/** add to cache **/
		self::$wpl_settings[$cache_key] = $settings;
		return $settings;
	}
    
    /**
     * This function takes care for modifying existing setting or inserting new record
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $name
     * @param mixed $value
     * @param string $category
     * @param string $condition
     * @return boolean
     */
	public static function save_setting($name, $value = '', $category = '', $condition = '')
	{
		/** first validation **/
		if(trim($name) == '') return false;
		
		$exists = wpl_settings::is_setting_exists($name, $category);
		
		if($exists) $result = wpl_settings::update_setting($name, $value, $category, $condition);
		else $result = wpl_settings::insert_setting($name, $value, $category);
		
		return $result;
	}
	
    /**
     * Updates settings
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $name
     * @param mixed $value
     * @param string $category
     * @param string $condition
     * @return boolean
     */
	public static function update_setting($name, $value = '', $category = '', $condition = '')
	{
		/** first validation **/
		if(trim($name) == '') return false;
		
		if(trim($condition) == '' and trim($category) != '')
		{
			if(!is_numeric($category)) $category = wpl_settings::get_category_id($category);
			$condition .= "AND `category`='$category'";
		}
		
		$query = "UPDATE `#__wpl_settings` SET `setting_value`='$value' WHERE `setting_name`='$name' ".$condition;
		$result = wpl_db::q($query, 'update');
        
        /** trigger event **/
        wpl_global::event_handler('settings_updated', array('setting_value'=>$value, 'setting_name'=>$name));
        
        return $result;
	}
    
    /**
     * Inserts a new setting
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $name
     * @param mixed $value
     * @param string $category
     * @return boolean
     */
	public static function insert_setting($name, $value = '', $category = '')
	{
		/** first validation **/
		if(trim($name) == '') return false;
		
		$category_id = 1;
		if(trim($category) != '')
		{
			if(!is_numeric($category)) $category_id = wpl_settings::get_category_id($category);
			else $category_id = $category;
		}
		
		$query = "INSERT INTO `#__wpl_settings` (`setting_name`,`setting_value`,`category`) VALUES ('$name','$value','$category_id')";
		$id = wpl_db::q($query, 'insert');
        
        /** trigger event **/
        wpl_global::event_handler('settings_added', array('id'=>$id));
        
        return $id;
	}
	
    /**
     * Get one settings
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $setting_name
     * @param string $category
     * @return mixed
     */
	public static function get($setting_name, $category = '')
	{
		/** return from cache if exists **/
		$cache_key = trim($category) != '' ? $category : 'all';
		if(isset(self::$wpl_settings[$cache_key][$setting_name])) return self::$wpl_settings[$cache_key][$setting_name];
		
		$condition = "`setting_name`='$setting_name' ";
		if(trim($category) != '')
		{
			if(!is_numeric($category)) $category = wpl_settings::get_category_id($category);
			$condition .= " AND `category`='$category' ";
		}
		
		/** get settings **/
		return wpl_db::get('setting_value', 'wpl_settings', '', '', true, $condition);
	}
	
    /**
     * Returns category id by category name
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $category
     * @return int
     */
	public static function get_category_id($category)
	{
		$category = strtolower($category);
		$query = "SELECT `id` FROM `#__wpl_setting_categories` WHERE LOWER(name)='$category'";
		
		return wpl_db::select($query, 'loadResult');
	}
	
    /**
     * Get setting categories
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $showable
     * @return object
     */
	public static function get_categories($showable = 1)
	{
		$query = "SELECT * FROM `#__wpl_setting_categories` WHERE `showable`>='$showable' ORDER BY `index` ASC";
		return wpl_db::select($query, 'loadObjectList');
	}
	
    /**
     * Check if setting exists or not
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $name
     * @param string $category
     * @return boolean
     */
	public static function is_setting_exists($name, $category = '')
	{
		$condition = '';
		
		if(trim($category) != '')
		{
			if(!is_numeric($category)) $category = wpl_settings::get_category_id($category);
			$condition .= "AND `category`='$category'";
		}
		
		$query = "SELECT COUNT(`id`) FROM `#__wpl_settings` WHERE `setting_name`='$name' ".$condition;
		$num = wpl_db::num($query);
		
		return ($num ? true : false);
	}
	
    /**
     * Generate setting field
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $setting_record
     * @return void
     */
	public static function generate_setting_form($setting_record)
	{
		/** first validation **/
		if(!$setting_record) return;
		
		$done_this = false;
		$type = $setting_record->type;
		$value = $setting_record->setting_value;
		$params = json_decode($setting_record->params, true);
		$options = json_decode($setting_record->options, true);
		
		$setting_title = trim($setting_record->title) != '' ? __($setting_record->title, WPL_TEXTDOMAIN) : __(str_replace('_', ' ', $setting_record->setting_name), WPL_TEXTDOMAIN);
		
		/** get files **/
		$path = WPL_ABSPATH .DS. 'libraries' .DS. 'settings_form';
		$files = array();
		
		if(wpl_folder::exists($path))
		{
			$files = wpl_folder::files($path, '.php$');
			
			foreach($files as $file)
			{
				include($path .DS. $file);
			}
		}
	}
	
    /**
     * Generates settings form (All the fields)
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $setting_records
     * @return void
     */
	public static function generate_setting_forms($setting_records)
	{
		/** first validation **/
		if(!$setting_records) return;
		
		foreach($setting_records as $key=>$setting_record)
		{
			self::generate_setting_form($setting_record);
		}
	}
    
    /**
     * Removes WPL cached data
     * @author Howard <howard@realtyna.com>
     * @static
     * @param type $cache_type
     * @return boolean
     */
	public static function clear_cache($cache_type = 'all')
	{
		/** first validation **/
		$cache_type = strtolower($cache_type);
		if(trim($cache_type) == '') return false;
		
        /** import libraries **/
        _wpl_import('libraries.property');
        _wpl_import('libraries.items');
        
        if($cache_type == 'unfinalized_properties' or $cache_type == 'all')
		{
            $properties = wpl_db::select("SELECT `id` FROM `#__wpl_properties` WHERE `finalized`='0'", 'loadAssocList');
            foreach($properties as $property) wpl_property::purge($property['id']);
		}
        
		if($cache_type == 'properties_cached_data' or $cache_type == 'all')
		{
            $q = " `location_text`='', `rendered`='', `alias`=''";
            if(wpl_global::check_multilingual_status()) $q = self::get_multilingual_query(array('alias', 'location_text', 'rendered'));
            
			$query = "UPDATE `#__wpl_properties` SET ".$q;
			wpl_db::q($query);
		}
        
        if($cache_type == 'location_texts' or $cache_type == 'all')
		{
            $q = " `location_text`=''";
            if(wpl_global::check_multilingual_status()) $q = self::get_multilingual_query(array('location_text'));
            
			$query = "UPDATE `#__wpl_properties` SET ".$q;
			wpl_db::q($query);
		}
        
        if($cache_type == 'listings_thumbnails' or $cache_type == 'all')
		{
			$properties = wpl_db::select("SELECT `id`, `kind` FROM `#__wpl_properties` WHERE `id`>0", 'loadAssocList');
            $ext_array = array('jpg', 'jpeg', 'gif', 'png');
            
            foreach($properties as $property)
            {
                $path = wpl_items::get_path($property['id'], $property['kind']);
                $thumbnails = wpl_folder::files($path, 'th.*\.('.implode('|', $ext_array).')$', 3, true);
                
                foreach($thumbnails as $thumbnail) wpl_file::delete($thumbnail);
            }
		}
        
        if($cache_type == 'users_cached_data' or $cache_type == 'all')
		{
            $q = " `location_text`='', `rendered`=''";
            if(wpl_global::check_multilingual_status()) $q = self::get_multilingual_query(array('location_text', 'rendered'), 'wpl_users');
            
			$query = "UPDATE `#__wpl_users` SET ".$q;
			wpl_db::q($query);
		}
        
        if($cache_type == 'users_thumbnails' or $cache_type == 'all')
		{
			$users = wpl_db::select("SELECT `id` FROM `#__wpl_users` WHERE `id`>0", 'loadAssocList');
            $ext_array = array('jpg', 'jpeg', 'gif', 'png');
            
            foreach($users as $user)
            {
                $path = wpl_items::get_path($user['id'], 2);
                $thumbnails = wpl_folder::files($path, 'th.*\.('.implode('|', $ext_array).')$', 3, true);
                
                foreach($thumbnails as $thumbnail) wpl_file::delete($thumbnail);
                
                /** delete email images **/
                if(wpl_file::exists($path.'main_email.png')) wpl_file::delete($path.'main_email.png');
                if(wpl_file::exists($path.'second_email.png')) wpl_file::delete($path.'second_email.png');
            }
		}
        
        if($cache_type == 'wpl_cache_directory' or $cache_type == 'all')
		{
            $cache = wpl_global::get_wpl_cache();
            $directories = wpl_folder::folders($cache->get_path());
            
            foreach($directories as $directory)
            {
                wpl_folder::delete($cache->get_path().DS.$directory.DS);
            }
		}
		
        /** trigger event **/
        wpl_global::event_handler('cache_cleared', array('cache_type'=>$cache_type));
		return true;
	}
    
    /**
     * Get Multilingual columns for clear cache queries
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $columns
     * @param string $table
     * @return string
     */
    public static function get_multilingual_query($columns = array(), $table = 'wpl_properties')
    {
        $q = "";
        
        $columns = wpl_global::get_multilingual_columns($columns, $table);
        foreach($columns as $column) $q .= "`$column`='', ";
        
        return trim($q, ', ');
    }

    /**
     * Import Settings from a file
     * @author Steve A. <steve@realtyna.com>
     * @static
     * @param  string  $file Settings File
     * @return boolean		 Result
     */
    public static function import_settings($file)
    {
    	$content = wpl_file::read($file);
    	$ext = wpl_file::getExt($file);

    	if($ext == 'json')
    	{
    		$settings = json_decode($content);
	    	if(!$settings) return false;
    	}
    	elseif($ext == 'xml')
    	{
    		$settings = simplexml_load_string($content);
			if(!$settings) return false;
            
			$settings = (array) $settings;
    	}
    	else return false;

    	foreach($settings as $name=>$value) self::update_setting($name, $value);
    	return true;
    }

    /**
     * Export Settings to a file
     * @author Steve A. <steve@realtyna.com>
     * @static
     * @param  string $format File Format
     * @return object 		  Settings File
     */
    public static function export_settings($format = 'json')
    {
    	$settings = self::get_settings();

    	if($format == 'json') return json_encode($settings);
    	elseif($format == 'xml')
    	{
    		$xml = new SimpleXMLElement('<wplsettings/>');
    		foreach($settings as $k=>$v) $xml->addChild($k, $v);
            
		    return $xml->asXML();
    	}
    	else return NULL;
    }
}