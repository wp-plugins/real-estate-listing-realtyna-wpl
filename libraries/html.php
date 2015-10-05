<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * HTML Library
 * @author Howard R <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 10/08/2013
 * @package WPL
 */
class wpl_html
{
	public static $document = NULL;
	public static $title;
    public static $content_title;
    public static $content_title_id;
	public static $meta_keywords = array();
	public static $meta_description;
    public static $footer_strings = array();
    public static $custom_strings = array();
    public static $canonical;
	public static $scripts = array();
    
    /**
     * Constructor function
     * @author Howard R <howard@realtyna.com>
     * @param type $init
     */
	public function __construct($init = true)
	{
		/** initialize html library **/
		if($init)
		{
			$html = $this->getInstance(false);
			$client = wpl_global::get_client();
            
			add_filter('wp_title', array($html, 'title'), 9999, 2);
			add_action('wp_head', array($html, 'generate_head'), 9999);
            
            if($client == 0)
            {
                /** SET WPL canonical **/
                remove_action('wp_head', 'rel_canonical');
                add_action('wp_head', array($html, 'generate_canonical'), 9999);
                
                add_action('wp_footer', array($html, 'generate_footer'), 9999);
            }
            elseif($client == 1) add_action('in_admin_footer', array($html, 'generate_footer'), 9999);
		}
	}
    
    /**
     * This is a function for getting instance of html class
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param boolean $init
     * @return object HTML class
     */
	public static function getInstance($init = true)
	{
		if(!self::$document)
		{
			self::$document = new wpl_html($init);
		}

		return self::$document;
	}
	
    /**
     * This is a function for setting meta keywords
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param array $keywords
     */
	public static function set_meta_keywords($keywords = array())
	{
		if(is_array($keywords))
		{
			foreach($keywords as $keyword) array_push(self::$meta_keywords, $keyword);
		}
		else
		{
			array_push(self::$meta_keywords, strip_tags($keywords));
		}
	}
    
    /**
     * This is a function for setting meta description
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $string
     * @return boolean
     */
	public static function set_meta_description($string)
	{
		$string = (string) strip_tags($string);
		if(trim($string) == '') return false;
		
		self::$meta_description = $string;
	}
	
    /**
     * This is a function for setting the title
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param type $string
     * @return boolean
     */
	public static function set_title($string = '')
	{
		$string = (string) strip_tags($string);
		if(trim($string) == '') return false;
		
		self::$title = $string;
	}
    
    /**
     * This is a function for filtering title
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $title
     * @param string $separator
     * @return string $title
     */
	public static function title($title, $separator = '')
	{
		if(trim(self::$title) != '') return self::$title;
		else return $title;
	}
    
    /**
     * This is a function for printing needed codes in footer
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $string
     * @return boolean
     */
	public static function set_custom_tag($string)
	{
		$string = (string) $string;
		if(trim($string) == '') return false;
		
        array_push(self::$custom_strings, $string);
	}
    
    /**
     * This is a function for generating canonical tag
     * @author Howard R <howard@realtyna.com>
     * @static
     * @global object $wp_the_query WordPress Query object
     * @return void
     */
	public static function generate_canonical()
	{
        /** Original WordPress code **/
        if(!is_singular()) return;
        
        global $wp_the_query;
        if(!$id = $wp_the_query->get_queried_object_id()) return;
        
        /** WPL canonical **/
        if(self::$canonical)
        {
            echo PHP_EOL;
            echo '<link rel="canonical" href="'.self::$canonical.'" />';
            return;
        }

        /** Original WordPress code **/
        $link = get_permalink($id);
        if($page = get_query_var('cpage')) $link = get_comments_pagenum_link($page);
        
        echo PHP_EOL;
        echo '<link rel="canonical" href="'.$link.'" />';
	}
    
    /**
     * This is a function for printing meta keywords and meta descriptions
     * @author Howard R <howard@realtyna.com>
     * @static
     */
	public static function generate_head()
	{
		/** generate meta keywords **/
		if(self::$meta_keywords)
		{
			echo '<meta name="keywords" content="'.implode(',', self::$meta_keywords).'" />';
		}
		
		/** generate meta description **/
		if(self::$meta_description)
		{
			echo '<meta name="description" content="'.self::$meta_description.'" />';
		}
        
        /** Printing Custom Tags **/
		if(isset(self::$custom_strings) and count(self::$custom_strings))
		{
            $strings = array_unique(self::$custom_strings);
            foreach($strings as $key=>$string)
            {
                echo PHP_EOL;
                echo $string;
                echo PHP_EOL;
            }
            
            /** make custom string empty **/
            self::$custom_strings = array();
		}
	}
    
    /**
     * This is a function for printing needed codes in footer
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $string
     * @return boolean
     */
	public static function set_footer($string)
	{
		$string = (string) $string;
		if(trim($string) == '') return false;
		
        array_push(self::$footer_strings, $string);
	}
    
    /**
     * This is a function for printing needed codes in footer
     * @author Howard R <howard@realtyna.com>
     * @static
     */
	public static function generate_footer()
	{
		/** printing footer strings **/
		if(isset(self::$footer_strings) and count(self::$footer_strings))
		{
            $strings = array_unique(self::$footer_strings);
            foreach($strings as $key=>$string)
            {
                echo PHP_EOL;
                echo $string;
                echo PHP_EOL;
            }
		}
	}
    
    /**
     * This is a function for loading any view on frontend
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $function
     * @param array $instance
     * @return string $output
     */
	public static function load_view($function, $instance = array())
	{
		if(trim($function) == '') return false;
		
		/** generate pages object **/
		$controller = new wpl_controller();
		
		ob_start();
		call_user_func(array($controller, $function), $instance);
		return $output = ob_get_clean();
	}
	
    /**
     * This is a function for loading profile wizard by shortcode
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param array $instance
     * @return string
     */
	public static function load_profile_wizard($instance = array())
	{
        /** PRO Addon **/
        if(!wpl_global::check_addon('PRO')) return __('PRO addon must be installed for this!', WPL_TEXTDOMAIN);
        
		return wpl_html::load_view('b:users:profile', $instance);
	}
    
    /**
     * This is a function for loading property wizard by shortcode
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param array $instance
     * @return string
     */
	public static function load_add_edit_listing($instance = array())
	{
        /** PRO Addon **/
        if(!wpl_global::check_addon('PRO')) return __('PRO addon must be installed for this!', WPL_TEXTDOMAIN);
        
		return wpl_html::load_view('b:listing:wizard', $instance);
	}
	
    /**
     * This is a function for loading property manager by shortcode
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param array $instance
     * @return string
     */
	public static function load_listing_manager($instance = array())
	{
        /** PRO Addon **/
        if(!wpl_global::check_addon('PRO')) return __('PRO addon must be installed for this!', WPL_TEXTDOMAIN);
        
		return wpl_html::load_view('b:listings:manager', $instance);
	}
}