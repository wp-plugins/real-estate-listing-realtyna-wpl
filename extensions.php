<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** Extensions Library
** Developed 03/16/2013
**/

class wpl_extensions
{
	var $extensions;
	
	/**
		@inputs {extension_id}
		@description for getting an extension
		@author Howard
	**/
	public function get_extension($extension_id)
	{
		$results = wpl_db::get('*', 'wpl_extensions', 'id', $extension_id);
		return $results;
	}
	
	/**
		@inputs [enabled] and [extension type]
		@description for getting extensions
		@author Howard
	**/
	public function get_extensions($enabled = 1, $type = '', $client = '')
	{
		$query = "SELECT * FROM `#__wpl_extensions` WHERE `enabled`>='$enabled' ".(trim($type) != '' ? "AND `type`='$type'" : "")." ".(trim($client) != '' ? "AND (`client`='$client' OR `client`='2')" : "")." ORDER BY `index` ASC";
		$this->extensions = wpl_db::select($query);
		
		return $this->extensions;
	}
	
	/**
		@inputs void
		@description for importing extensions automatically
		@author Howard
	**/
	public function import_extensions()
	{
		if(!$this->extensions) return;
		
		foreach($this->extensions as $extension)
		{
			if($extension->type == 'action') $this->import_action($extension);
			elseif($extension->type == 'shortcode') $this->import_shortcode($extension);
			elseif($extension->type == 'library') $this->import_library($extension);
			elseif($extension->type == 'widget') $this->import_widget($extension);
			elseif($extension->type == 'service') $this->import_service($extension);
            elseif($extension->type == 'sidebar') $this->import_sidebar($extension);
		}
	}
	
	/**
		@inputs enabled
		@description for getting extension types
		@author Howard
	**/
	public function get_extensions_types($enabled = 0)
	{
		$query = "SELECT `id`, `type` FROM `#__wpl_extensions` WHERE `enabled`>='$enabled' GROUP BY `type` ORDER BY `type` ASC";
		return $extension_types = wpl_db::select($query);
	}
	
	/**
		@inputs object $extension
		@description for importing extension
		@author Howard
	**/
	public function import_action($extension)
	{
		if(strpos($extension->param2, '->') === false)
		{
			add_action($extension->param1, $extension->param2);
		}
		else
		{
			$ex = explode('->', $extension->param2);
			$class_name = $ex[0];
			
			/** generate object **/
			$class_obj = new $class_name();
			$function_name = $ex[1];
			$priority = trim($extension->param3) != '' ? $extension->param3 : 10;
			
			add_action($extension->param1, array($class_obj, $function_name), $priority);
		}
	}
	
	/**
		@inputs object $extension
		@description for importing extension
		@author Howard
	**/
	public function import_shortcode($extension)
	{
		if(strpos($extension->param2, '->') === false)
		{
			add_shortcode($extension->param1, $extension->param2);
		}
		else
		{
			$ex = explode('->', $extension->param2);
			$class_name = $ex[0];
			
			/** generate object **/
			$class_obj = new $class_name();
			$function_name = $ex[1];
			
			add_shortcode($extension->param1, array($class_obj, $function_name));
		}
	}
	
	/**
		@inputs object $extension
		@description for importing extension
		@author Howard
	**/
	public static function import_style($extension)
	{
		/** render style_url **/
		$style_url = (isset($extension->external) or (isset($extension->param5) and trim($extension->param5))) ? $extension->param2 : wpl_global::get_wpl_asset_url($extension->param2);
		
		if(trim($extension->param2) != '') wp_register_style($extension->param1, $style_url);
    	wp_enqueue_style($extension->param1);
	}
	
	/**
		@inputs object $extension
		@description for importing extension
		@author Howard
	**/
	public static function import_javascript($extension)
	{
		/** render script_url **/
		$script_url = (isset($extension->external) or (isset($extension->param5) and trim($extension->param5))) ? $extension->param2 : wpl_global::get_wpl_asset_url($extension->param2);
		
		if(trim($extension->param2) != '') wp_register_script($extension->param1, $script_url);
	    wp_enqueue_script($extension->param1);
	}
	
	/**
		@inputs object $extension
		@description for importing extension
		@author Howard
	**/
	public function import_library($extension)
	{
		$function_name = $extension->param2;
		$function_name($extension->param1);
	}
	
	/**
		@inputs object $extension
		@description for importing extension
		@author Howard
	**/
	public function import_widget($extension)
	{
		$path = _wpl_import($extension->param1, true, true);
		
		if(wpl_file::exists($path))
		{
			require_once $path;
			add_action($extension->param2, create_function('', 'register_widget("'.$extension->param3.'");'));
		}
	}
	
	/**
		@inputs object $extension
		@description for importing extension
		@author Howard
	**/
	public function import_service($extension)
	{
		$ex = explode('->', $extension->param2);
		$class_file = $ex[0];
		$class_name = 'wpl_service_'.$ex[0];
		
		/** first validation **/
		if(trim($class_file) == '') return false;
		
		/** generate object **/
		_wpl_import('libraries.services.'.$class_file);
		$class_obj = new $class_name();
		$function_name = $ex[1];
		$priority = trim($extension->param3) != '' ? $extension->param3 : 10;
		
		add_action($extension->param1, array($class_obj, $function_name), $priority);
	}
	
	/**
		@inputs void
		@description for importing language
		@author Howard
	**/
	public function import_language()
	{
		$overriden_language_filepath = WPL_ABSPATH .DS. 'languages' .DS. 'overrides' .DS. WPL_TEXTDOMAIN .'-'. WPLANG .'.mo';
		
		/** check if the language file is overridden **/
		if(wpl_file::exists($overriden_language_filepath))
			load_plugin_textdomain(WPL_TEXTDOMAIN, false, dirname(plugin_basename( __FILE__ )) .DS. 'languages' .DS. 'overrides');
		else
			load_plugin_textdomain(WPL_TEXTDOMAIN, false, dirname(plugin_basename( __FILE__ )) .DS. 'languages');
	}
	
	/**
		@inputs void
		@description for importing permalink
		@author Howard
	**/
	public function import_permalink()
	{
		add_action('wp_loaded', array($this, 'wpl_flush_rules'), 1);
		add_filter('rewrite_rules_array', array($this, 'wpl_insert_rewrite_rules'));
		add_filter('query_vars', array($this, 'wpl_insert_query_vars'));
		
		$sef = new wpl_sef();
		add_shortcode('WPL', array($sef, 'process'));
	}
	
    /**
		@inputs object $extension
		@description for importing sidebar
		@author Howard
	**/
	public function import_sidebar($extension)
	{
        $name = isset($extension->title) ? $extension->title : 'WPL sidebar';
        $id = isset($extension->param1) ? $extension->param1 : 'wpl-sidebar-id';
        $description = isset($extension->description) ? $extension->description : 'WPL sidebar description';
        $before_widget = isset($extension->param2) ? $extension->param2 : '<aside id="%1$s" class="widget %2$s">';
        $after_widget = isset($extension->param3) ? $extension->param3 : '</aside>';
        $before_title = isset($extension->param4) ? $extension->param4 : '<h3 class="widget-title">';
        $after_title = isset($extension->param5) ? $extension->param5 : '</h3>';
        
		register_sidebar(array(
			'name'          => __($name, WPL_TEXTDOMAIN),
			'id'            => $id,
			'description'   => __($description, WPL_TEXTDOMAIN),
			'before_widget' => $before_widget,
			'after_widget'  => $after_widget,
			'before_title'  => $before_title,
			'after_title'   => $after_title,
		));
	}
    
	/**
		@inputs void
		@description flush_rules() if our rules are not yet included
		@author Howard
	**/
	public function wpl_flush_rules()
	{
		$rules = get_option('rewrite_rules');
	
		if(!isset($rules['('.wpl_global::get_setting('main_permalink').')/(.+)$']))
		{
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}
	}
	
	/**
		@inputs void
		@description Adding a new rule
		@author Howard
	**/
	public function wpl_insert_rewrite_rules($rules)
	{
		$newrules = array();
		$newrules['('.wpl_global::get_setting('main_permalink').')/(.+)$'] = 'index.php?pagename=$matches[1]&wpl_qs=$matches[2]';

		return $newrules + $rules;
	}
	
	/**
		@inputs void
		@description Adding the wpl query string var so that WP recognizes it
		@author Howard
	**/
	public function wpl_insert_query_vars($vars)
	{
		array_push($vars, 'wpl_qs');
		return $vars;
	}
	
	/**
		@inputs void
		@description Adding wpl TinyMCE buttons
		@author Howard
	**/
	public function import_mce_buttons()
	{
        if(current_user_can('edit_posts') or current_user_can('edit_pages'))
        {
            add_filter('mce_external_plugins', array($this, 'register_shortcode_buttons'));
            add_filter('mce_buttons', array($this, 'add_shortcode_wizard'));
        }
    }
	
	/**
		@inputs $buttons
		@description Adding shortcode wizard
		@author Howard
	**/
	public function add_shortcode_wizard($buttons)
	{
		array_push($buttons, 'wplshortcode');
        return $buttons;
    }
	
	/**
		@inputs $plugin_array
		@description Registering shortcode buttons
		@author Howard
	**/
	public function register_shortcode_buttons($plugin_array)
	{
		$plugin_array['wplbuttons'] = wpl_global::get_wpl_asset_url('js/mce_editor/wpl.js');
        return $plugin_array;
    }
	
	/**
		@inputs void
		@returns void
		@description Registering active and deactive functions for WPl
		@author Howard
	**/
	public function wpl_active_deactive()
	{
		register_activation_hook(WPL_ABSPATH.'WPL.php', array($this, 'activate_wpl'));
		register_deactivation_hook(WPL_ABSPATH.'WPL.php', array($this, 'deactivate_wpl'));
		register_uninstall_hook(WPL_ABSPATH.'WPL.php', array('wpl_extensions', 'uninstall_wpl'));
    }
	
	/**
		@inputs void
		@returns void
		@description Running installation queries and initializing WPL
		@author Howard
	**/
	public function activate_wpl()
	{
		if(wpl_folder::exists(WPL_ABSPATH. 'assets' .DS. 'install' .DS. 'files'))
		{
			/** copy files **/
			$res = wpl_folder::copy(WPL_ABSPATH. 'assets' .DS. 'install' .DS. 'files', ABSPATH, '', true);
	
			/** delete files **/
			wpl_folder::delete(WPL_ABSPATH. 'assets' .DS. 'install' .DS. 'files');
		}
		
		/** run queries **/
		$query_file = WPL_ABSPATH. 'assets' .DS. 'install' .DS. 'queries.sql';
		if(wpl_file::exists($query_file))
		{
			$queries = wpl_file::read($query_file);
			$queries = str_replace(";\r\n", "-=++=-", $queries);
			$queries = str_replace(";\r", "-=++=-", $queries);
			$queries = str_replace(";\n", "-=++=-", $queries);
			$sqls = explode("-=++=-", $queries);
			
			if(function_exists('is_multisite') and is_multisite() and wpl_global::check_addon('multisite'))
			{
				$original_blog_id = wpl_global::get_current_blog_id();
				
				// Get all blogs
				$blogs = wpl_db::select("SELECT `blog_id` FROM `#__blogs`", 'loadColumn');
				
				foreach($blogs as $blog)
				{
					switch_to_blog($blog->blog_id);
					foreach($sqls as $sql)
					{
						try{wpl_db::q($sql);} catch (Exception $e){}
					}
				}
				
				/** delete query file **/
				wpl_file::delete($query_file);
				switch_to_blog($original_blog_id);
			}
			else
			{
				foreach($sqls as $sql)
				{
					try{wpl_db::q($sql);} catch (Exception $e){}
				}
				
				/** delete query file **/
				wpl_file::delete($query_file);
			}
		}
		
		/** run script **/
		$script_file = WPL_ABSPATH. 'assets' .DS. 'install' .DS. 'script.php';
		if(wpl_file::exists($script_file))
		{
			include $script_file;
			
			/** delete script file **/
			wpl_file::delete($query_file);
		}
		
		if(function_exists('is_multisite') and is_multisite() and wpl_global::check_addon('multisite'))
		{
			$original_blog_id = wpl_global::get_current_blog_id();
			
			// Get all blogs
			$blogs = wpl_db::select("SELECT `blog_id` FROM `#__blogs`", 'loadColumn');
			foreach($blogs as $blog)
			{
				switch_to_blog($blog->blog_id);
				
				/** create propertylisting page **/
				$pages = array('Properties'=>'[WPL]', 'For Sale'=>'[WPL sf_select_listing="9"]', 'For Rent'=>'[WPL sf_select_listing="10"]', 'Vacation Rental'=>'[WPL sf_select_listing="12"]');
				foreach($pages as $title=>$content)
				{
					if(wpl_db::select("SELECT COUNT(post_content) FROM `#__posts` WHERE `post_content` LIKE '%$content%' AND `post_status` IN ('publish', 'private')", 'loadResult') != 0) continue;
					
					$post = array('post_title'=>$title, 'post_content'=>$content, 'post_type'=>'page', 'post_status'=>'publish', 'comment_status'=>'closed', 'ping_status'=>'closed', 'post_author'=>1);
					wp_insert_post($post);
				}
				
				/** Add admin user to WPL **/
				wpl_users::add_user_to_wpl(1);
			}
			
			switch_to_blog($original_blog_id);
		}
		else
		{
			/** create propertylisting page **/
			$pages = array('Properties'=>'[WPL]', 'For Sale'=>'[WPL sf_select_listing="9"]', 'For Rent'=>'[WPL sf_select_listing="10"]', 'Vacation Rental'=>'[WPL sf_select_listing="12"]');
			foreach($pages as $title=>$content)
			{
				if(wpl_db::select("SELECT COUNT(post_content) FROM `#__posts` WHERE `post_content` LIKE '%$content%' AND `post_status` IN ('publish', 'private')", 'loadResult') != 0) continue;
				
				$post = array('post_title'=>$title, 'post_content'=>$content, 'post_type'=>'page', 'post_status'=>'publish', 'comment_status'=>'closed', 'ping_status'=>'closed', 'post_author'=>1);
				wp_insert_post($post);
			}
			
			/** Add admin user to WPL **/
			wpl_users::add_user_to_wpl(1);
		}
		
		/** upgrade WPL **/
		self::upgrade_wpl();
    }
	
	/**
		@inputs void
		@returns void
		@description Running necesarry queries and functions for upgrading
		@author Howard
	**/
	public function upgrade_wpl()
	{
		if(wpl_folder::exists(WPL_ABSPATH. 'assets' .DS. 'upgrade' .DS. 'files'))
		{
			/** copy files **/
			$res = wpl_folder::copy(WPL_ABSPATH. 'assets' .DS. 'upgrade' .DS. 'files', ABSPATH, '', true);
	
			/** delete files **/
			wpl_folder::delete(WPL_ABSPATH. 'assets' .DS. 'upgrade' .DS. 'files');
		}
		
		/** run queries **/
		$query_file = WPL_ABSPATH. 'assets' .DS. 'upgrade' .DS. 'queries.sql';
		if(wpl_file::exists($query_file))
		{
			$queries = wpl_file::read($query_file);
			$queries = str_replace(";\r\n", "-=++=-", $queries);
			$queries = str_replace(";\r", "-=++=-", $queries);
			$queries = str_replace(";\n", "-=++=-", $queries);
			$sqls = explode("-=++=-", $queries);
			
			if(function_exists('is_multisite') and is_multisite() and wpl_global::check_addon('multisite'))
			{
				$original_blog_id = wpl_global::get_current_blog_id();
				
				// Get all blogs
				$blogs = wpl_db::select("SELECT `blog_id` FROM `#__blogs`", 'loadColumn');
				
				foreach($blogs as $blog)
				{
					switch_to_blog($blog->blog_id);
					foreach($sqls as $sql)
					{
						try{wpl_db::q($sql);} catch (Exception $e){}
					}
				}
				
				/** delete query file **/
				wpl_file::delete($query_file);
				switch_to_blog($original_blog_id);
			}
			else
			{
				foreach($sqls as $sql)
				{
					try{wpl_db::q($sql);} catch (Exception $e){}
				}
				
				/** delete query file **/
				wpl_file::delete($query_file);
			}
		}
		
		/** run script **/
		$script_file = WPL_ABSPATH. 'assets' .DS. 'upgrade' .DS. 'script.php';
		if(wpl_file::exists($script_file))
		{
			include $script_file;
			
			/** delete script file **/
			wpl_file::delete($query_file);
		}
		
		/** update WPL version in db **/
		update_option('wpl_version', WPL_VERSION);
    }
	
	/**
		@inputs void
		@returns void
		@description Deactivating WPL
		@author Howard
	**/
	public function deactivate_wpl()
	{
	}
	
	/**
		@inputs void
		@returns void
		@description Uninstalling WPL
		@author Howard
	**/
	public function uninstall_wpl()
	{
        $tables = wpl_db::select('SHOW TABLES');
		$database = wpl_db::get_DBO();
		
		foreach($tables as $table_name=>$table)
		{
			if(strpos($table_name, $database->prefix.'wpl_') !== false)
			{
				/** drop table **/
				wpl_db::q("DROP TABLE `$table_name`");
			}
		}
        
        /** delete options **/
        wpl_db::q("DELETE FROM `#__options` WHERE `option_name` LIKE 'wpl_%' AND `option_name` NOT LIKE 'wpl_theme%'", 'delete');
        
        return true;
	}
	
	/**
		@inputs void
		@returns void
		@description Adding js dynamic vars to the head of page
		@author Howard
	**/
	public function import_dynamic_js()
	{
		echo '<script type="text/javascript">';
		echo 'wpl_baseUrl="'.wpl_global::get_wp_site_url().'";';
		echo 'wpl_baseName="'.WPL_BASENAME.'";';
		echo '</script>';
	}
	
	/**
		@inputs void
		@returns void
		@description Adding js dynamic vars to the head of page
		@author Howard
	**/
	public function plus_new_menu($wp_admin_bar)
	{
		$cur_user_id = wpl_users::get_cur_user_id();
		$cur_user_data = wpl_users::get_user($cur_user_id);
		
		if(wpl_users::is('administrator', $cur_user_id) or $cur_user_data->data->wpl_data->id)
		{
			$wp_admin_bar->add_menu(array(
			   'id'=>'wpl_add_listings',
			   'title'=>__('WPL Listing', WPL_TEXTDOMAIN),
			   'parent'=>'new-content',
			   'href'=>'admin.php?page=wpl_admin_add_listing'
			));
		}
	}
	
	/**
		@inputs void
		@description for creating admin pages
		@author Howard
	**/
	public function wpl_admin_pages()
	{
		$cur_user_id = wpl_users::get_cur_user_id();
		$cur_user_data = wpl_users::get_user($cur_user_id);
		
		$cur_role = wpl_users::get_role();
		$wpl_roles = wpl_users::get_wpl_roles();
		$menus = wpl_global::get_menus('menu', 'backend');
		$submenus = wpl_global::get_menus('submenu', 'backend');
		
		/** generate pages object **/
		$controller = new wpl_controller();
	
		if(wpl_users::is('administrator', $cur_user_id) or $cur_user_data->data->wpl_data->id)
		{
			/** add menus **/
			foreach($menus as $menu)
			{
				$role = $menu->capability == 'current' ? $cur_role : $wpl_roles[$menu->capability];
				$position = $menu->position ? $menu->position : NULL;
				
				add_menu_page(__($menu->page_title, WPL_TEXTDOMAIN), __($menu->menu_title, WPL_TEXTDOMAIN), $role, $menu->menu_slug, array($controller, $menu->function), '', $position);
			}
			
			/** add sub menus **/
			foreach($submenus as $submenu)
			{
				$role = $submenu->capability == 'current' ? $cur_role : $wpl_roles[$submenu->capability];
				$menu_title = $submenu->separator ? $controller->wpl_add_separator().__($submenu->menu_title, WPL_TEXTDOMAIN) : __($submenu->menu_title, WPL_TEXTDOMAIN);
				
				add_submenu_page($submenu->parent, __($submenu->page_title, WPL_TEXTDOMAIN), $menu_title, $role, $submenu->menu_slug, array($controller, $submenu->function));
			}
		}
	}
	
	/**
		@inputs void
		@description for creating admin bar menu
		@author Howard
	**/
	public function wpl_admin_bar_menu()
	{
		$cur_user_id = wpl_users::get_cur_user_id();
		$cur_user_data = wpl_users::get_user($cur_user_id);
		
		$cur_role = wpl_users::get_role();
		$wpl_roles = wpl_users::get_wpl_roles();
		$menus = wpl_global::get_menus('menu', 'backend');
		$submenus = wpl_global::get_menus('submenu', 'backend');
		
		global $wp_admin_bar;
		
		/** generate pages object **/
		$controller = new wpl_controller();
	
		if(wpl_users::is('administrator', $cur_user_id) or $cur_user_data->data->wpl_data->id)
		{
			/** add menus **/
			foreach($menus as $menu)
			{
				$menu_slug = (!wpl_users::is('administrator', $cur_user_id) and $submenu->capability != 'current') ? 'wpl_admin_profile' : $menu->menu_slug;
				
				$wp_admin_bar->add_menu(array(
					'id'=>$menu->menu_slug,
					'title'=>__($menu->menu_title, WPL_TEXTDOMAIN),
					'href'=>wpl_global::get_wp_admin_url().'admin.php?page='.$menu_slug,
				));
			}
			
			/** add sub menus **/
			foreach($submenus as $submenu)
			{
				if(!wpl_users::is('administrator', $cur_user_id) and $submenu->capability != 'current') continue;
				
				$menu_title = $submenu->separator ? $controller->wpl_add_separator().__($submenu->menu_title, WPL_TEXTDOMAIN) : __($submenu->menu_title, WPL_TEXTDOMAIN);
				
				$wp_admin_bar->add_menu(array(
					'id'=>$submenu->menu_slug,
					'parent'=>$submenu->parent,
					'title'=>$menu_title,
					'href'=>wpl_global::get_wp_admin_url().'admin.php?page='.$submenu->menu_slug,
				));
			}
		}
	}
	
	/**
		@inputs title
		@description for adding page number to listing pages
		@author Howard
	**/
	public function wp_title($title)
	{
		$wplview = wpl_request::getVar('wplview');
		$wplpage = wpl_request::getVar('wplpage');
		
		if(in_array($wplview, array('property_listing', 'profile_listing')) and $wplpage >= 2)
		{
            /** has HTML tag **/
            if(strpos($title, '</') != false) return $title;
            
			return wpl_global::clean($title.' -- '.__('Page', WPL_TEXTDOMAIN).' '.$wplpage);
		}
		
		return $title;
	}
	
	/**
		@inputs void
		@description for adding styles and scripts
		@author Howard
	**/
	public static function import_styles_scripts()
	{
		$wpl_extensions = new wpl_extensions();
		
		$javascripts = $wpl_extensions->get_extensions(1, 'javascript', wpl_global::get_client());
		foreach($javascripts as $javascript)
		{
			$wpl_extensions->import_javascript($javascript);
		}
		
		$styles = $wpl_extensions->get_extensions(1, 'style', wpl_global::get_client());
		foreach($styles as $style)
		{
			$wpl_extensions->import_style($style);
		}
	}
}

/** load extensions **/
$wpl_extensions = new wpl_extensions();

/** active deactive functions **/
$wpl_extensions->wpl_active_deactive();

if(!($GLOBALS['pagenow'] == 'plugins.php' and wpl_request::getVar('action') == 'activate') and !(wpl_request::getVar('tgmpa-activate') == 'activate-plugin'))
{
	$wpl_extensions->get_extensions(1, '', wpl_global::get_client());
	$wpl_extensions->import_extensions();
	
	if(version_compare(wpl_global::get_wp_option('wpl_version'), wpl_global::wpl_version(), '<'))
	{
		/** upgrading WPL **/
		$wpl_extensions->upgrade_wpl();
	}
}

/** Run WPL Proccess service **/
_wpl_import('libraries.services.process');
$wpl_service_process = new wpl_service_process();
$wpl_service_process->run();

/** import TinyMCE buttons **/
add_action('init', array($wpl_extensions, 'import_mce_buttons'));

/** listing menu in +new menu **/
add_action('admin_bar_menu', array($wpl_extensions, 'plus_new_menu'), 99);

/** import dynamic js **/
add_action('wp_head', array($wpl_extensions, 'import_dynamic_js'), 1);
add_action('admin_print_scripts', array($wpl_extensions, 'import_dynamic_js'), 1);

/** add javascripts and styles **/
if(wpl_global::get_client() == '0') add_action('wp_enqueue_scripts', array($wpl_extensions, 'import_styles_scripts'), 0);
elseif(wpl_global::get_client() == '1') add_action('admin_enqueue_scripts', array($wpl_extensions, 'import_styles_scripts'), 0);

add_action('login_enqueue_scripts', array($wpl_extensions, 'import_styles_scripts'), 0);

/** filter title **/
add_filter('wp_title', array($wpl_extensions, 'wp_title'), 999);

/** import languages **/
$wpl_extensions->import_language();

/** import permalink **/
$wpl_extensions->import_permalink();