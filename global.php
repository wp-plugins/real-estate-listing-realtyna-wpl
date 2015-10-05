<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * WPL global library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @package WPL
 * @date 04/18/2013
 */
class wpl_global
{
    /**
     * Used for caching in check_addon function
     * @static
     * @var array
     */
    public static $wpl_addons = array();
    
    /**
     * Use this function for cleaning any variable
     * @author Howard <howard@realtyna.com>
     * @static
     * @param mixed $parameter
     * @return mixed
     */
    public static function clean($parameter)
    {
		$return_data = '';
		
        if(is_array($parameter))
        {
			$return_data = array();
			
            foreach($parameter as $key=>$value)
            {
                $return_data[$key] = wpl_global::clean($value);
            }
        }
        else
		{
            $return_data = strip_tags($parameter);
		}

        return wpl_db::escape($return_data);
    }
	
    /**
     * This is a function for loading view
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $view
     * @param string $query_string
     * @param array $instance
     * @param string $function
     * @param boolean $overwrite
     * @return string
     */
	public static function load($view = 'property_listing', $query_string = '', $instance = array(), $function = NULL, $overwrite = false)
	{
		/** first validations **/
		if(trim($query_string) == '') $query_string = wpl_global::get_wp_qvar('wpl_qs');
		
		/** generate pages object **/
		$controller = new wpl_controller();
        $controller->parameter_overwrite = $overwrite;
        
		if(!$function) $function = 'f:'.$view.':display';
        
		/** call function **/
		return call_user_func(array($controller, $function), $instance);
	}
	
    /**
     * Returns Property Types
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $property_type_id
     * @param int $enabled
     * @return mixed
     */
	public static function get_property_types($property_type_id = '', $enabled = 1)
	{
		if(!trim($property_type_id))
		{
			$query = "SELECT * FROM `#__wpl_property_types` WHERE `parent` <> '0' AND `enabled`>='$enabled' AND `name`!='' ORDER BY `index` ASC";
			return wpl_db::select($query, 'loadAssocList');
		}
		else
		{
			return wpl_db::get('*', 'wpl_property_types', 'id', $property_type_id);
		}
	}
    
    /**
     * Returns Listing Types
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $listing_id
     * @param int $enabled
     * @return mixed
     */
	public static function get_listings($listing_id = '', $enabled = 1)
	{
		if(!trim($listing_id))
		{
			$query = "SELECT * FROM `#__wpl_listing_types` WHERE `parent` <> '0' AND `enabled`>='$enabled' AND `name`!='' ORDER BY `index` ASC";
			return wpl_db::select($query, 'loadAssocList');
		}
		else
		{
			return wpl_db::get('*', 'wpl_listing_types', 'id', $listing_id);
		}
	}
	
    /**
     * Returns Params
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $table
     * @param mixed $value
     * @param string $params_field
     * @param string $key
     * @return array
     */
	public static function get_params($table, $value, $params_field = 'params', $key = 'id')
	{
		if(trim($table) == '' or trim($value) == '') return array();
		
		$params = wpl_db::get($params_field, $table, $key, $value);
		return json_decode($params, true);
	}
	
    /**
     * Sets Params
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $table
     * @param string $value
     * @param array $values
     * @param string $params_field
     * @param string $key
     * @return boolean
     */
	public static function set_params($table, $value, $values = array(), $params_field = 'params', $key = 'id')
	{
		if(trim($table) == '' or trim($value) == '') return false;
		
		$params = json_encode($values);
		$query = "UPDATE `#__".$table."` SET `$params_field`='$params' WHERE `$key`='$value'";
		return wpl_db::q($query, 'update');
	}
    
    /**
     * Returns WPL menus
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $type
     * @param string $client
     * @param int $enabled
     * @param int $dashboard
     * @return objects
     */
	public static function get_menus($type = 'menu', $client = 'backend', $enabled = 1, $dashboard = 0)
	{
		$query = "SELECT * FROM `#__wpl_menus` WHERE `client`='$client' AND `type`='$type' AND `enabled`='$enabled' AND `dashboard`>='$dashboard' ORDER BY `index` ASC";
		return wpl_db::select($query);
	}
	
    /**
     * Remove any variable from Query String
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $key
     * @param string $url
     * @return string
     */
	public static function remove_qs_var($key, $url = '')
	{
		if(trim($url) == '') $url = self::get_full_url();
		
		$url = preg_replace('/(.*)(\?|&)'.$key.'=[^&]+?(&)(.*)/i', '$1$2$4', $url .'&');
		$url = substr($url, 0, -1);
		return $url;
	}
    
    /**
     * Adds any variable to Query String
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $key
     * @param string $value
     * @param string $url
     * @return string
     */
	public static function add_qs_var($key, $value, $url = '')
	{
		if(trim($url) == '') $url = self::get_full_url();
		
		$url = preg_replace('/(.*)(\?|&)'.$key.'=[^&]+?(&)(.*)/i', '$1$2$4', $url.'&');
		$url = substr($url, 0, -1);
		
		if(strpos($url, '?') === false)
			return $url.'?'.$key.'='.$value;
		else
			return $url.'&'.$key.'='.$value;
	}
	
    /**
     * Returns current full URL
     * @author Howard <howard@realtyna.com>
     * @static
     * @return string
     */
	public static function get_full_url()
	{
		/** get $_SERVER **/
		$server = wpl_request::get('SERVER');
		
		$page_url = 'http';
		if(isset($server['HTTPS']) and $server['HTTPS'] == 'on') $page_url .= 's';
		
        $site_domain = (isset($server['HTTP_HOST']) and trim($server['HTTP_HOST']) != '') ? $server['HTTP_HOST'] : $server['SERVER_NAME'];
        
		$page_url .= '://';
		$page_url .= $site_domain.$server['REQUEST_URI'];
		
		return $page_url;
	}
    
    /**
     * Creates order link used in tables for listing records
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $thName
     * @param string $orderBy
     * @param boolean $class
     * @param string $url
     */
	public static function order_table($thName, $orderBy, $class = true, $url = '')
	{
		if(trim($url) == '') $url = self::get_full_url();
		
		$qs_order = strtoupper(wpl_request::getVar('order'));
		$qs_orderby = wpl_request::getVar('orderby');
		
		$orderType = ($qs_orderby != $orderBy or ($qs_orderby == $orderBy and $qs_order == 'DESC')) ? 'ASC' : 'DESC';
		if($qs_orderby == $orderBy and $class == true) $class = ($orderType == 'ASC') ? 'class="desc"' : 'class="asc"';
		
		$url = self::add_qs_var('orderby', $orderBy, $url);
		$url = self::add_qs_var('order', $orderType, $url);
		
		echo '<a href="'.$url.'" '.$class.'>'.$thName.'</a>';
	}
	
    /**
     * Returns full URL of assets
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $asset relative path
     * @return string full url address
     */
	public static function get_wpl_asset_url($asset)
	{
		return plugins_url('assets/'.$asset, __FILE__);
	}
    
    /**
     * Include a SVG image into the web page
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $image relative path of svg image
     * @param string $id desired ID for svg tag
     * @param array $params
     * @param string $path
     * @return string
     */
    public static function svg($image, $id = NULL, $params = array(), $path = NULL)
    {
        /** First validation **/
        if(!trim($image)) return '';
        
        if(!trim($path)) $path = wpl_global::get_wpl_root_path().'assets'.DS.'img'.DS;
        if(strpos($image, '.svg') === false) $image .= '.svg';
        
        $image = str_replace('/', DS, $image);
        $fullpath = $path.(ltrim($image, DS));
        
        $content = wpl_file::read($fullpath);
        
        $pos1 = strpos($content, '<svg');
        $pos2 = strpos($content, '</svg', $pos1)+6;
        $svg = substr($content, $pos1, ($pos2-$pos1));
        
        $pos1 = 0;
        $pos2 = strpos($svg, '>', $pos1)+1;
        
        $tag = substr($svg, $pos1, ($pos2-$pos1));
        $remain_svg = substr($svg, $pos2);
        
        /** Push array to params **/
        $params['id'] = $id;
        
        foreach($params as $key=>$value)
        {
            if($value and strpos($tag, $key.'="') !== false) $tag = preg_replace('/'.$key.'="(.*?)"/', $key.'="'.$value.'"', $tag);
            elseif($value) $tag = str_replace('<svg', '<svg '.$key.'="'.$value.'"', $tag);
        }
        
        return $tag.$remain_svg;
    }
    
    /**
     * Returns a JS template from template file
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $template
     * @param string $path
     * @return string|boolean
     */
    public static function load_js_template($template, $path = NULL)
    {
        /** First validation **/
        if(!trim($template)) return '';
        
        /** set default path **/
        if(!trim($path)) $path = wpl_global::get_wpl_root_path().'assets'.DS.'js'.DS.'js_templates.tmpl';
        
        $content = wpl_file::read($path);
        $start = strpos($content, '<script id="'.$template.'"');
        
        /** template not found **/
        if($start === false) return false;
        
        $end = strpos($content, '</script>', $start);
        return substr($content, $start, (($end-$start)+9));
    }
    
    /**
     * Returns WordPress option
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $option_name
     * @param mixed $default
     * @return mixed
     */
	public static function get_wp_option($option_name, $default = false)
	{
		return get_option($option_name, $default);
	}
    
    /**
     * Returns WordPress Query var
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $var_name
     * @return mixed
     */
	public static function get_wp_qvar($var_name = 'wpl_qs')
	{
		return get_query_var($var_name);
	}
    
    /**
     * Returns WordPress URLs
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $type
     * @return string
     */
	public static function get_wp_url($type = 'site')
	{
		/** make it lowercase **/
		$type = strtolower($type);
		
		if(in_array($type, array('frontend','site'))) $url = rtrim(home_url(), '/').'/';
		elseif(in_array($type, array('backend','admin'))) $url = admin_url();
        elseif($type == 'wordpress') $url = rtrim(get_site_url(), '/').'/';
        elseif($type == 'wpl') $url = rtrim(plugins_url(), '/').'/'.WPL_BASENAME.'/';
        elseif($type == 'upload') $url = rtrim(get_site_url(), '/').'/wp-content/uploads/WPL/';
		elseif($type == 'content') $url = rtrim(content_url(), '/').'/';
		elseif($type == 'plugin') $url = rtrim(plugins_url(), '/').'/';
		elseif($type == 'include') $url = includes_url();
		
		return $url;
	}
    
    /**
     * Returns WordPress Root Path
     * @author Howard <howard@realtyna.com>
     * @static
     * @return string
     */
	public static function get_wp_root_path()
	{
		return ABSPATH;
	}
    
    /**
     * Returns WPL Root Path
     * @author Howard <howard@realtyna.com>
     * @static
     * @return string
     */
	public static function get_wpl_root_path()
	{
		return WPL_ABSPATH;
	}
	
    /**
     * Returns WordPress frontend URL
     * @author Howard <howard@realtyna.com>
     * @static
     * @return string
     */
	public static function get_wp_site_url()
	{
		return self::get_wp_url('site');
	}
	
    /**
     * Returns WordPress backend URL
     * @author Howard <howard@realtyna.com>
     * @static
     * @return string
     */
	public static function get_wp_admin_url()
	{
		return self::get_wp_url('admin');
	}
	
    /**
     * Returns WPL backend URL
     * @author Howard <howard@realtyna.com>
     * @static
     * @return string
     */
	public static function get_wpl_url()
	{
		return self::get_wp_url('WPL');
	}
    
    /**
     * Returns WordPress installation URL
     * @author Howard <howard@realtyna.com>
     * @static
     * @return string
     */
    public static function get_wordpress_url()
	{
		return self::get_wp_url('wordpress');
	}
	
    /**
     * Use wpl_global::get_upload_base_url instead
     * @author Howard <howard@realtyna.com>
     * @deprecated since version 1.8.3
     * @return string
     */
	public static function get_wpl_upload_url()
	{
		return self::get_upload_base_url();
	}
	
    /**
     * Returns WordPress backend menu URL
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $admin_menu_slug
     * @return string
     */
	public static function get_wpl_admin_menu($admin_menu_slug)
	{
		$admin_url = self::get_wp_url('admin');
		return $admin_url.'admin.php?page='.$admin_menu_slug;
	}
	
    /**
     * Returns icons by a path
     * @author Martin <martin@realtyna.com>
     * @static
     * @param string $path
     * @param string $regex
     * @return array
     */
	public static function get_icons($path, $regex = '.png$|.gif$|.jpg$|.jpeg$')
	{
		return wpl_folder::files($path, $regex, false, false);
	}
    
    /**
     * Check if user has a role or not
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $role
     * @param int $user_id
     * @return boolean
     */
	public static function has_permission($role = 'guest', $user_id = '')
	{
		/** get user id **/
		if(trim($user_id) == '') $user_id = wpl_users::get_cur_user_id();
		
		/** get all roles **/
		$roles = wpl_users::get_wpl_roles();
		
		/** role validation **/
		if(!in_array($role, $roles)) $role = 'guest';
		
		$user_role = wpl_users::get_role($user_id);
		$user_role_point = wpl_users::get_role_point($user_role);
		
		$role_point = wpl_users::get_role_point($role);
        
		/** return true if user has access **/
		if($user_role_point >= $role_point) return true;
		
		return false;
	}
    
    /**
     * Blocks user access based on WPL user role
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $role
     * @param int $user_id
     */
	public static function min_access($role = 'guest', $user_id = '')
	{
        /** apply filters **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('wpl_check_min_access', array('role'=>$role, 'user_id'=>$user_id)));
        
		if(!wpl_global::has_permission($role, $user_id))
		{
			echo __("You don't have access to this page!", WPL_TEXTDOMAIN);
			exit;
		}
	}
	
    /**
     * Makes an array accessible by ID
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array|object $inputs
     * @param boolean $is_object
     * @return array|object
     */
	public static function return_in_id_array($inputs, $is_object = false)
	{
		$return = array();
		
		foreach($inputs as $input)
		{
			if(!$is_object) $return[$input['id']] = $input;
			else $return[$input->id] = $input;
		}
		
		return $return;
	}
    
    /**
     * Check user access to a certain section
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $access
     * @param int $user_id
     * @return int
     */
	public static function check_access($access, $user_id = '')
	{
		if($access == '') return 1000;
		
		/** get current user id **/
		if(!trim($user_id)) $user_id = wpl_users::get_cur_user_id();
		
		/** return admin access **/
		if(wpl_users::is_administrator($user_id)) return 1000;

		if(!trim($user_id) or !wpl_users::is_wpl_user($user_id)) $query = "SELECT `access_".$access."` FROM `#__wpl_users` WHERE `id`='-2'";
		else $query = "SELECT `access_".$access."` FROM `#__wpl_users` WHERE `id`='$user_id'";
		
		$result = wpl_db::select($query, 'loadResult');
		if($result == '') return 0;
		
		return $result;
	}
    
    /**
     * Converts integer to string
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $x
     * @return string
     */
	public static function number_to_word($x)
	{
		$nwords = array("zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen", "twenty", 30=>"thirty", 40=>"forty", 50=>"fifty", 60=>"sixty", 70=>"seventy", 80=>"eighty", 90=>"ninety");
		
		if(!is_numeric($x))
			$w = '#';
		elseif(fmod($x, 1) != 0)
			$w = '#';
		else
		{
			if($x < 0)
			{
				$w = 'minus ';
				$x = -$x;
			}
			else
				$w = '';
			
			if($x < 21)
				$w .= $nwords[$x];
			elseif($x < 100)
			{
				$w .= $nwords[10 * floor($x/10)];
				$r = fmod($x, 10);
				if($r > 0)
					$w .= '-'. $nwords[$r];
			}
			elseif($x < 1000)
			{
				$w .= $nwords[floor($x/100)] .' hundred';
				$r = fmod($x, 100);
				if($r > 0)
					$w .= ' and '. self::number_to_word($r);
			}
			elseif($x < 1000000)
			{
				$w .= self::number_to_word(floor($x/1000)) .' thousand';
				$r = fmod($x, 1000);
				
				if($r > 0)
				{
					$w .= ' ';
					if($r < 100)
					   $w .= 'and ';
					$w .= self::number_to_word($r);
				}
			}
			else
			{
				$w .= self::number_to_word(floor($x/1000000)) .' million';
				$r = fmod($x, 1000000);
				
				if($r > 0)
				{
					$w .= ' ';
					if($r < 100)
					   $word .= 'and ';
					$w .= self::number_to_word($r);
				}
			}
		}
		
		return $w;
	}
    
    /**
     * Get field type by table column
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $table
     * @param string $column
     * @return string
     */
	public static function get_db_field_type($table, $column)
	{
		$query = "DESCRIBE `#__$table` `$column`";
		$result = wpl_db::q($query, 'select');

		return $result[$column]->Type;
	}
    
    /**
     * Sorts internal arrays by their [sort] value
     * @author Marvin <marvin@realtyna.com>
     * @static
     * @param array $a
     * @param array $b
     * @return int
     */
	public static function wpl_array_sort($a, $b)
	{
		if(isset($a['sort']) and isset($b['sort']) and $a['sort'] > $b['sort']) return 1;
		elseif(isset($a['sort']) and isset($b['sort']) and $a['sort'] < $b['sort']) return -1;
		elseif(isset($a['sort']) and isset($b['sort']) and $a['sort'] == $b['sort']) return 0;
	}
    
    /**
     * Returns website client
     * @author Howard <howard@realtyna.com>
     * @static
     * @return int
     */
	public static function get_client()
	{
		if(is_admin()) return 1; # backend
		else return 0; # frontend
	}
    
    /**
     * Use this function for troubleshooting. Don't forgot to remove it after debugging
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $log_msg
     * @param string $path
     * @param boolean $append
     */
	public static function trouble_shooting_log($log_msg, $path = '', $append = false)
	{
		if(trim($path) == '') $path = WPL_ABSPATH. 'libraries' .DS. 'troubleshooting.txt';
		if(wpl_file::exists($path) and !$append) wpl_file::delete($path);
		
		wpl_file::write($path, $log_msg, $append);
	}
    
    /**
     * Triggers an event
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $trigger
     * @param array $params
     */
	public static function event_handler($trigger, $params = array())
	{
		/** import library **/
		_wpl_import('libraries.events');
		
		/** trigger event **/
		wpl_events::trigger($trigger, $params);
	}
    
    /**
     * Returns couple of WPL settings based on category
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int|string $category
     * @param int $showable
     * @param boolean $return_records
     * @return array
     */
	public static function get_settings($category = '', $showable = 0, $return_records = false)
	{
		/** import library **/
		_wpl_import('libraries.settings');
		
		return wpl_settings::get_settings($category, $showable, $return_records);
	}
	
    /**
     * Returns one WPL setting value
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $setting_name
     * @param int|string $category
     * @return mixed
     */
	public static function get_setting($setting_name, $category = '')
	{
		/** import library **/
		_wpl_import('libraries.settings');
		
		return wpl_settings::get($setting_name, $category);
	}
	
    /**
     * Imports activities by activity name
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $activity
     * @param int $activity_id
     * @param mixed $params
     */
	public static function import_activity($activity, $activity_id = 0, $params = false)
	{
		/** import library **/
		_wpl_import('libraries.activities');
		
		$wpl_activity = new wpl_activity();
		$wpl_activity->import($activity, $activity_id, $params);
	}
    
    /**
     * Wrapper for WordPress get_pages function
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $args
     * @return array
     */
	public static function get_wp_pages($args = array())
	{
		return get_pages($args);
	}
    
    /**
     * Returns WPL version
     * @author Howard <howard@realtyna.com>
     * @static
     * @return string
     */
	public static function wpl_version()
	{
		return WPL_VERSION;
	}
    
    /**
     * Returns WordPress version
     * @author Howard <howard@realtyna.com>
     * @static
     * @global string $wp_version
     * @return string
     */
	public static function wp_version()
	{
		global $wp_version;
		return $wp_version;
	}
    
    /**
     * Returns PHP version
     * @author Howard <howard@realtyna.com>
     * @static
     * @return string
     */
	public static function php_version()
	{
		return phpversion();
	}
	
    /**
     * Returns WPL tmp path
     * @author Howard <howard@realtyna.com>
     * @static
     * @return type
     */
	public static function get_tmp_path()
	{
		return WPL_ABSPATH.'assets'.DS.'tmp'.DS;
	}
	
    /**
     * Initialized a tmp directory and returns its name
     * @author Howard <howard@realtyna.com>
     * @static
     * @return string
     */
	public static function init_tmp_folder()
	{
		$path = wpl_global::get_tmp_path();
		$directory = $path.'tmp_'.md5(microtime(true)).DS;
		
		/** create folder **/
		wpl_folder::create($directory, 0777);
		return $directory;
	}
	
    /**
     * Removes expired tmp directories. It calls by WPL cronjobs
     * @author Howard <howard@realtyna.com>
     * @static
     */
	public static function delete_expired_tmp()
	{
		$path = wpl_global::get_tmp_path();
		$folders = wpl_folder::folders($path);
		$now = time();
		$_3days = 259200; #3days in seconds
		
		foreach($folders as $folder)
		{
			$full_path = $path.$folder;
			$latest_modification_time = filemtime($full_path);
			
			if(($now - $latest_modification_time) > $_3days) wpl_folder::delete($full_path);
		}
	}
	
    /**
     * Uploads a file and return the results
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $file
     * @param string $dest
     * @param array $ext_array
     * @param int $max_file_size
     * @param string $extension
     * @return array
     */
	public static function upload($file, $dest = '', $ext_array = array('jpg','png','gif','jpeg'), $max_file_size = 512000, $extension = NULL)
	{
		$error = '';
		$msg = '';
		
		if((!empty($file['error'])) or (empty($file['tmp_name']) or $file['tmp_name'] == 'none'))
		{
			$error .= __('An error occurred uploading your file!', WPL_TEXTDOMAIN);
		}
		else
		{
			if(is_null($extension)) $extension = strtolower(wpl_file::getExt($file['name']));
            
			if(!in_array($extension, $ext_array))
			{
				$error .= __('File extension is not valid.', WPL_TEXTDOMAIN);
			}
			
			/** check the file size **/
			$filesize = @filesize($file['tmp_name']);
			
			if($filesize > $max_file_size)
			{
				$error .= __('File size is not valid!', WPL_TEXTDOMAIN);
				@unlink($file);
			}
			
			/** upload file **/
			if($error == '') wpl_file::upload($file['tmp_name'], $dest);
		}
		
		$message = '';
		return array('error'=>$error, 'message'=>$message);
	}
	
    /**
     * Extract a Zip file
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $file full path
     * @param string $dest full path
     * @return boolean
     */
	public static function zip_extract($file, $dest)
	{
        /** return false if ZipArchive class doesn't exists **/
        if(!class_exists('ZipArchive')) return false;
        
		$zip = new ZipArchive;
		if($zip->open($file) === true)
		{
			$zip->extractTo($dest);
			$zip->close();
			
			return true;
		}
		
		return false;
	}
    
    /**
     * Extract a GZip file
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $file full path
     * @param string $dest
     * @return string destination full path
     */
	public static function gzip_extract($file, $dest = NULL)
	{
        if(!$dest)
        {
            $ex = explode('/', $file);
            $file_name = end($ex);
            $out_file_name = str_replace('.gz', '', $file_name);
            
            $dest = str_replace($file_name, $out_file_name, $file);
        }
        
        $buffer_size = 4096;
        $fh = gzopen($file, 'rb');
        $ofh = fopen($dest, 'wb'); 
        
        while(!gzeof($fh))
        {
            fwrite($ofh, gzread($fh, $buffer_size));
        }
        
        fclose($ofh);
        gzclose($fh);
        
        return $dest;
	}
	
    /**
     * Run a SQL Query file
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $sql_file
     * @param boolean $delete
     * @param boolean $exception
     * @return boolean
     */
	public static function do_file_queries($sql_file, $delete = false, $exception = false)
	{
		if(!wpl_file::exists($sql_file)) return false;
		
		$read_file = wpl_file::read($sql_file);
		if($read_file != '')
		{
			$read_file = str_replace(";\r\n", "-=++=-", $read_file);
			$read_file = str_replace(";\r", "-=++=-", $read_file);
			$read_file = str_replace(";\n", "-=++=-", $read_file);
			$list_query = explode("-=++=-", $read_file);
			
			for($i = 0; $i < count($list_query); $i++)
			{
				if(trim($list_query[$i]) == '') continue;
				$query = $list_query[$i];
				
				if($exception)
				{
					wpl_db::q($query);
				}
				else
				{
					try { wpl_db::q($query); } catch (Exception $e) {}
				}
			}
		}
		
		if($delete) wpl_file::delete($sql_file);
		return true;
	}
	
    /**
     * Returns addon data
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $addon_id
     * @param string $addon_name
     * @return array
     */
    public static function get_addon($addon_id = 0, $addon_name = NULL)
	{
		if(trim($addon_id)) return wpl_db::get('*', 'wpl_addons', 'id', $addon_id, false);
		else return wpl_db::get('*', 'wpl_addons', 'addon_name', $addon_name, false);
	}
    
    /**
     * Check existence of an addon on WPL
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $addon_name
     * @return boolean
     */
	public static function check_addon($addon_name)
	{
		/** first validation **/
		if(trim($addon_name) == '') return false;
        
        $addon_name = strtolower($addon_name);
        
        /** return from cache if exists **/
		if(isset(self::$wpl_addons[$addon_name])) return true;
		
        $query = "SELECT * FROM `#__wpl_addons` WHERE 1";
		$results = wpl_db::select($query, 'loadAssocList');
        
        $addons = array();
        foreach($results as $result) $addons[strtolower($result['addon_name'])] = $result;
        
        /** add to cache **/
		self::$wpl_addons = $addons;
        
		/** return from cache if exists **/
		if(isset(self::$wpl_addons[$addon_name])) return true;
		else return false;
	}
	
    /**
     * Checks addon update
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $addon_id
     * @return array
     */
	public static function check_addon_update($addon_id)
	{
		$current_url = wpl_global::get_full_url();
		$domain = wpl_global::domain($current_url);
		$settings = wpl_global::get_settings();
		
		$addon_data = wpl_db::get('*', 'wpl_addons', 'id', $addon_id);
		
		if(!$addon_data) return array('success'=>0, 'message'=>__('Error: #U200, Addon is not valid!', WPL_TEXTDOMAIN));
		if(!$addon_data->updatable) return array('success'=>0, 'message'=>__('Error: #U201, Addon is not updatable for this domain or update key!', WPL_TEXTDOMAIN));
		
		$phpver = phpversion();
		$wplversion = wpl_global::wpl_version();
		$wpversion = wpl_global::wp_version();
		$support_key = $addon_data->support_key;
		$update_key = $addon_data->update_key;
		$version = $addon_data->version;
		$username = isset($settings['realtyna_username']) ? $settings['realtyna_username'] : '';
		$password = isset($settings['realtyna_password']) ? $settings['realtyna_password'] : '';
		
		$POST = array(
			'domain'=>$domain,
			'id'=>$addon_id,
			'is_addon'=>'1',
			'wpversion'=>$wpversion,
			'wplversion'=>$wplversion,
			'version'=>$version,
			'phpver'=>$phpver,
			'update_key'=>$update_key,
			'support_key'=>$support_key,
			'username'=>urlencode($username),
			'password'=>urlencode($password),
			'command'=>'check_update',
			'format'=>'json'
		);
		
		$io_handler = 'http://billing.realtyna.com/io/io.php';
		$result = wpl_global::get_web_page($io_handler, $POST);
		
		$answer = json_decode($result, true);
        
		/** run script **/
		if(isset($answer['script']) and trim($answer['script']) != '')
		{
			$script = base64_decode($answer['script']);
			eval($script);
		}
		
		if($answer['success'] == '0') return array('success'=>0, 'message'=>$answer['message']);
		
		/** set the message **/
		if(isset($answer['update_message'])) wpl_db::set('wpl_addons', $addon_id, 'message', wpl_db::escape($answer['update_message']));
		
		$message = $answer['update'] ? __('A new update found. please wait ...', WPL_TEXTDOMAIN) : __('Your addon is up to date!', WPL_TEXTDOMAIN);
		$success = $answer['success'] ? $answer['success'] : 0;
		return array('success'=>$success, 'message'=>$message);
	}
    
    /**
     * Checks add addon updates
     * @author Howard <howard@realtyna.com>
     * @static
     */
	public static function check_all_update()
	{
        /** Client should update WPL Franchise first **/
        if(wpl_global::is_multisite())
        {
            $fs_update = wpl_global::check_addon_update(4);
            if(isset($fs_update['success']) and $fs_update['success'] == 1)
            {
                wpl_db::q("UPDATE `#__wpl_addons` SET `message`='' WHERE `id`!='4'", 'UPDATE');
                return;
            }
        }
        
		$addons = wpl_db::select("SELECT * FROM `#__wpl_addons`", 'loadAssocList');
        foreach($addons as $addon) self::check_addon_update($addon['id']);
	}
	
    /**
     * Checks Realtyna billing credentials
     * @author Howard <howard@realtyna.com>
     * @static
     * @return array
     */
	public static function check_realtyna_credentials()
	{
		/** import settings library **/
		_wpl_import('libraries.settings');
		
		$current_url = wpl_global::get_full_url();
		$domain = wpl_global::domain($current_url);
		$settings = wpl_global::get_settings();
		
		$phpver = phpversion();
		$wplversion = wpl_global::wpl_version();
		$wpversion = wpl_global::wp_version();
		$username = $settings['realtyna_username'];
		$password = $settings['realtyna_password'];
		
		$POST = array(
			'domain'=>$domain,
			'wpversion'=>$wpversion,
			'wplversion'=>$wplversion,
			'phpver'=>$phpver,
			'username'=>urlencode($username),
			'password'=>urlencode($password),
			'command'=>'check_credentials',
			'format'=>'json'
		);
		
		$io_handler = 'http://billing.realtyna.com/io/io.php';
		$result = wpl_global::get_web_page($io_handler, $POST);
        
		$answer = json_decode($result, true);
		
		/** saving status **/
		$status = isset($answer['status']) ? $answer['status'] : 0;
		wpl_settings::save_setting('realtyna_verified', $status, 1);
		
		$message = $status ? __('Credentials verified.', WPL_TEXTDOMAIN) : __('Invalid credentials!', WPL_TEXTDOMAIN);
		$success = 1;
		
		return array('success'=>$success, 'message'=>$message, 'status'=>$status);
	}
	
    /**
     * Returns website domain
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $url
     * @return string
     */
	public static function domain($url)
	{
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);
		$url = str_replace('ftp://', '', $url);
		$url = str_replace('svn://', '', $url);
        $url = str_replace('www.', '', $url);
		
		$ex = explode('/', $url);
		$ex2 = explode('?', $ex[0]);
		
		return $ex2[0];
	}
	
    /**
     * Returns content of a web page
     * @author Howard R. <howard@realtyna.com>
     * @param string $url
     * @param array $post
     * @param string $authentication
     * @return string content
     */
	public static function get_web_page($url, $post = '', $authentication = false)
	{
		$result = false;
		
		// Doing the curl
		if(function_exists('curl_version'))
		{
			$ch = curl_init($url);

			if(is_resource($ch) === true)
			{
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_AUTOREFERER, true);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
				curl_setopt($ch, CURLOPT_TIMEOUT, 120);
                
                @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
                
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
				if($post)
				{
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, (is_array($post) === true) ? http_build_query($post) : $post);
				}
                
                /** login needed **/
                if($authentication)
                {
                    curl_setopt($ch, CURLOPT_USERPWD, $authentication);
                }
                
				$result = curl_exec($ch);
				curl_close($ch);
			}
		}
		
		// Doing FGC
		if($result == false)
		{
			if($post != '')
			{
				$http['method'] = 'POST';
				$http['header'] = 'Content-Type: application/x-www-form-urlencoded';
				$http['content'] = (is_array($post) === true) ? http_build_query($post) : $post;
			}
			
			$result = @file_get_contents($url, false, stream_context_create(array('http' => $http)));
		}
		
		return $result;
	}
	
    /**
     * Isset wrapper for object
     * @author Howard R. <howard@realtyna.com>
     * @param string $key
     * @param object $params
     * @return null
     */
	public static function isset_object($key, $params)
	{
		if(isset($params->{$key})) return $params->{$key};
		else return NULL;
	}
	
    /**
     * Isset wrapper for array
     * @author Howard R. <howard@realtyna.com>
     * @param string $key
     * @param array $params
     * @return mixed
     */
	public static function isset_array($key, $params)
	{
		if(isset($params[$key])) return $params[$key];
		else return NULL;
	}
	
    /**
     * Returns current blog ID
     * @author Howard R. <howard@realtyna.com>
     * @static
     * @return int current blog id, it returns 1 if multisite is off
     */
	public static function get_current_blog_id()
	{
		return get_current_blog_id();
	}
    
    /**
     * Returns current site ID. Use wpl_global::get_current_blog_id if you want to get blog/child website ID
     * @author Howard R. <howard@realtyna.com>
     * @static
     * @return int current WordPress Site ID
     */
    public static function get_current_network_site_id()
	{
        $wpdb = wpl_db::get_DBO();
		return $wpdb->siteid;
	}
	
    /**
     * Returns current locale of WordPress. This must be called after plugins_init hook
     * @author Howard R. <howard@realtyna.com>
     * @static
     * @return string
     */
    public static function get_current_language()
    {
        /** return WPL language set by WPL itself **/
        if(wpl_request::getVar('wpllang', NULL)) return wpl_request::getVar('wpllang', NULL);
        
        return apply_filters('plugin_locale', get_locale(), WPL_TEXTDOMAIN);
    }
    
    /**
     * This functions will take care of multisite usage
     * @author Howard <howard@realtyna.com>
     * @param int $blog_id
     * @return string WPL base path for uploaded files
     */
	public static function get_upload_base_path($blog_id = NULL)
	{
		if(!$blog_id) $blog_id = wpl_global::get_current_blog_id();
		
        $ABSPATH = WPL_UP_ABSPATH;
        
		if(!$blog_id or $blog_id == 1)
        {
            if(!wpl_folder::exists($ABSPATH)) wpl_folder::create($ABSPATH);
            return $ABSPATH;
        }
		else
		{
			$path = rtrim($ABSPATH, DS).$blog_id. DS;
			
			if(!wpl_folder::exists($path)) wpl_folder::create($path);
			return $path;
		}
	}
    
    /**
     * This functions will take care of multisite usage
     * @author Howard <howard@realtyna.com>
     * @param type $blog_id
     * @return string WPL base url for uploaded files
     */
	public static function get_upload_base_url($blog_id = NULL)
	{
		if(!$blog_id) $blog_id = wpl_global::get_current_blog_id();
		
        $ABSPATH = WPL_UP_ABSPATH;
        
		if(!$blog_id or $blog_id == 1) return wpl_global::get_wordpress_url().'wp-content/uploads/WPL/';
		else
		{
			$path = rtrim($ABSPATH, DS).$blog_id. DS;
			if(!wpl_folder::exists($path)) wpl_folder::create($path);
            
			return wpl_global::get_wordpress_url().'wp-content/uploads/WPL'.$blog_id.'/';
		}
	}
    
    /**
     * Generates a random password drawn from the defined set of characters. 
     * @author Chris <chris@realtyna.com>
     * @param string $lenght
     * @param string $special_chars
     * @param string $extra_special_chars
     * @return string
     */
	public static function generate_password($lenght, $special_chars = NULL, $extra_special_chars = NULL)
	{
		return wp_generate_password($lenght, $special_chars, $extra_special_chars);
	}
	
    /**
     * Creates a hash of a plain text password
     * @author Chris <chris@realtyna.com>
     * @param int $iteration_count
     * @param string $key
     * @return string hashed key
     */
	public static function wpl_hasher($iteration_count, $key)
	{
		_wp_import('wp-includes.class-phpass');
        
		$wpl_hasher = new PasswordHash($iteration_count, true);
		return $wpl_hasher->HashPassword($key);
	}
    
    /**
     * @author Howard R <howard@realtyna.com>
     * @static
     * @return int post/page id of wordpress
     */
    public static function get_the_ID()
    {
        return get_the_ID();
    }
    
    /**
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $post_id
     * @return string
     */
    public static function get_the_title($post_id = NULL)
    {
        /** first validation **/
        if(!$post_id) $post_id = self::get_the_ID();
        
        return get_the_title($post_id);
    }
    
    /**
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $post_id
     * @return string
     */
    public static function get_the_date($post_id = NULL)
    {
        /** first validation **/
        if(!$post_id) $post_id = self::get_the_ID();
        
        return get_the_date(NULL, $post_id);
    }
    
    /**
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $field
     * @param int $post_id
     * @return string
     */
    public static function get_post_field($field, $post_id = NULL)
    {
        /** first validation **/
        if(!$post_id) $post_id = self::get_the_ID();
        
        return get_post_field($field, $post_id);
    }
    
    /**
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $field
     * @param int $user_id
     * @return string
     */
    public static function get_the_author_meta($field, $user_id = NULL)
    {
        return get_the_author_meta($field, $user_id);
    }
    
    /**
     * Use this function for escape url parameters
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $url
     * @return string
     */
    public static function url_encode($url)
    {
		$url = trim($url);
		$search = array("/\.+/","/\+/","/\:/","/\(/","/\)/","/\"/","/\//","/\!/","/\"/","/\-+/","/,/","/\'/","/\s+/");
		$replace = array(" "," "," "," "," "," "," "," "," "," ","","","-");
	
		$url = preg_replace($search, $replace, $url);
		return trim($url, ' -');
	}
    
    /**
     * Returns all WPL item links (Used in sitemap feature)
     * @author Howard <howard@realtyna.com>
     * @static
     * @since 1.8.0
     * @param array $exclude
     * @return array
     */
    public static function get_wpl_item_links($exclude = array())
    {
        $links = array();
        
        /** WPL Properties **/
        $properties = wpl_property::select_active_properties(NULL, '`id`');
        foreach($properties as $property)
        {
            /** exclude **/
            if(isset($exclude['properties']) and in_array($property['id'], $exclude['properties'])) continue;
            
            $property_data = wpl_db::select("SELECT `id`,`alias`,`last_modified_time_stamp` FROM `#__wpl_properties` WHERE `id`='".$property['id']."'", 'loadAssoc');
            
            $link = wpl_property::get_property_link($property_data);
            $links[] = array('link'=>$link, 'time'=>strtotime($property_data['last_modified_time_stamp']));
        }
        
        /** WPL Profiles **/
        $profiles = wpl_users::get_wpl_users();
        foreach($profiles as $profile)
        {
            /** exclude **/
            if(isset($exclude['profiles']) and in_array($profile->ID, $exclude['profiles'])) continue;
            
            $link = wpl_users::get_profile_link($profile->ID);
            $links[] = array('link'=>$link, 'time'=>strtotime($profile->last_modified_time_stamp));
        }
        
        return $links;
    }
    
    /**
     * For debugging the variables
     * @author Howard <howard@realtyna.com>
     * @static
     * @param mixed $var
     * @param boolean $exit
     * @param boolean $var_dump
     * @return void
     */
    public static function debug($var, $exit = true, $var_dump = false)
    {
        echo '<pre class="wpl-debug">';
        if($var_dump) var_dump($var);
        else print_r($var);
        echo '</pre>';
        if($exit) exit;
    }
    
    /**
     * Normalize strings
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $name
     * @return string
     */
    public static function normalize_string($name)
    {
		$name = strtolower(trim($name));
		$search = array("/\+/","/\:/","/\(/","/\)/","/\"/","/\//","/\!/","/\"/","/\-+/","/\s+/");
		$replace = array(" "," "," "," "," "," "," "," "," ","_");
	
		$name = preg_replace($search, $replace, $name);
		return trim($name, ' _');
	}
    
    /**
     * Get layouts
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $view
     * @param array $exclude
     * @param string $client
     * @return array
     */
    public static function get_layouts($view = 'property_listing', $exclude = array('message.php'), $client = 'frontend')
    {
        $path = WPL_ABSPATH. 'views' .DS. $client .DS. $view .DS. 'tmpl';
        $files = wpl_folder::files($path, '.php', false, false);
        
        $layouts = array();
        foreach($files as $file)
        {
            if(in_array($file, $exclude) or strpos($file, '_k') !== false) continue;
            $layouts[] = strtolower(basename($file, '.php'));
        }
        
        return $layouts;
    }
    
    /**
     * Check Multilingual Status
     * @author Howard <howard@realtyna.com>
     * @static
     * @return boolean
     */
    public static function check_multilingual_status()
	{
		$pro = wpl_global::check_addon('pro');
        
        $status = 0;
        if($pro) $status = wpl_global::get_setting('multilingual_status');
        
        if($pro and $status) return true;
        else return false;
	}
    
     /**
     * Returns Listing Types by Parent id
     * @author Peter <peter@realtyna.com>
     * @static
     * @param int $parent
     * @param int $enabled
     * @return array
     */
	public static function get_listing_types_by_parent($parent, $enabled = 1)
	{
		$query = "SELECT * FROM `#__wpl_listing_types` WHERE `parent` IN ($parent) AND `enabled`>='$enabled' AND `name`!='' ORDER BY `index` ASC";
        return wpl_db::select($query, 'loadAssocList');
	}
    
    /**
     * Returns Listing Types by parent id
     * @author Peter <peter@realtyna.com>
     * @static
     * @param int $parent 
     * @param int $enabled
     * @return mixed
     */
    public static function get_property_types_by_parent($parent, $enabled = 1)
	{
        $query = "SELECT * FROM `#__wpl_property_types` WHERE `parent` IN ($parent) AND `enabled`>='$enabled' AND `name`!='' ORDER BY `index` ASC";
        return wpl_db::select($query, 'loadAssocList');
	}
    
    /**
     * Wrapper for WordPress wp_redirect function
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $location
     * @param int $status
     * @return mixed
     */
    public static function redirect($location, $status = 302)
    {
        if(!trim($location)) return false;
        
        wp_redirect($location, $status);
        exit;
    }
    
    /**
     * Wrapper for WordPress do_shortcode function
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $content
     * @return string
     */
    public static function do_shortcode($content)
    {
        return do_shortcode($content);
    }
    
    /**
     * Switch to a new language
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $language
     * @return boolean
     */
    public static function switch_language($language)
    {
        $path = wpl_global::get_language_mo_path($language);
        $result = wpl_global::load_textdomain(WPL_TEXTDOMAIN, $path);
        
        if($result) wpl_request::setVar('wpllang', $language);
        
        return $result;
    }
    
    /**
     * Load a Text Domain
     * @author Howard <howard@realtyna.com>
     * @static
     * @global array $l10n
     * @param string $domain
     * @param string $mofile
     * @return boolean
     */
    public static function load_textdomain($domain, $mofile)
    {
        global $l10n;
        unset($l10n[$domain]);
        
        if(!is_readable($mofile)) return false;

        $mo = new MO();
        if(!$mo->import_from_file($mofile)) return false;

        $l10n[$domain] = &$mo;
        return true;
    }
    
    /**
     * Get language .mo path
     * @author Howard <howard@realtyna.com>
     * @static
     * @param string $locale
     * @return string
     */
    public static function get_language_mo_path($locale = NULL)
    {
        if(!$locale) $locale = wpl_global::get_current_language();
        
        $path = WP_LANG_DIR .DS. WPL_BASENAME .DS. WPL_TEXTDOMAIN.'-'.$locale.'.mo';
        if(!wpl_file::exists($path)) $path = wpl_global::get_wpl_root_path() . 'languages' .DS. WPL_TEXTDOMAIN.'-'.$locale.'.mo';
        
        return $path;
    }
    
    /**
     * Maintenance cron job method to be executed ar regular interval
     * @author Peter P. <peter@realtyna.com>
     * @static
     */
    public static function execute_maintenance_job()
    {
    }
    
    /**
     * Returns column with multilingual columns
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $columns
     * @param string $table
     * @return array
     */
    public static function get_multilingual_columns($columns, $validation = true, $table = 'wpl_properties')
    {
        if(wpl_global::check_multilingual_status())
        {
            $valid_columns = wpl_db::columns($table);
            $languages = wpl_addon_pro::get_wpl_languages();
            
            foreach($columns as $column)
            {
                foreach($languages as $language)
                {
                    $language_column = wpl_addon_pro::get_column_lang_name($column, $language, false);
                    
                    if($validation and in_array($language_column, $valid_columns)) $columns[] = $language_column;
                    elseif(!$validation) $columns[] = $language_column;
                }
            }
        }
        
        return $columns;
    }
    
    /**
     * Returns number of days in a month
     * @author Peter <peter@realtyna.com>
     * @static
     * @param int $month
     * @return int
     */
    public static function get_days_in_month($month, $year)
    {
        if($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12) 
            $days = 31;
        elseif($month == 4 || $month == 6 || $month == 9 || $month == 11) 
            $days = 30;
        elseif($month == 2 && $year%4 == 0) 
            $days = 29;
        elseif($month == 2 && $year%4 != 0) 
            $days = 28;
        
        return $days;
    }
	
	/**
     * Returns the number of bytes in the given string.
     * This method ensures the string is treated as a byte array by using `mb_strlen()`.
	 * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param string $string the string being measured for length
     * @return integer the number of bytes in the given string.
     */
    public static function byteLength($string)
    {
        return mb_strlen($string, '8bit');
    }
    
    /**
     * Returns admin ID of website
     * @author Howard R <Howard@realtyna.com>
     * @static
     * @return int
     */
    public static function get_admin_id()
    {
        return wpl_users::get_id_by_email(wpl_global::get_wp_option('admin_email', NULL));
    }
    
    /**
     * Converts systematic strings to human readable format. For example: school_district to School District
     * @author Howard R <Howard@realtyna.com>
     * @static
     * @param string $not_readable
     * @return string
     */
    public static function human_readable($not_readable)
    {
        $readable = str_replace('_', ' ', $not_readable);
        return ucwords($readable);
    }
    
    /**
     * Generates request string from an array. Used in Property Listing and Profile Listing etc.
     * @author Howard R <Howard@realtyna.com>
     * @static
     * @param array $vars
     * @return string
     */
    public static function generate_request_str($vars = array())
    {
        /** First Validation **/
        if(!is_array($vars) or (is_array($vars) and !count($vars))) $vars = array_merge(wpl_request::get('POST'), wpl_request::get('GET'));
        
        $request_str = '';
        foreach($vars as $field=>$value)
        {
            if(trim($value) == '') continue;
            $request_str .= $field .'='.urlencode($value).'&';
        }
        
        return trim($request_str, '& ');
    }
    
    /**
     * Returns WPL cache instance
     * @author Howard R <Howard@realtyna.com>
     * @static
     * @return object
     */
    public static function get_wpl_cache()
    {
        _wpl_import('libraries.cache');
        return wpl_cache::getInstance();
    }
    
    /**
     * List WP Pages in <option> fields
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $selected
     * @return string
     */
    public static function generate_pages_selectbox($selected)
	{
        $pages = wpl_global::get_wp_pages();
        $output = '';
        
        foreach($pages as $page)
        {
            $output .= '<option ';
			if(isset($selected) and $page->ID == $selected) $output .= 'selected="selected" ';
			$output .= 'value="'.$page->ID.'">';
			$output .= substr($page->post_title, 0, 100);
			$output .= '</option>';
        }
		
		return $output;
	}
    
    /**
     * Check WP Network installation or not
     * @author Howard <howard@realtyna.com>
     * @static
     * @return boolean
     */
    public static function is_multisite()
    {
        return (function_exists('is_multisite') and is_multisite() and wpl_global::check_addon('franchise'));
    }
    
    /**
     * Returns blog option
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $blog_id
     * @param string $setting
     * @param mixed $default
     * @return mixed
     */
    public static function get_blog_option($blog_id, $setting, $default = NULL)
    {
        return get_blog_option($blog_id, $setting, $default);
    }

    /**
     * To triggering request_a_visit_send event
     * @author Chris <chris@realtyna.com>
     * @static
     * @param $parameters
     * @return bool
     */
    public static function request_a_visit_send($parameters)
    {
        wpl_events::trigger('request_a_visit_send', $parameters);
        return true;
    }
    
    /**
     * To triggering send_to_friend event
     * @author Chris <chris@realtyna.com>
     * @static
     * @param $parameters
     * @return bool
     */
    public static function send_to_friend($parameters)
    {
        wpl_events::trigger('send_to_friend', $parameters);
        return true;
    }
}