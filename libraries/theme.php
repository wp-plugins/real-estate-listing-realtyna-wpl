<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** theme Library
** Developed 09/30/2013
**/

class wpl_theme
{
	public static function get_head()
	{
		get_header();
	}
	
	public static function get_footer()
	{
		get_footer();
	}
	
	public static function wpl_head()
	{
		wp_head();
	}
	
	public static function wpl_title()
	{
		wp_title();
	}
	
	public static function get_sidebar($sidebar_name = 'sidebar-1')
	{
		get_sidebar();
	}
	
	public static function get_menu($args = array())
	{
		wp_nav_menu($args);
	}
	
	/**
	** FINAL FUNCTIONS
	** These are WP functions
	**/
	final public static function is_active_sidebar($sidebar_name){ return is_active_sidebar($sidebar_name); }
	final public static function load_sidebar($sidebar_name){ dynamic_sidebar($sidebar_name); }
	
	/** use this function for including theme parts (child theme compatible) **/
	final public static function get_template_part($slug, $name){ get_template_part($slug, $name); }
	
	final public static function have_posts(){ return have_posts(); }
	final public static function the_post(){ the_post(); }
	final public static function the_title(){ the_title(); }
	final public static function the_content(){ the_content(); }
	final public static function the_permalink(){ the_permalink(); }
	final public static function the_time($d){ the_time($d); }
	final public static function the_author_posts_link(){ the_author_posts_link(); }
	final public static function the_category($separator = '', $parents = '', $post_id = false){ the_category($separator, $parents, $post_id); }
	final public static function the_title_attribute($args = array()){ the_title_attribute($args); }
	final public static function the_excerpt(){ the_excerpt(); }
	final public static function body_class($class = NULL){ body_class($class); }
	final public static function the_ID(){ the_ID(); }
	
	/** getting the post id **/
	final public static function get_the_ID(){ return get_the_ID(); }
	
	final public static function wp_footer(){ wp_footer(); }
	final public static function language_attributes($doctype = 'html'){ language_attributes($doctype); }
	final public static function home_url($path = '', $scheme = NULL){ return home_url($path, $scheme); }
	final public static function comments_template($file = '/comments.php ', $separate_comments = false){ comments_template($file, $separate_comments); }
	final public static function wp_login_url($redirect = NULL){ return wp_login_url($redirect); }
	final public static function wp_registration_url(){ return wp_registration_url(); }
	final public static function is_front_page(){ return is_front_page(); }
	final public static function get_theme_url(){ return get_template_directory_uri(); }
}