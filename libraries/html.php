<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** HTML Library
** Developed 08/19/2013
**/

class wpl_html
{
	public static $document = NULL;
	public static $title;
	public static $meta_keywords = array();
	public static $meta_description;
    public static $footer_strings = array();
	public static $scripts = array();
	
	/** constructor **/
	public function __construct($init = true)
	{
		/** initialize html library **/
		if($init)
		{
			$html = $this->getInstance(false);
			$client = wpl_global::get_client();
            
			add_filter('wp_title', array($html, 'title'), 9999, 2);
			add_action('wp_head', array($html, 'generate_head'), 9999);
            
            if($client == 0) add_action('wp_footer', array($html, 'generate_footer'), 9999);
            elseif($client == 1) add_action('in_admin_footer', array($html, 'generate_footer'), 9999);
		}
	}
	
	/**
		Developed by : Howard
		Inputs : boolean init
		Outputs : void
		Date : 2013-08-19
		Description : This is a function for getting instance of html class
	**/
	public static function getInstance($init = true)
	{
		if(!self::$document)
		{
			self::$document = new wpl_html($init);
		}

		return self::$document;
	}
	
	/**
		Developed by : Howard
		Inputs : array keywords
		Outputs : void
		Date : 2013-08-19
		Description : This is a function for setting meta keywords
	**/
	public static function set_meta_keywords($keywords = array())
	{
		if(is_array($keywords))
		{
			foreach($keywords as $keyword) array_push(self::$meta_keywords, $keyword);
		}
		else
		{
			array_push(self::$meta_keywords, $keywords);
		}
	}
	
	/**
		Developed by : Howard
		Inputs : string
		Outputs : void
		Date : 2013-08-19
		Description : This is a function for setting meta description
	**/
	public static function set_meta_description($string)
	{
		$string = (string) $string;
		if(trim($string) == '') return false;
		
		self::$meta_description = $string;
	}
	
	/**
		Developed by : Howard
		Inputs : string
		Outputs : void
		Date : 2013-08-19
		Description : This is a function for setting the title
	**/
	public static function set_title($string = '')
	{
		$string = (string) $string;
		if(trim($string) == '') return false;
		
		self::$title = $string;
	}
	
	/**
		Developed by : Howard
		Inputs : void
		Outputs : void
		Date : 2013-08-19
		Description : This is a function for filtering title
	**/
	public static function title($title, $separator)
	{
		if(trim(self::$title) != '') return self::$title;
		else return $title;
	}
	
	/**
		Developed by : Howard
		Inputs : void
		Outputs : void
		Date : 2013-08-19
		Description : This is a function for printing meta keywords and meta descriptions
	**/
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
	}
    
    /**
		Developed by : Howard
		Inputs : void
		Outputs : void
		Date : 2014-04-19
		Description : This is a function for printing needed codes in footer
	**/
	public static function set_footer($string)
	{
		$string = (string) $string;
		if(trim($string) == '') return false;
		
        array_push(self::$footer_strings, $string);
	}
    
    /**
		Developed by : Howard
		Inputs : void
		Outputs : void
		Date : 2014-04-19
		Description : This is a function for printing needed codes in footer
	**/
	public static function generate_footer()
	{
		/** printing footer strings **/
		if(isset(self::$footer_strings) and count(self::$footer_strings))
		{
            foreach(self::$footer_strings as $key=>$string)
            {
                echo PHP_EOL;
                echo $string;
                echo PHP_EOL;
            }
		}
	}
	
	/**
		Developed by : Howard
		Inputs : array instance
		Outputs : void
		Date : 2014-03-20
		Description : This is a function for loading any view on frontend
	**/
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
		Developed by : Howard
		Inputs : array instance
		Outputs : void
		Date : 2014-03-20
		Description : This is a function for loading profile wizard by shortcode
	**/
	public static function load_profile_wizard($instance = array())
	{
		return wpl_html::load_view('b:users:profile', $instance);
	}
	
	/**
		Developed by : Howard
		Inputs : array instance
		Outputs : void
		Date : 2014-03-25
		Description : This is a function for loading property wizard by shortcode
	**/
	public static function load_add_edit_listing($instance = array())
	{
		return wpl_html::load_view('b:listing:wizard', $instance);
	}
	
	/**
		Developed by : Howard
		Inputs : array instance
		Outputs : void
		Date : 2014-03-25
		Description : This is a function for loading property manager by shortcode
	**/
	public static function load_listing_manager($instance = array())
	{
		return wpl_html::load_view('b:listings:manager', $instance);
	}
}