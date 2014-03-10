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
}