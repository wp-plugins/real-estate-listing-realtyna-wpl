<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** theme Library
** Developed 09/30/2013
**/

class wpl_theme
{
	public function get_head()
	{
		get_header();
	}
	
	public function get_footer()
	{
		get_footer();
	}
	
	public function wpl_head()
	{
		wp_head();
	}
	
	public function wpl_title()
	{
		wp_title();
	}
	
	public function get_sidebar($sidebar_name = 'sidebar-1')
	{
		get_sidebar();
	}
	
	public function get_menu($args = array())
	{
		wp_nav_menu($args);
	}
	
	/**
	** FINAL FUNCTIONS
	** These are WP functions
	**/
	final public function is_active_sidebar($sidebar_name){ return is_active_sidebar($sidebar_name); }
	final public function load_sidebar($sidebar_name){ dynamic_sidebar($sidebar_name); }
	
	/** use this function for including theme parts (child theme compatible) **/
	final public function get_template_part($slug, $name){ get_template_part($slug, $name); }
	
	final public function have_posts(){ return have_posts(); }
	final public function the_post(){ the_post(); }
	final public function the_title(){ the_title(); }
	final public function the_content(){ the_content(); }
	final public function the_permalink(){ the_permalink(); }
	final public function the_time($d){ the_time($d); }
	final public function the_author_posts_link(){ the_author_posts_link(); }
	final public function the_category($separator = '', $parents = '', $post_id = false){ the_category($separator, $parents, $post_id); }
	final public function the_title_attribute($args = array()){ the_title_attribute($args); }
	final public function the_excerpt(){ the_excerpt(); }
	final public function body_class($class = NULL){ body_class($class); }
	final public function the_ID(){ the_ID(); }
	
	/** getting the post id **/
	final public function get_the_ID(){ return get_the_ID(); }
	
	final public function wp_footer(){ wp_footer(); }
	final public function language_attributes($doctype = 'html'){ language_attributes($doctype); }
	final public function home_url($path = '', $scheme = NULL){ return home_url($path, $scheme); }
	final public function comments_template($file = '/comments.php ', $separate_comments = false){ comments_template($file, $separate_comments); }
	final public function wp_login_url($redirect = NULL){ return wp_login_url($redirect); }
	final public function wp_registration_url(){ return wp_registration_url(); }
	final public function is_front_page(){ return is_front_page(); }
	final public function get_theme_url(){ return get_template_directory_uri(); }
}