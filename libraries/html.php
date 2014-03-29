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
	public static $scripts = array();
	
	/** constructor **/
	public function __construct($init = true)
	{
		/** initialize html library **/
		if($init)
		{
			$html = $this->getInstance(false);
			
			add_filter('wp_title', array($html, 'title'), 9999, 2);
			add_action('wp_head', array($html, 'generate_head'), 9999);
		}
	}
	
	/**
		Developed by : Howard
		Inputs : boolean init
		Outputs : void
		Date : 2013-08-19
		Description : This is a function for getting instance of html class
	**/
	public function getInstance($init = true)
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
	public function set_meta_keywords($keywords = array())
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
	public function set_meta_description($string)
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
	public function set_title($string = '')
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
	public function title($title, $separator)
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
	public function generate_head()
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
		Inputs : array instance
		Outputs : void
		Date : 2014-03-20
		Description : This is a function for loading any view on frontend
	**/
	public function load_view($function, $instance = array())
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
	public function load_profile_wizard($instance = array())
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
	public function load_add_edit_listing($instance = array())
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
	public function load_listing_manager($instance = array())
	{
		return wpl_html::load_view('b:listings:manager', $instance);
	}
}