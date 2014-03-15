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
	
	public function __construct($init = true)
	{
		/** initialize html library **/
		if($init)
		{
			$html = $this->getInstance(false);
			
			add_filter('wp_title', array($html, 'title'), 9999);
			add_action('wp_head', array($html, 'generate_head'), 9999);
		}
	}
	
	public function getInstance($init = true)
	{
		if(!self::$document)
		{
			self::$document = new wpl_html($init);
		}

		return self::$document;
	}
	
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
	
	public function set_meta_description($string)
	{
		$string = (string) $string;
		if(trim($string) == '') return false;
		
		self::$meta_description = $string;
	}
	
	public function set_title($string = '')
	{
		$string = (string) $string;
		if(trim($string) == '') return false;
		
		self::$title = $string;
	}
	
	public function title()
	{
		return self::$title;
	}
	
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
}