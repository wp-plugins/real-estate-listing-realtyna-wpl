<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.widgets');
_wpl_import('libraries.flex');
_wpl_import('libraries.property');
_wpl_import('libraries.images');

/**
 * WPL Carousel Widget
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update
 */
class wpl_carousel_widget extends wpl_widget
{
	var $wpl_tpl_path = 'widgets.carousel.tmpl';
	var $wpl_backend_form = 'widgets.carousel.form';
	var $listing_specific_array = array();
	var $property_type_specific_array = array();
	var $widget_id;
	var $widget_uq_name; # widget unique name
	
	public function __construct()
	{
		$this->widget_id = $this->number;
		$this->widget_uq_name = 'wplc'.$this->number;
		
		parent::__construct('wpl_carousel_widget', 'WPL carousel widget', array('description'=>__('Showing specific properties', WPL_TEXTDOMAIN)));
	}

	/**
	 * How to display the widget on the screen.
	 */
	public function widget($args, $instance)
	{
		$this->instance = $instance;
		$widget_id = $this->widget_id;
		
		/** add main scripts **/
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-slider');
		
		/** add main styles **/
		$css[] = (object) array('param1'=>'jquery-ui-style', 'param2'=>'js/jquery.ui/jquery.ui.lightness.css');
		foreach($css as $style) wpl_extensions::import_style($style);
		
		/** render properties **/
		$query = self::query($instance);
		$properties = wpl_property::search($query);
		
		$wpl_properties = array();
		foreach($properties as $property)
		{
			$wpl_properties[$property->id] = wpl_property::full_render($property->id, $plisting_fields, $property);
		}
		
		echo $args['before_widget'];

		$title = apply_filters('widget_title', $instance['title']);
		if(trim($title) != '') echo $args['before_title'] .$title. $args['after_title'];
		
		$layout = 'widgets.carousel.tmpl.'.$instance['layout'];
		$layout = _wpl_import($layout, true, true);
		
		if(!wpl_file::exists($layout)) $layout = _wpl_import('widgets.carousel.tmpl.default', true, true);
		elseif(wpl_file::exists($layout)) 
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
		$instance['layout'] = $new_instance['layout'];
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
		/* Set up some default widget settings. */
		if(!isset($instance['layout']))
		{
			$instance = array('title'=>__('Featured Properties', WPL_TEXTDOMAIN), 'layout'=>'default.php', 'data'=>array('limit'=>'8', 'orderby'=>'p.add_date', 'order'=>'DESC'));
			$instance = wp_parse_args((array) $instance, $defaults);
		}
		
		$path = _wpl_import($this->wpl_backend_form, true, true);
		
		ob_start();
		include $path;
		echo $output = ob_get_clean();
	}
	
	private function query($instance)
	{
		$data = $instance['data'];
		
		$this->listing_fields = wpl_property::get_plisting_fields();
		$this->select = wpl_property::generate_select($this->listing_fields, 'p');
		$this->limit = $data['limit'];
		$this->order = $data['orderby']." ".$data['order'];
		
		$this->where = " AND p.`deleted`='0' AND p.`finalized`='1' AND p.`confirmed`='1'";
		
		if(trim($data['listing']) and $data['listing'] != '-1') $this->where .= " AND p.`listing`='".$data['listing']."'";
		if(trim($data['property_type']) and $data['property_type'] != '-1') $this->where .= " AND p.`property_type`='".$data['property_type']."'";
		if(trim($data['property_ids'])) $this->where .= " AND p.`id` IN (".trim($data['property_ids'], ', ').")";
		if(trim($data['only_featured'])) $this->where .= " AND p.`sp_featured`='1'";
		if(trim($data['only_hot'])) $this->where .= " AND p.`sp_hot`='1'";
		if(trim($data['only_openhouse'])) $this->where .= " AND p.`sp_openhouse`='1'";
		if(trim($data['only_forclosure'])) $this->where .= " AND p.`sp_forclosure`='1'";
		
		if(trim($data['random']) and trim($data['property_ids']) == '')
		{
			$query_rand = "SELECT p.`id` FROM `#__wpl_properties` AS p WHERE 1 ".$this->where." ORDER BY RAND() LIMIT ".$this->limit;
			$results = wpl_db::select($query_rand);
			
			$rand_ids = array();
			foreach($results as $result) $rand_ids[] = $result->id;
			
			$this->where .= " AND p.`id` IN (".implode(',', $rand_ids).")";
		}
		
		return $query = "SELECT ".$this->select." FROM `#__wpl_properties` AS p WHERE 1 ".$this->where." ORDER BY ".$this->order." LIMIT ".$this->limit;
	}
}