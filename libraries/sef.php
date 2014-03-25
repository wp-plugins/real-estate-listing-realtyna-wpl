<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** SEF Library
** Developed 08/14/2013
**/

class wpl_sef
{
	/**
		Developed by : Howard
		Inputs : array $instance passed by wordpress
		Outputs : void
		Date : 2013-08-14
		Description : This is a system function for processing SEF
	**/
	public function process($instance)
	{
		/** get global settings **/
		$settings = wpl_global::get_settings();
		
		$wpl_qs = wpl_global::get_wp_qvar('wpl_qs');
		$ex = explode($settings['sef_main_separator'], $wpl_qs);
		
		/** get view **/
		$view = self::get_view($wpl_qs, $settings['sef_main_separator']);
		
		/** load view **/
		return wpl_global::load($view, '', $instance);
	}
	
	/**
		Developed by : Howard
		Inputs : string $query_string, string $separator
		Outputs : string $view
		Date : 2013-08-14
		Description : This is a function for getting the view based on query string
	**/
	public function get_view($query_string = '', $separator = '')
	{
		/** first validations **/
		if(trim($query_string) == '') $query_string = wpl_global::get_wp_qvar('wpl_qs');
		if(trim($separator) == '') $separator = wpl_global::get_setting('sef_main_separator');
		
		$ex = explode($separator, $query_string);
		
		if(trim($ex[0]) == '') $view = 'property_listing';
		else
		{
			$exp = explode('-', $ex[0]);
			
			if(is_numeric($exp[0])) $view = 'property_show';
			elseif($ex[0] == 'agents') $view = 'profile_listing';
			elseif($ex[0] == 'features') $view = 'features';
			elseif(strpos($ex[0], ':') === false) $view = 'profile_show';
			else $view = 'property_listing';
		}
		
		return $view;
	}
	
	/**
		Developed by : Howard
		Inputs : string $view, string $query_string
		Outputs : void
		Date : 2013-08-14
		Description : This is a function for setting the parameters
	**/
	public function setVars($view = 'property_listing', $query_string = '')
	{
		/** first validations **/
		if(trim($query_string) == '') $query_string = wpl_global::get_wp_qvar('wpl_qs');
		
		$separator = wpl_global::get_setting('sef_main_separator'); /** default value is "/" character **/
		$ex = explode($separator, $query_string);
		
		/** set view **/
		wpl_request::setVar('wplview', $view);
		
		if($view == 'property_show')
		{
			$exp = explode('-', $ex[0]);
			wpl_request::setVar('pid', $exp[0]);
		}
		elseif($view == 'profile_show')
		{
			$query = "SELECT `ID` FROM `#__users` WHERE `user_login`='".$ex[0]."' ORDER BY ID ASC LIMIT 1";
			$uid = wpl_db::select($query, 'loadResult');
			
			wpl_request::setVar('uid', $uid);
			wpl_request::setVar('sf_select_user_id', $uid);
		}
		else
		{
			/** specific fields like country, state, city and ... **/
			$specific_fields = array();
			
			/** set location vars **/
			self::set_location_vars($ex);
			
			foreach($ex as $parameter)
			{
				$types = array();
				$detected = explode(':', $parameter);
				
				if(count($detected) == 1) continue;
				elseif(count($detected) == 2)
				{
					$types[0]  = 'select';
					$fields[0] = $detected[0];
					$values[0] = $detected[1];
					
					$parsed_value = explode('-', $detected[1]);
					
					if(count($parsed_value) == 2)
					{
						$types[0]  = 'tmin';
						$fields[0] = $detected[0];
						$values[0] = $parsed_value[0];
						
						$types[1]  = 'tmax';
						$fields[1] = $detected[0];
						$values[1] = $parsed_value[1];
					}
					elseif(count($parsed_value) == 3)
					{
						$types[0]  = 'min';
						$fields[0] = $detected[0];
						$values[0] = $parsed_value[0];
						
						$types[1]  = 'max';
						$fields[1] = $detected[0];
						$values[1] = $parsed_value[1];
						
						$types[2]  = 'unit';
						$fields[2] = $detected[0];
						$values[2] = $parsed_value[2];
					}
				}
				elseif(count($detected) == 3)
				{
					$types[0]  = strtolower($detected[0]);
					$fields[0] = $detected[1];
					$values[0] = $detected[2];
				}
				
				$i = 0;
				foreach($types as $type)
				{
					$field = self::parse_field(urldecode($fields[$i]), $specific_fields);
					$value = self::get_id_by_name($field, urldecode($values[$i]));
					
					if(trim($value) != '') wpl_request::setVar('sf_'.$type.'_'.$field, $value);
					$i++;
				}
			}
		}
	}
	
	/**
		Developed by : Howard
		Inputs : string $view, string $query_string
		Outputs : void
		Date : 2013-08-14
		Description : This is a function for loading view
	**/
	public function get_id_by_name($field = '', $value = '')
	{
		/** return if value is numeric for some special fields **/
		if(is_numeric($value)) return $value;
		
		if($field == 'listing')
		{
			$query = "SELECT `id` FROM `#__wpl_listing_types` WHERE LOWER(name)='".strtolower($value)."'";
			$result = wpl_db::select($query, 'loadResult');
			
			return $result ? $result : '';
		}
		elseif($field == 'property_type')
		{
			$query = "SELECT `id` FROM `#__wpl_property_types` WHERE LOWER(name)='".strtolower($value)."'";
			$result = wpl_db::select($query, 'loadResult');
			
			return $result ? $result : '';
		}
		else
		{
			return $value;
		}
	}
	
	/**
		Developed by : Howard
		Inputs : string $field
		Outputs : string $field
		Date : 2013-08-14
		Description : It will change dummy fields to WPL fields for example "Property Type" to "property_type". Also it takes care of specific fields
	**/
	public function parse_field($field, $specific_fields = array())
	{
		if(trim($field) == '') return '';
		
		if(in_array($field, $specific_fields)) return $specific_fields[$field];
		else return strtolower(str_replace(' ', '_', $field));
	}
	
	/**
		Developed by : Howard
		Date : 2013-08-14
		Description : It will change dummy fields to WPL fields.
	**/
	public function set_location_vars($parameters)
	{
		/** specific fields like country, state, city and ... **/
		$location_fields = array();
		$rendered_parameters = array();
		
		/** add location fields to specific fields **/
		$location_settings = wpl_global::get_settings('3'); # location settings
		foreach($location_settings as $location_key=>$location_value)
		{
			if(!strpos($location_key, '_keyword') or trim($location_value) == '') continue;
			
			$location_id = str_replace('location', '', str_replace('_keyword', '', $location_key));
			
			if($location_id != 'zips') $location_fields['location'.$location_id.'_id'] = self::parse_field($location_value);
			else $location_fields['zip_id'] = self::parse_field($location_value);
		}
		
		foreach($parameters as $parameter)
		{
			$ex = explode(':', $parameter);
			
			if(count($ex) == 2) $rendered_parameters[self::parse_field(urldecode($ex[0]))] = $ex[1];
			elseif(count($ex) == 3) $rendered_parameters[self::parse_field(urldecode($ex[1]))] = $ex[2];
		}
		
		foreach($location_fields as $column=>$location_field)
		{
			if(!isset($rendered_parameters[$location_field])) continue;
			
			$location_id = str_replace('location', '', str_replace('_id', '', $column));
			if($location_id == 'zip') $location_id = 'zips';
			
			$query = "SELECT `id` FROM `#__wpl_location".$location_id."` WHERE `name`='".urldecode($rendered_parameters[$location_field])."' ".($parent ? "AND `parent`='$parent'" : '');
			$parent = wpl_db::select($query, 'loadResult');
			
			wpl_request::setVar('sf_select_'.$column, $parent);
		}
	}
	
	/**
		Developed by : Howard
		Inputs : void
		Outputs : void
		Date : 2013-11-06
		Description : This is for settings wpl view before initializing theme for using in theme
	**/
	public function set_view()
	{
		/** checking wordpress post type (post, page, any kind of posts and ...) **/
		if(is_page() or is_single())
		{
			/** getting the post id and post content **/
			$post_id = get_the_ID();
			$post_content = wpl_db::get('post_content', 'posts', 'id', $post_id);
			$wplview = '';
			
			if(strpos($post_content, '[wpl_profile_listing') !== false) $wplview = 'profile_listing';
			elseif(strpos($post_content, '[wpl_property_listings') !== false or strpos($post_content, '[WPL') !== false) $wplview = 'property_listing';
			elseif(strpos($post_content, '[wpl_property_show') !== false) $wplview = 'property_show';
			elseif(strpos($post_content, '[wpl_my_profile') !== false) $wplview = 'profile_wizard';
			elseif(strpos($post_content, '[wpl_add_edit_listing') !== false) $wplview = 'property_wizard';
			elseif(strpos($post_content, '[wpl_listing_manager') !== false) $wplview = 'property_manager';
			
			/** set view **/
			if(trim($wplview) != '') wpl_request::setVar('wplview', $wplview);
		}
	}
}