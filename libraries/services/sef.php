<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** SEF service
** Developed 08/19/2013
**/

class wpl_service_sef
{
	public function run()
	{
		/** setting view from post content by shortcode **/
		wpl_sef::set_view();
		
		/** get global settings **/
		$settings = wpl_global::get_settings();
		$wpl_qs = urldecode(wpl_global::get_wp_qvar('wpl_qs'));
		
		/** get view **/
		$view = wpl_sef::get_view($wpl_qs, $settings['sef_main_separator']);
		
		/** set vars **/
		wpl_sef::setVars($view, $wpl_qs);
        
		if($view == 'property_show')
		{
            if(trim($wpl_qs) != '')
            {
                $ex = explode('-', $wpl_qs);
                $exp = explode('-', $ex[0]);
                $proeprty_id = $exp[0];
            }
			else
            {
                $proeprty_id = wpl_request::getVar('pid', NULL);
                if(!$proeprty_id) $proeprty_id = wpl_request::getVar('property_id', NULL);
            }
		
			if(trim($wpl_qs) != '') self::check_property_link($proeprty_id);
			self::set_property_page_params($proeprty_id);
		}
		elseif($view == 'profile_show')
		{
			$username = $wpl_qs;
			self::set_profile_page_params($username);
		}
		elseif($view == 'property_listing'){}
		elseif($view == 'profile_listing'){}
		elseif($view == 'features')
		{
			$function = str_replace('features/', '', $wpl_qs);
			
			_wpl_import('views.basics.features.wpl_'.$function);
			$obj = new wpl_features_controller();
			$obj->display();
		}
	}
	
	public function check_property_link($proeprty_id)
	{
		$wpl_qs = urldecode(wpl_global::get_wp_qvar('wpl_qs'));
		
		/** check property alias for avoiding duplicate content **/
		$called_alias = $wpl_qs;
		$property_alias = urldecode(wpl_db::get('alias', 'wpl_properties', 'id', $proeprty_id));
		
		if(trim($property_alias) != '' and $called_alias != $property_alias)
		{
			$url = wpl_global::get_wp_site_url();
			$url .= wpl_global::get_setting('main_permalink')."/".urlencode($property_alias);
			
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: ".$url);
			exit;
		}
	}
	
	public function set_property_page_params($proeprty_id)
	{
		_wpl_import('libraries.property');
		
		$property_data = wpl_property::get_property_raw_data($proeprty_id);
		
		if(trim($property_data['property_title']) == '') $property_title = wpl_property::generate_property_title($property_data);
		else $property_title = $property_data['property_title'];
		
		$html = wpl_html::getInstance();
		
		/** set title **/
		$html->set_title($property_title);
		
		/** set meta keywords **/
		$html->set_meta_keywords($property_data['meta_keywords']);
		
		/** set meta description **/
		$html->set_meta_description($property_data['meta_description']);
	}
	
	public function set_profile_page_params($username)
	{
		$user_id = wpl_users::get_id_by_username($username);
		$user_data = (array) wpl_users::get_wpl_user($user_id);
		
		$user_title = '';
		$user_keywords = '';
		$user_description = __('Listings of', WPL_TEXTDOMAIN);
		
		if(trim($user_data['first_name']) != '')
		{
			$user_title .= $user_data['first_name'];
			$user_keywords .= $user_data['first_name'].',';
			$user_description .= ' '.$user_data['first_name'];
		}
		
		if(trim($user_data['last_name']) != '')
		{
			$user_title .= ' '.$user_data['last_name'];
			$user_keywords .= $user_data['last_name'].',';
			$user_description .= ' '.$user_data['last_name'];
		}
		
		if(trim($user_data['company_name']) != '')
		{
			$user_title .= ' - '.$user_data['company_name'];
			$user_keywords .= $user_data['company_name'].',';
			$user_description .= ' - '.$user_data['company_name'];
		}
		
		$user_title .= ' '.__('Listings', WPL_TEXTDOMAIN);
		$user_keywords = trim($user_keywords, ', ');
		$user_description .= ' '.__('which is located in', WPL_TEXTDOMAIN).' '.$user_data['location_text'];
		
		$html = wpl_html::getInstance();
		
		/** set title **/
		$html->set_title($user_title);
		
		/** set meta keywords **/
		$html->set_meta_keywords($user_keywords);
		
		/** set meta description **/
		$html->set_meta_description($user_description);
	}
}