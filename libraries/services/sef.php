<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * SEF service
 * @author Howard <howard@realtyna.com>
 * @date 08/19/2013
 */
class wpl_service_sef
{
    /**
     * Service runner
     * @author Howard <howard@realtyna.com>
     * @return void
     */
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
            if(trim($username) != '') $user_id = wpl_users::get_id_by_username($username);
            elseif(wpl_request::getVar('sf_select_user_id', 0)) $user_id = wpl_request::getVar('sf_select_user_id', 0);
            elseif(wpl_request::getVar('uid', 0)) $user_id = wpl_request::getVar('uid', 0);
                
			self::set_profile_page_params($user_id);
		}
		elseif($view == 'property_listing'){}
		elseif($view == 'profile_listing'){}
		elseif($view == 'features')
		{
			$function = str_replace('features/', '', $wpl_qs);
			if(!trim($function)) $function = wpl_request::getVar('wpltype');
			_wpl_import('views.basics.features.wpl_'.$function);
			$obj = new wpl_features_controller();
			$obj->display();
		}
	}
	
    /**
     * Checke proeprty alias and 301 redirect the page to the correct link
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $proeprty_id
     */
	public static function check_property_link($proeprty_id)
	{
		$wpl_qs = urldecode(wpl_global::get_wp_qvar('wpl_qs'));
		
		/** check property alias for avoiding duplicate content **/
		$called_alias = $wpl_qs;
		$property_alias = urldecode(wpl_db::get('alias', 'wpl_properties', 'id', $proeprty_id));
		
		if(trim($property_alias) != '' and $called_alias != $property_alias)
		{
			$url = wpl_sef::get_wpl_permalink(true)."/".urlencode($property_alias);
			
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: ".$url);
			exit;
		}
	}
	
    /**
     * Sets property single page parameters
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $proeprty_id
     */
	public static function set_property_page_params($proeprty_id)
	{
		_wpl_import('libraries.property');
        
        $current_link_url = wpl_global::get_full_url();
		$property_data = wpl_property::get_property_raw_data($proeprty_id);
		
        if(trim($property_data['field_312']) != '') $property_title = $property_data['field_312'];
		elseif(trim($property_data['property_title']) == '') $property_title = wpl_property::generate_property_title($property_data);
		else $property_title = $property_data['property_title'];
        
		$html = wpl_html::getInstance();
		
		/** set title **/
		$html->set_title($property_title);
		
		/** set meta keywords **/
		$html->set_meta_keywords($property_data['meta_keywords']);
		
		/** set meta description **/
		$html->set_meta_description($property_data['meta_description']);
        
        /** SET og meta parameters for social websites like facebook etc **/
        wpl_html::$canonical = str_replace('&', '&amp;', $current_link_url);
        $html->set_custom_tag('<meta property="og:url" content="'.str_replace('&', '&amp;', $current_link_url).'" />');
        $html->set_custom_tag('<meta property="og:title" data-page-subject="true" content="'.$property_title.'" />');
        $html->set_custom_tag('<meta property="og:description" content="'.strip_tags($property_data['field_308']).'" />');
        
        $gallery = wpl_items::get_gallery($proeprty_id, $property_data['kind']);
        if(is_array($gallery) and count($gallery))
        {
            foreach($gallery as $image) $html->set_custom_tag('<meta property="og:image" content="'.$image['url'].'" />');
        }
	}
	
    /**
     * Sets profile single page parameters
     * @author Howard <howard@realtyna.com>
     * @static
     * @param int $user_id
     */
	public static function set_profile_page_params($user_id)
	{
        $current_link_url = wpl_global::get_full_url();
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
		wpl_html::$canonical = str_replace('&', '&amp;', $current_link_url);
        
		/** set title **/
		$html->set_title($user_title);
		
		/** set meta keywords **/
		$html->set_meta_keywords($user_keywords);
		
		/** set meta description **/
		$html->set_meta_description($user_description);
	}
}