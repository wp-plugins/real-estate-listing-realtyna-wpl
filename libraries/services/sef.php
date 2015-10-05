<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * SEF service
 * @author Howard <howard@realtyna.com>
 * @date 08/19/2013
 * @package WPL
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
		/** get global settings **/
		$settings = wpl_global::get_settings();
		$wpl_qs = urldecode(wpl_global::get_wp_qvar('wpl_qs'));
		
        /** get view **/
		$this->view = wpl_sef::get_view($wpl_qs, $settings['sef_main_separator']);
        
		/** set vars **/
		wpl_sef::setVars($this->view, $wpl_qs);
        
        /** trigger event **/
		wpl_global::event_handler('wplview_detected', array('wplview'=>$this->view));
        
		if($this->view == 'property_show')
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
            
			if(trim($wpl_qs) != '') $this->check_property_link($proeprty_id);
			$this->set_property_page_params($proeprty_id);
		}
		elseif($this->view == 'profile_show')
		{
			$username = $wpl_qs;
            
            if(trim($username) != '') $user_id = wpl_users::get_id_by_username($username);
            elseif(wpl_request::getVar('sf_select_user_id', 0)) $user_id = wpl_request::getVar('sf_select_user_id', 0);
            elseif(wpl_request::getVar('uid', 0)) $user_id = wpl_request::getVar('uid', 0);
                
			$this->set_profile_page_params($user_id);
		}
		elseif($this->view == 'property_listing')
        {
            $this->set_property_listing_page_params();
        }
		elseif($this->view == 'profile_listing')
        {
            $this->set_profile_listing_page_params();
        }
		elseif($this->view == 'features')
		{
			$function = str_replace('features/', '', $wpl_qs);
            
			if(!trim($function)) $function = wpl_request::getVar('wpltype');
			_wpl_import('views.basics.features.wpl_'.$function);
            
			$obj = new wpl_features_controller();
			$obj->display();
		}
        elseif($this->view == 'addon_crm')
		{
			_wpl_import('views.frontend.addon_crm.wpl_main');
            
			$obj = new wpl_addon_crm_controller();
			$obj->display();
		}
        elseif($this->view == 'payments')
        {
            $this->set_payments_page_params();
        }
        elseif($this->view == 'addon_membership')
        {
            $this->set_addon_membership_page_params();
        }
        
        /** Print Geo Meta Tags **/
        $this->geotags();
        
        /** Print Geo Meta Tags **/
        $this->dublincore();
	}
	
    /**
     * Checke proeprty alias and 301 redirect the page to the correct link
     * @author Howard <howard@realtyna.com>
     * @param int $proeprty_id
     */
	public function check_property_link($proeprty_id)
	{
		$wpl_qs = urldecode(wpl_global::get_wp_qvar('wpl_qs'));
		
		/** check property alias for avoiding duplicate content **/
		$called_alias = $wpl_qs;
        
        $column = 'alias';
        $field_id = wpl_flex::get_dbst_id($column, wpl_property::get_property_kind($proeprty_id));
        $field = wpl_flex::get_field($field_id);
        
        if(isset($field->multilingual) and $field->multilingual and wpl_global::check_multilingual_status()) $column = wpl_addon_pro::get_column_lang_name($column, wpl_global::get_current_language(), false);
        
        $alias = wpl_db::get($column, 'wpl_properties', 'id', $proeprty_id);
        if(trim($alias) == '') $alias = wpl_property::update_alias(NULL, $proeprty_id);
        
		$property_alias = $proeprty_id.'-'.urldecode($alias);
		
		if(trim($property_alias) != '' and $called_alias != $property_alias)
		{
			$url = wpl_sef::get_wpl_permalink(true).'/'.urlencode($property_alias);
			
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: '.$url);
			exit;
		}
	}
	
    /**
     * Sets property single page parameters
     * @author Howard <howard@realtyna.com>
     * @param int $proeprty_id
     */
	public function set_property_page_params($proeprty_id)
	{
		_wpl_import('libraries.property');
        
        $current_link_url = wpl_global::get_full_url();
		$property_data = wpl_property::get_property_raw_data($proeprty_id);
        
        $locale = wpl_global::get_current_language();
		$this->property_page_title = wpl_property::update_property_page_title($property_data);
        
        $meta_keywords_column = 'meta_keywords';
        if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($meta_keywords_column, $property_data['kind'])) $meta_keywords_column = wpl_addon_pro::get_column_lang_name($meta_keywords_column, $locale, false);
        
        $this->property_keywords = $property_data[$meta_keywords_column];
        if(trim($this->property_keywords) == '') $this->property_keywords = wpl_property::get_meta_keywords($property_data);
        
        $meta_description_column = 'meta_description';
        if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($meta_description_column, $property_data['kind'])) $meta_description_column = wpl_addon_pro::get_column_lang_name($meta_description_column, $locale, false);
        
        $this->property_description = $property_data[$meta_description_column];
        if(trim($this->property_description) == '') $this->property_description = wpl_property::get_meta_description($property_data);
        
		$html = wpl_html::getInstance();
		
		/** set title **/
		$html->set_title($this->property_page_title);
		
		/** set meta keywords **/
		$html->set_meta_keywords($this->property_keywords);
		
		/** set meta description **/
		$html->set_meta_description($this->property_description);
        
        /** SET og meta parameters for social websites like facebook etc **/
        wpl_html::$canonical = str_replace('&', '&amp;', $current_link_url);
        
        /** Remove canonical tags **/
        $this->remove_canonical();
        
        $html->set_custom_tag('<meta property="og:type" content="property" />');
        $html->set_custom_tag('<meta property="og:locale" content="'.$locale.'" />');
        
        $content_column = 'field_308';
        if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($content_column, $property_data['kind'])) $content_column = wpl_addon_pro::get_column_lang_name($content_column, $locale, false);
        
        $html->set_custom_tag('<meta property="og:url" content="'.str_replace('&', '&amp;', $current_link_url).'" />');
        $html->set_custom_tag('<meta property="og:title" data-page-subject="true" content="'.$this->property_page_title.'" />');
        $html->set_custom_tag('<meta property="og:description" content="'.strip_tags($property_data[$content_column]).'" />');
        
        $html->set_custom_tag('<meta property="twitter:card" content="summary" />');
        $html->set_custom_tag('<meta property="twitter:title" content="'.$this->property_page_title.'" />');
        $html->set_custom_tag('<meta property="twitter:description" content="'.strip_tags($property_data[$content_column]).'" />');
        $html->set_custom_tag('<meta property="twitter:url" content="'.str_replace('&', '&amp;', $current_link_url).'" />');
        
        $gallery = wpl_items::get_gallery($proeprty_id, $property_data['kind']);
        if(is_array($gallery) and count($gallery))
        {
            foreach($gallery as $image)
            {
                $html->set_custom_tag('<meta property="og:image" content="'.$image['url'].'" />');
                $html->set_custom_tag('<meta property="twitter:image" content="'.$image['url'].'" />');
            }
        }
	}
	
    /**
     * Sets profile single page parameters
     * @author Howard <howard@realtyna.com>
     * @param int $user_id
     */
	public function set_profile_page_params($user_id)
	{
        $current_link_url = wpl_global::get_full_url();
		$user_data = (array) wpl_users::get_wpl_user($user_id);
		
		$this->user_title = '';
		$this->user_keywords = '';
		$this->user_description = __('Listings of', WPL_TEXTDOMAIN);
		
		if(trim($user_data['first_name']) != '')
		{
			$this->user_title .= $user_data['first_name'];
			$this->user_keywords .= $user_data['first_name'].',';
			$this->user_description .= ' '.$user_data['first_name'];
		}
		
		if(trim($user_data['last_name']) != '')
		{
			$this->user_title .= ' '.$user_data['last_name'];
			$this->user_keywords .= $user_data['last_name'].',';
			$this->user_description .= ' '.$user_data['last_name'];
		}
		
		if(trim($user_data['company_name']) != '')
		{
			$this->user_title .= ' - '.$user_data['company_name'];
			$this->user_keywords .= $user_data['company_name'].',';
			$this->user_description .= ' - '.$user_data['company_name'];
		}
		
		$this->user_title .= ' '.__('Listings', WPL_TEXTDOMAIN);
		$this->user_keywords = trim($this->user_keywords, ', ');
		$this->user_description .= ' '.__('which is located in', WPL_TEXTDOMAIN).' '.$user_data['location_text'];
		
		$html = wpl_html::getInstance();
		wpl_html::$canonical = str_replace('&', '&amp;', $current_link_url);
        
        /** Remove canonical tags **/
        $this->remove_canonical();
        
		/** set title **/
		$html->set_title($this->user_title);
		
		/** set meta keywords **/
		$html->set_meta_keywords($this->user_keywords);
		
		/** set meta description **/
		$html->set_meta_description($this->user_description);
	}
    
    /**
     * Sets property listing page parameters
     * @author Howard <howard@realtyna.com>
     */
    public function set_property_listing_page_params()
    {
    }
    
    /**
     * Sets profile listing page parameters
     * @author Howard <howard@realtyna.com>
     */
    public function set_profile_listing_page_params()
    {
    }
    
    /**
     * Sets payments page parameters
     * @author Howard <howard@realtyna.com>
     */
    public function set_payments_page_params()
    {
        $html = wpl_html::getInstance();
        
		/** set title **/
		$html->set_title(__('Payments', WPL_TEXTDOMAIN));
    }
    
    /**
     * Sets addon membership page parameters
     * @author Howard <howard@realtyna.com>
     */
    public function set_addon_membership_page_params()
    {
        $html = wpl_html::getInstance();
        
		/** set title **/
		$html->set_title(__('Members', WPL_TEXTDOMAIN));
    }
    
    /**
     * Sets Geo Meta Tags
     * @author Howard <howard@realtyna.com>
     * @return boolean
     */
    public function geotags()
    {
        $settings = wpl_global::get_settings();
        
        /** check status of geo tags **/
        if(!isset($settings['geotag_status']) or (isset($settings['geotag_status']) and !$settings['geotag_status'])) return false;
        
        $html = wpl_html::getInstance();
        
        if(trim($settings['geotag_region'])) $html->set_custom_tag('<meta name="geo.region" content="'.$settings['geotag_region'].'" />');
        if(trim($settings['geotag_placename'])) $html->set_custom_tag('<meta name="geo.placename" content="'.$settings['geotag_placename'].'" />');
        if(trim($settings['geotag_latitude']) and trim($settings['geotag_longitude'])) $html->set_custom_tag('<meta name="geo.position" content="'.$settings['geotag_latitude'].';'.$settings['geotag_longitude'].'" />');
        if(trim($settings['geotag_latitude']) and trim($settings['geotag_longitude'])) $html->set_custom_tag('<meta name="ICBM" content="'.$settings['geotag_latitude'].', '.$settings['geotag_longitude'].'" />');
    }
    
    /**
     * Sets Dublin Core Meta Tags
     * @author Howard <howard@realtyna.com>
     * @return boolean
     */
    public function dublincore()
    {
        $settings = wpl_global::get_settings();
        $dc_status = isset($settings['dc_status']) ? $settings['dc_status'] : false;
        
        /** check status of geo tags **/
        if(!$dc_status) return false;
        
        $current_link_url = wpl_global::get_full_url();
        $html = wpl_html::getInstance();
        
        /** WPL views and WordPress views (Page/Post) **/
        if((trim($this->view) != '' and $dc_status == 2) or $dc_status == 1)
        {
            if(trim($settings['dc_coverage']) != '') $html->set_custom_tag('<meta name="DC.coverage" content="'.$settings['dc_coverage'].'" />');
            if(trim($settings['dc_contributor']) != '') $html->set_custom_tag('<meta name="DC.contributor" content="'.$settings['dc_contributor'].'" />');
            if(trim($settings['dc_publisher']) != '') $html->set_custom_tag('<meta name="DC.publisher" content="'.$settings['dc_publisher'].'" />');
            if(trim($settings['dc_copyright']) != '') $html->set_custom_tag('<meta name="DC.rights" content="'.$settings['dc_copyright'].'" />');
            if(trim($settings['dc_source']) != '') $html->set_custom_tag('<meta name="DC.source" content="'.$settings['dc_source'].'" />');
            if(trim($settings['dc_relation']) != '') $html->set_custom_tag('<meta name="DC.relation" content="'.$settings['dc_relation'].'" />');

            $html->set_custom_tag('<meta name="DC.type" content="Text" />');
            $html->set_custom_tag('<meta name="DC.format" content="text/html" />');
            $html->set_custom_tag('<meta name="DC.identifier" content="'.$current_link_url.'" />');
            
            $locale = apply_filters('plugin_locale', get_locale(), WPL_TEXTDOMAIN);
            $html->set_custom_tag('<meta name="DC.language" scheme="RFC1766" content="'.$locale.'" />');
        }
        
        if($this->view == 'property_show')
        {
            $proeprty_id = wpl_request::getVar('pid');
            $property_data = wpl_property::get_property_raw_data($proeprty_id);
            $user_data = (array) wpl_users::get_user($property_data['user_id']);
            
            $html->set_custom_tag('<meta name="DC.title" content="'.$this->property_page_title.'" />');
            $html->set_custom_tag('<meta name="DC.subject" content="'.$this->property_page_title.'" />');
            $html->set_custom_tag('<meta name="DC.description" content="'.$this->property_description.'" />');
            $html->set_custom_tag('<meta name="DC.date" content="'.$property_data['add_date'].'" />');
            $html->set_custom_tag('<meta name="DC.creator" content="'.$user_data['data']->user_login.'" />');
        }
        elseif($this->view == 'profile_show')
        {
            $user_id = wpl_request::getVar('uid');
            $user_data = (array) wpl_users::get_user($user_id);
            
            $html->set_custom_tag('<meta name="DC.title" content="'.$this->user_title.'" />');
            $html->set_custom_tag('<meta name="DC.subject" content="'.$this->user_title.'" />');
            $html->set_custom_tag('<meta name="DC.description" content="'.$this->user_description.'" />');
            $html->set_custom_tag('<meta name="DC.date" content="'.$user_data['data']->user_registered.'" />');
            $html->set_custom_tag('<meta name="DC.creator" content="'.$user_data['data']->user_login.'" />');
        }
        elseif(is_single())
        {
            $post_author_id = wpl_global::get_post_field('post_author');
            $author_username = wpl_global::get_the_author_meta('user_login', $post_author_id);
            
            $html->set_custom_tag('<meta name="DC.title" content="'.wpl_global::get_the_title().'" />');
            $html->set_custom_tag('<meta name="DC.subject" content="'.wpl_global::get_the_title().'" />');
            $html->set_custom_tag('<meta name="DC.date" content="'.wpl_global::get_the_date().'" />');
            $html->set_custom_tag('<meta name="DC.creator" content="'.$author_username.'" />');
        }
    }
    
    /**
     * For removing canonical URLs from WPL pages
     * @author Howard <howard@realtyna.com>
     */
    public function remove_canonical()
    {
        /** Remove Yoast Canonical URL **/
        add_filter('wpseo_canonical', '__return_false');
    }
}