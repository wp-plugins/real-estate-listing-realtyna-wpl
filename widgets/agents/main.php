<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.widgets');
_wpl_import('libraries.flex');
_wpl_import('libraries.users');
_wpl_import('libraries.images');
_wpl_import('libraries.sort_options');

/**
 * WPL Agents Widget
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update
 */
class wpl_agents_widget extends wpl_widget
{
	var $wpl_tpl_path = 'widgets.agents.tmpl';
	var $wpl_backend_form = 'widgets.agents.form';
	var $widget_id;
	var $widget_uq_name; # widget unique name
	
	public function __construct()
	{
		$this->widget_id = $this->number;
		$this->widget_uq_name = 'wplc'.$this->number;
		
		parent::__construct('wpl_agents_widget', 'WPL agents widget', array('description'=>__('Showing specific agents', WPL_TEXTDOMAIN)));
	}

	/**
	 * How to display the widget on the screen.
	 */
	public function widget($args, $instance)
	{
		$this->instance = $instance;
		$widget_id = $this->widget_id;
        
		/** render properties **/
		$query = self::query($instance);
        $model = new wpl_users();
		$profiles = $model->search($query);
		
		$plisting_fields = $model->get_plisting_fields();
		$wpl_profiles = array();
        $render_params['wpltarget'] = isset($instance['wpltarget']) ? $instance['wpltarget'] : 0;
        $params = array();
        
		foreach($profiles as $profile)
		{
			$wpl_profiles[$profile->id] = $model->full_render($profile->id, $plisting_fields, $profile, $render_params);
            
            $params['image_parentid'] = $profile->id;
            
            /** profile picture **/
            if(isset($wpl_profiles[$profile->id]['profile_picture']['url']))
            {
                $params['image_name'] = isset($wpl_profiles[$profile->id]['profile_picture']['name']) ? $wpl_profiles[$profile->id]['profile_picture']['name'] : '';
                $profile_picture_path = isset($wpl_profiles[$profile->id]['profile_picture']['path']) ? $wpl_profiles[$profile->id]['profile_picture']['path'] : '';
                $wpl_profiles[$profile->id]['profile_picture']['url'] = wpl_images::create_profile_images($profile_picture_path, $instance['data']['image_width'], $instance['data']['image_height'], $params);

                $wpl_profiles[$profile->id]['profile_picture']['image_width'] = isset($instance['data']['image_width']) ? $instance['data']['image_width'] : '';
                
                $wpl_profiles[$profile->id]['profile_picture']['image_height'] = isset($instance['data']['image_height']) ? $instance['data']['image_height'] : '';
            }
            
            /** company logo **/
            if(isset($wpl_profiles[$profile->id]['company_logo']['url']))
            {
                $params['image_name'] = isset($wpl_profiles[$profile->id]['company_logo']['name']) ? $wpl_profiles[$profile->id]['company_logo']['name'] : '';
                $company_logo_path = isset($wpl_profiles[$profile->id]['company_logo']['path']) ? $wpl_profiles[$profile->id]['company_logo']['path'] : '';
                $wpl_profiles[$profile->id]['company_logo']['url'] = wpl_images::create_profile_images($company_logo_path, $instance['data']['image_width'], $instance['data']['image_height'], $params);
            }
		}
		
		echo $args['before_widget'];
        
		$title = apply_filters('widget_title', $instance['title']);
		if(trim($title) != '') echo $args['before_title'] .$title. $args['after_title'];
		
		$layout = 'widgets.agents.tmpl.'.$instance['layout'];
		$layout = _wpl_import($layout, true, true);
		
		if(!wpl_file::exists($layout)) $layout = _wpl_import('widgets.agents.tmpl.default', true, true);
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
        if(isset($instance['data']['random']) and $instance['data']['random']) $instance['data']['user_ids'] = '';
        
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
			$instance = array('title'=>__('Featured Agents', WPL_TEXTDOMAIN), 'layout'=>'default.php', 'data'=>array('limit'=>'4', 'orderby'=>'p.first_name', 'order'=>'DESC', 'image_width'=>'230', 'image_height'=>'230'));
			$instance = wp_parse_args((array) $instance, NULL);
		}
		
		$path = _wpl_import($this->wpl_backend_form, true, true);
		
		ob_start();
		include $path;
		echo $output = ob_get_clean();
	}
	
	private function query($instance)
	{
        $model = new wpl_users();
		$data = $instance['data'];
		
		$this->listing_fields = $model->get_plisting_fields();
		$this->select = '*';
        $this->limit = $data['limit'];
		$this->order = $data['orderby']." ".$data['order'];
		
		$this->where = "";
		
		if(trim($data['user_type']) and $data['user_type'] != '-1') $this->where .= " AND p.`membership_type`='".$data['user_type']."'";
		if(trim($data['membership']) and $data['membership'] != '') $this->where .= " AND p.`membership_id`='".$data['membership']."'";
		if(trim($data['user_ids'])) $this->where .= " AND p.`id` IN (".trim($data['user_ids'], ', ').")";
		
        if(isset($data['random']) and trim($data['random']) and trim($data['user_ids']) == '')
		{
			$query_rand = "SELECT p.`id` FROM `#__users` AS u INNER JOIN `#__wpl_users` AS p ON u.ID = p.id WHERE 1 ".$this->where." ORDER BY RAND() LIMIT ".$this->limit;
			$results = wpl_db::select($query_rand);
			
			$rand_ids = array();
			foreach($results as $result) $rand_ids[] = $result->id;
			
			$this->where .= " AND p.`id` IN (".implode(',', $rand_ids).")";
		}
        
		return $query = "SELECT ".$this->select." FROM `#__users` AS u INNER JOIN `#__wpl_users` AS p ON u.ID = p.id WHERE 1 ".$this->where." ORDER BY ".$this->order." LIMIT ".$this->limit;
	}
}