<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.widgets');
_wpl_import('libraries.locations');

/**
 * WPL Search Widget
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update
 */
class wpl_search_widget extends wpl_widget
{
	public $wpl_tpl_path = 'widgets.search.tmpl';
	public $wpl_backend_form = 'widgets.search.form';
	public $listing_specific_array = array();
	public $property_type_specific_array = array();
	public $widget_id;
	public $widget_uq_name; # widget unique name
	
	public function __construct()
	{
		parent::__construct('wpl_search_widget', __('(WPL) Search', WPL_TEXTDOMAIN), array('description'=>__('Search properties/profiles.', WPL_TEXTDOMAIN)));
	}

	/**
	 * How to display the widget on the screen.
	 */
	public function widget($args, $instance)
	{
        $this->widget_id = $this->number;
        if($this->widget_id < 0) $this->widget_id = abs($this->widget_id)+1000;
        
        $this->widget_uq_name = 'wpls'.$this->widget_id;
		$widget_id = $this->widget_id;
		$target_id = isset($instance['wpltarget']) ? $instance['wpltarget'] : 0;
        
        $this->kind = isset($instance['kind']) ? $instance['kind'] : 0;
        $this->ajax = isset($instance['ajax']) ? $instance['ajax'] : 0;
        $this->css_class = isset($instance['css_class']) ? $instance['css_class'] : '';
        
		/** add main scripts **/
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('jquery-ui-button');
		wp_enqueue_script('jquery-ui-datepicker');
		
        /** add Layout js **/
        $js[] = (object) array('param1'=>'jquery.checkbox', 'param2'=>'packages/jquery.ui/checkbox/jquery.checkbox.js');
        foreach($js as $javascript) wpl_extensions::import_javascript($javascript);

		echo $args['before_widget'];

		$title = apply_filters('widget_title', (isset($instance['title']) ? $instance['title'] : ''));
		if(trim($title) != '') echo $args['before_title'] .$title. $args['after_title'];
		
		$layout = 'widgets.search.tmpl.'.(isset($instance['layout']) ? $instance['layout'] : 'default');
		$layout = _wpl_import($layout, true, true);
		$find_files = array();
		
		/** render search fields **/
		$this->rendered = $this->render_search_fields($instance, $widget_id, $find_files);
		
		if(!wpl_file::exists($layout)) $layout = _wpl_import('widgets.search.tmpl.default', true, true);
		if(wpl_file::exists($layout))
			require $layout;
		else
			echo __('Widget Layout Not Found!', WPL_TEXTDOMAIN);
		
		echo $args['after_widget'];
	}

	/**
	 * Update the widget settings.
	 */
	public function update($new_instance, $old_instance)
	{
		$instance = array();
		$instance['title'] = strip_tags($new_instance['title']);
        $instance['kind'] = isset($new_instance['kind']) ? $new_instance['kind'] : 0;
		$instance['layout'] = $new_instance['layout'];
        $instance['wpltarget'] = $new_instance['wpltarget'];
        $instance['ajax'] = isset($new_instance['ajax']) ? $new_instance['ajax'] : 0;
        $instance['css_class'] = isset($new_instance['css_class']) ? $new_instance['css_class'] : '';
		$instance['data'] = (array) $new_instance['data'];
		
		return $instance;
	}
	
	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	public function form($instance)
	{
        $this->widget_id = $this->number;
        
        $this->kind = isset($instance['kind']) ? $instance['kind'] : 0;
        $this->ajax = isset($instance['ajax']) ? $instance['ajax'] : 0;
        
		_wpl_import('libraries.flex');

		/** add main scripts **/
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-slider');
		wp_enqueue_script('jquery-ui-button');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-draggable');
		wp_enqueue_script('jquery-ui-droppable');
		wp_enqueue_script('jquery-effects-core');

		/* Set up some default widget settings. */
		if(!isset($instance['layout']))
		{
			$instance = array('title'=>__('Search', WPL_TEXTDOMAIN), 'layout'=>'default.php', 'data'=>self::make_array_defaults(wpl_flex::get_fields('', 1, $this->kind, 'searchmod', 1)));
			$defaults = array();
			$instance = wp_parse_args((array) $instance, $defaults);
		}
		
		$path = _wpl_import($this->wpl_backend_form, true, true);
		
		ob_start();
		include $path;
		echo $output = ob_get_clean();
	}
	
	public function generate_backend_categories($values)
	{
        $categories = wpl_flex::get_categories(1, $this->kind, " AND `searchmod`=1 AND `kind`='{$this->kind}' AND `enabled`>=1");
        
        // Tab Content
		foreach($categories as $category)
		{
			$path = 'widgets.search.scripts.fields_category';
			include _wpl_import($path, true, true);
		}
	}
    
    public function generate_backend_categories_tabs($values)
	{
		$categories = wpl_flex::get_categories(1, $this->kind, " AND `searchmod`=1 AND `kind`='{$this->kind}' AND `enabled`>=1");
        
        // Tabs
		foreach($categories as $category)
		{
			$path = 'widgets.search.scripts.fields_category_tabs';
			include _wpl_import($path, true, true);
		}
	}
	
	public function generate_backend_fields($fields, $values, &$finds = array())
	{
		# File Listing
		$path = WPL_ABSPATH .DS. 'libraries' .DS. 'widget_search' .DS. 'backend';
		$files = array();
		
		if(wpl_folder::exists($path)) $files = wpl_folder::files($path, '.php$');
        
		foreach($fields as $key=>$field)
		{
			if(!$field) return;
            
			$done_this = false;
			$type = $field->type;
			$options = json_decode($field->options, true);
			$value = isset($values[$field->id]) ? $values[$field->id] : NULL;
			
			if(isset($finds[$type]))
			{
				include($path .DS. $finds[$type]);
				continue;
			}
			
			foreach($files as $file)
			{
				include($path .DS. $file);
				
				/** break and go to next field **/
				if($done_this)
				{
					$finds[$type] = $file;
					break;
				}
			}
		}
	}
	
	public function render_search_fields($instance, $widget_id, $finds = array())
	{
		/** first validation **/
		if(!$instance) return array();
		
		$path = WPL_ABSPATH .DS. 'libraries' .DS. 'widget_search' .DS. 'frontend';
		$files = array();
		
		if(wpl_folder::exists($path)) $files = wpl_folder::files($path, '.php$');
		
		$fields = $instance['data'];
		uasort($fields, array('wpl_global', 'wpl_array_sort'));
		
		$rendered = array();
		foreach($fields as $key=>$field)
		{
			/** proceed to next field if field is not enabled **/
			if(!isset($field['enable']) or (isset($field['enable']) and $field['enable'] != 'enable')) continue;
			
            /** Fix empty id issue **/
            if((!isset($field['id']) or (isset($field['id']) and !$field['id'])) and $key) $field['id'] = $key;
            
			$field_data = (array) wpl_flex::get_field($field['id']);
            if(!$field_data) continue;
            
			$field['name'] = $field_data['name'];
			
			$type = $field_data['type'];
			$field_id = $field['id'];
			$options = json_decode($field_data['options'], true);
			
			$display = '';
			$done_this = false;
			$html = '';
            $current_value = '';
			
			/** listing and property type specific **/
			if(trim($field_data['listing_specific']) != '')
			{
				$specified_listings = explode(',', trim($field_data['listing_specific'], ', '));
				$this->listing_specific_array[$field_data['id']] = $specified_listings;
			}
			elseif(trim($field_data['property_type_specific']) != '')
			{
				$specified_property_types = explode(',', trim($field_data['property_type_specific'], ', '));
				$this->property_type_specific_array[$field_data['id']] = $specified_property_types;
			}
            
            /** Accesses **/
            if(trim($field_data['accesses']) != '')
            {
                $accesses = explode(',', trim($field_data['accesses'], ', '));
                $cur_membership_id = wpl_users::get_user_membership();

                if(!in_array($cur_membership_id, $accesses)) continue;
            }
			
			if(isset($finds[$type]))
			{
				$html .= '<div class="wpl_search_field_container '.(isset($field['type']) ? $field['type'].'_type' : '').' '.((isset($field['type']) and $field['type'] == 'predefined') ? 'wpl_hidden' : '').'" id="wpl'.$widget_id.'_search_field_container_'.$field['id'].'">';
				include($path .DS. $finds[$type]);
				$html .= '</div>';
				
				$rendered[$field_id]['id'] = $field_id;
				$rendered[$field_id]['field_data'] = $field_data;
				$rendered[$field_id]['field_options'] = json_decode($field_data['options'], true);
				$rendered[$field_id]['search_options'] = isset($field['extoption']) ? $field['extoption'] : NULL;
				$rendered[$field_id]['html'] = $html;
                $rendered[$field_id]['current_value'] = isset($current_value) ? $current_value : NULL;
				$rendered[$field_id]['display'] = $display;
				continue;
			}
			
			$html .= '<div class="wpl_search_field_container '.(isset($field['type']) ? $field['type'].'_type' : '').' '.((isset($field['type']) and $field['type'] == 'predefined') ? 'wpl_hidden' : '').'" id="wpl'.$widget_id.'_search_field_container_'.$field['id'].'" style="'.$display.'">';
			foreach($files as $file)
			{
				include($path .DS. $file);
				
				/** proceed to next field **/
				if($done_this)
				{
					$finds[$type] = $file;
					break;
				}
			}
			$html .= '</div>';
			
			$rendered[$field_id]['id'] = $field_id;
			$rendered[$field_id]['field_data'] = $field_data;
			$rendered[$field_id]['field_options'] = json_decode($field_data['options'], true);
			$rendered[$field_id]['search_options'] = isset($field['extoption']) ? $field['extoption'] : NULL;
			$rendered[$field_id]['html'] = $html;
            $rendered[$field_id]['current_value'] = isset($current_value) ? $current_value : NULL;
			$rendered[$field_id]['display'] = $display;
		}
        
		return $rendered;
	}
	
	# Makes an Array to set the default value of fields.
	public function make_array_defaults($fields)
	{
		$array_defaults = array();
		
		foreach($fields as $key=>$field)
		{
			if(!$field) return;
			
			$type = $field->type;
			$options = json_decode($field->options, true);
			$array_defaults[$field->id] = array('id'=>$field->id, 'enable'=>'disable', 'name'=>$field->name);
		}
		
		return $array_defaults;
	}
    
    public function get_target_page($target_id = NULL)
    {
        if(trim($target_id) and $target_id == '-1') $target_page = wpl_global::get_full_url();
        else $target_page = wpl_property::get_property_listing_link($target_id);
        
        return $target_page;
    }
	
	public function create_listing_specific_js()
	{
		echo '
		function wpl_listing_changed'.$this->widget_id.'(id)
		{';
		
		foreach($this->listing_specific_array as $id=>$listing_specific)
		{
			if($listing_specific == '') continue;
			
			if(is_array($listing_specific)) $listings = $listing_specific;
			else $listings = explode(',', $listing_specific);
			
			$cond = '';
			foreach($listings as $listing) $cond .= 'id=='.$listing.'||';
			$cond = rtrim($cond, '||');
			
			if(trim($cond) != 'id==')
			{
				echo '
				try
				{
					if('.$cond.')
						wplj("#wpl_searchwidget_'.$this->widget_id.' .wpl'.$this->widget_id.'_search_field_container_'.$id.', #wpl_searchwidget_'.$this->widget_id.' #wpl'.$this->widget_id.'_search_field_container_'.$id.'").css("display", "");
					else
						wplj("#wpl_searchwidget_'.$this->widget_id.' .wpl'.$this->widget_id.'_search_field_container_'.$id.', #wpl_searchwidget_'.$this->widget_id.' #wpl'.$this->widget_id.'_search_field_container_'.$id.'").css("display", "none");
				}catch(err){}
				';
			}
		}
			
		echo '	
		}';
	}

	public function create_property_type_specific_js()
	{
		echo '
		function wpl_property_type_changed'.$this->widget_id.'(id)
		{';
		
		foreach($this->property_type_specific_array as $id=>$property_type_specific)
		{
			if($property_type_specific == '') continue;
			
			if(is_array($property_type_specific)) $property_types = $property_type_specific;
			else $property_types = explode(',', $property_type_specific);
			
			$cond = '';
			foreach($property_types as $property_type) $cond .= 'id=='.$property_type.'||';
			$cond = rtrim($cond, '||');
			
			if (trim($cond) != 'id==')
			{
				echo '
				try
				{
					if('.$cond.')
						wplj("#wpl_searchwidget_'.$this->widget_id.' .wpl'.$this->widget_id.'_search_field_container_'.$id.', #wpl_searchwidget_'.$this->widget_id.' #wpl'.$this->widget_id.'_search_field_container_'.$id.'").css("display", "");
					else
						wplj("#wpl_searchwidget_'.$this->widget_id.' .wpl'.$this->widget_id.'_search_field_container_'.$id.', #wpl_searchwidget_'.$this->widget_id.' #wpl'.$this->widget_id.'_search_field_container_'.$id.'").css("display", "none");
				}catch(err){}
				';
			}
		}
		
		echo '	
		}';
	}
}