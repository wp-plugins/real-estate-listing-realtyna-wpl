<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.widgets');
_wpl_import('libraries.flex');
_wpl_import('libraries.property');
_wpl_import('libraries.images');
_wpl_import('libraries.sort_options');

/**
 * WPL Carousel Widget
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update
 */
class wpl_carousel_widget extends wpl_widget
{
	public $wpl_tpl_path = 'widgets.carousel.tmpl';
	public $wpl_backend_form = 'widgets.carousel.form';
	public $listing_specific_array = array();
	public $property_type_specific_array = array();
	public $widget_id;
	public $widget_uq_name; # widget unique name
	
	public function __construct()
	{
		parent::__construct('wpl_carousel_widget', __('(WPL) Carousel', WPL_TEXTDOMAIN), array('description'=>__('Showing specific properties.', WPL_TEXTDOMAIN)));
	}

	/**
	 * How to display the widget on the screen.
	 */
	public function widget($args, $instance)
	{
		$this->instance = $instance;
        
        $this->widget_id = $this->number;
        if($this->widget_id < 0) $this->widget_id = abs($this->widget_id)+1000;
        
		$this->widget_uq_name = 'wplc'.$this->widget_id;
		$widget_id = $this->widget_id;
        
        $this->css_class = isset($instance['data']['css_class']) ? $instance['data']['css_class'] : '';
        
		/** add main scripts **/
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-slider');
			
		/** render properties **/
		$query = self::query($instance);
        $model = new wpl_property();
		$properties = $model->search($query);
		
        /** return if no property found **/
        if(!count($properties)) return;
        
		$plisting_fields = $model->get_plisting_fields();
		$wpl_properties = array();
        $render_params['wpltarget'] = isset($instance['wpltarget']) ? $instance['wpltarget'] : 0;
        
		foreach($properties as $property)
		{
			$wpl_properties[$property->id] = $model->full_render($property->id, $plisting_fields, $property, $render_params);
		}
		
		echo $args['before_widget'];

		$title = apply_filters('widget_title', $instance['title']);
		if(trim($title) != '') echo $args['before_title'] .$title. $args['after_title'];
		
		$layout = 'widgets.carousel.tmpl.'.$instance['layout'];
		$layout = _wpl_import($layout, true, true);
		
		if(!wpl_file::exists($layout)) $layout = _wpl_import('widgets.carousel.tmpl.default', true, true);
		elseif(wpl_file::exists($layout)) require $layout;
		else echo __('Widget Layout Not Found!', WPL_TEXTDOMAIN);
		
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
        $instance['wpltarget'] = $new_instance['wpltarget'];
		$instance['data'] = (array) $new_instance['data'];
		
        /** random option **/
        if(isset($instance['data']['random']) and $instance['data']['random']) $instance['data']['listing_ids'] = '';
        
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
        
		/* Set up some default widget settings. */
		if(!isset($instance['layout']))
		{
			$instance = array('title'=>__('Featured Properties', WPL_TEXTDOMAIN), 'layout'=>'default.php', 'data'=>array('kind'=>'0', 'limit'=>'8', 'orderby'=>'p.add_date', 'order'=>'DESC', 'image_width'=>'1920', 'image_height'=>'558', 'thumbnail_width'=>'150', 'thumbnail_height'=>'60'));
			$instance = wp_parse_args((array) $instance, NULL);
		}
        
		$path = _wpl_import($this->wpl_backend_form, true, true);
		
		ob_start();
		include $path;
		echo $output = ob_get_clean();
	}
    
    private function query($instance)
	{
        /** property listing model **/
		$model = new wpl_property;
		$data = $instance['data'];
        
		$this->start = 0;
		$this->limit = $data['limit'];
		$this->orderby = urldecode($data['orderby']);
        $this->order = $data['order'];
        
		/** detect kind **/
        if(isset($data['kind']) and (trim($data['kind']) != '' or trim($data['kind']) != '-1')) $kind = $data['kind'];
        else $kind = 0;
		
        $where = array('sf_select_confirmed'=>1, 'sf_select_finalized'=>1, 'sf_select_deleted'=>0, 'sf_select_expired'=>0, 'sf_select_kind'=>$kind);
        
        if(trim($data['listing']) and $data['listing'] != '-1') $where['sf_select_listing'] = $data['listing'];
		if(trim($data['property_type']) and $data['property_type'] != '-1') $where['sf_select_property_type'] = $data['property_type'];
		if(isset($data['listing_ids']) and trim($data['listing_ids'])) $where['sf_multiple_mls_id'] = trim($data['listing_ids'], ', ');
        
        /** Tags **/
        $tags = wpl_flex::get_fields(NULL, NULL, NULL, NULL, NULL, "AND `type`='tag' AND `enabled`>='1' GROUP BY `table_column`");
        foreach($tags as $tag)
        {
            $tagkey = 'only_'.ltrim($tag->table_column, 'sp_');
            if(isset($data[$tagkey]) and trim($data[$tagkey])) $where['sf_select_'.$tag->table_column] = 1;
        }
        
        /** Parent **/
        if(isset($data['parent']) and trim($data['parent'])) $where['sf_parent'] = $data['parent'];
        if(isset($data['auto_parent']) and trim($data['auto_parent']))
        {
            /** current proeprty id - This features works only in single property page **/
            $property_data = NULL;
            $pid = wpl_request::getVar('pid', 0);
            if($pid) $property_data = $model->get_property_raw_data($pid);
            
            if(isset($property_data['mls_id'])) $where['sf_parent'] = $property_data['mls_id'];
        }
		
        if(isset($data['random']) and trim($data['random']) and trim($data['listing_ids']) == '')
		{
			$query_rand = "SELECT p.`id` FROM `#__wpl_properties` AS p WHERE 1 ".wpl_db::create_query($where)." ORDER BY RAND() LIMIT ".$this->limit;
			$results = wpl_db::select($query_rand);
			
			$rand_ids = array();
			foreach($results as $result) $rand_ids[] = $result->id;
			
            $where['sf_multiple_id'] = implode(',', $rand_ids);
		}
        
        /** Similar properties **/
        if(isset($data['sml_only_similars']) and $data['sml_only_similars']) # sml = similar
        {
            $sml_where = array('sf_select_confirmed'=>1, 'sf_select_finalized'=>1, 'sf_select_deleted'=>0, 'sf_select_expired'=>0);
            
            /** current proeprty id - This features works only in single property page **/
            $pid = wpl_request::getVar('pid', 0);
            $property_data = wpl_property::get_property_raw_data($pid);
            
            if($property_data)
            {
                $sml_where['sf_notselect_id'] = $pid;
                $sml_where['sf_select_kind'] = $property_data['kind'];
            
                if(isset($data['sml_inc_listing']) and $data['sml_inc_listing']) $sml_where['sf_select_listing'] = $property_data['listing'];
                if(isset($data['sml_inc_property_type']) and $data['sml_inc_property_type']) $sml_where['sf_select_property_type'] = $property_data['property_type'];

                if(isset($data['sml_inc_price']) and $data['sml_inc_price'])
                {
                    $down_rate = $data['sml_price_down_rate'] ? $data['sml_price_down_rate'] : 0.8;
                    $up_rate = $data['sml_price_up_rate'] ? $data['sml_price_up_rate'] : 1.2;

                    $price_down_range = $property_data['price_si']*$down_rate;
                    $price_up_range = $property_data['price_si']*$up_rate;
                    
                    $sml_where['sf_tmin_price_si'] = $price_down_range;
                    $sml_where['sf_tmax_price_si'] = $price_up_range;
                }

                if(isset($data['sml_inc_radius']) and $data['sml_inc_radius'])
                {
                    $latitude = $property_data['googlemap_lt'];
                    $longitude = $property_data['googlemap_ln'];
                    $radius = $data['sml_radius'];
                    $unit_id = $data['sml_radius_unit'];
                    
                    if($latitude and $longitude and $radius and $unit_id)
                    {
                        $sml_where['sf_radiussearchunit'] = $unit_id;
                        $sml_where['sf_radiussearch_lat'] = $latitude;
                        $sml_where['sf_radiussearch_lng'] = $longitude;
                        $sml_where['sf_radiussearchradius'] = $radius;
                    }
                }
            }
            
            /** overwrite $where if similar where is correct **/
            if(count($sml_where) > 3) $where = $sml_where;
        }
        
		/** Start Search **/
		$model->start($this->start, $this->limit, $this->orderby, $this->order, $where);
		
		/** Return the search **/
		return $model->query();
	}
}