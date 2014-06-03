<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/* WPL WIDGET - 21/07/2013 */
class wpl_widget extends WP_Widget
{
	var $data;
	
	/*
	 * Get The List of Layouts in the Widget
	 */
	public function get_layouts($widget_name)
	{
		$path = WPL_ABSPATH. 'widgets' .DS. $widget_name .DS. 'tmpl';
		$layouts = wpl_folder::files($path, '.php', false, false);
		return $layouts;
	}
	
	/*
	 * List the layouts in <option> fields
	 */
	public function generate_layouts_selectbox($widget_name, $instance)
	{
		// Base Layouts
		$layouts = self::get_layouts($widget_name);
		$i = 0;
		$data = '';
        
		while($i < count($layouts))
		{
			$data .= '<option ';
			if(str_replace('.php', '', $layouts[$i]) == $instance['layout']) $data .= 'selected="selected" ';
			$data .= 'value="'.str_replace('.php', '', $layouts[$i]).'"';
			$data .= '>';
			$data .= str_replace('.php', '', $layouts[$i]);
			$data .= '</option>';
		    $i++;
		}
		
		return $data;
	}
    
    /*
	 * List the layouts in <option> fields
	 */
	public function generate_pages_selectbox($instance, $field_name = 'wpltarget')
	{
        $pages = wpl_global::get_wp_pages();
        $data = '';
        
        foreach($pages as $page)
        {
            $data .= '<option ';
			if(isset($instance[$field_name]) and $page->ID == $instance[$field_name]) $data .= 'selected="selected" ';
			$data .= 'value="'.$page->ID.'"';
			$data .= '>';
			$data .= substr($page->post_title, 0, 100);
			$data .= '</option>';
        }
		
		return $data;
	}
}