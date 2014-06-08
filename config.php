<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** wpl import function it used for importing overrided files automatically **/
function _wpl_import($include, $override = true, $return_path = false)
{
	$original_exploded = explode('.', $include);
	$path = WPL_ABSPATH . implode(DS, $original_exploded) . '.php';
	
	if($override)
	{
		$overrided_exploded = explode('.', 'overrides.'.$include);
		$overrided_path = WPL_ABSPATH . implode(DS, $overrided_exploded) . '.php';
		if(file_exists($overrided_path)) $path = $overrided_path;
		
		/** theme overrides just for tmpl files **/
		if(strpos($include, '.tmpl.') !== false)
		{
			/** main theme **/
			$wp_theme_path = get_template_directory();
			$overrided_file_in_theme = str_replace('views.', 'wplhtml.', $include);
			$overrided_file_in_theme = str_replace('tmpl.', '', $overrided_file_in_theme);
			if(substr($overrided_file_in_theme, 0, 8) == 'widgets.') $overrided_file_in_theme = 'wplhtml.'.$overrided_file_in_theme;
			
			$theme_exploded = explode('.', $overrided_file_in_theme);
			$theme_path = $wp_theme_path .DS. implode(DS, $theme_exploded) . '.php';
			
			if(file_exists($theme_path)) $path = $theme_path;
			
			/** child theme **/
			$wp_stylesheet = get_option('stylesheet');
			if(strpos($wp_stylesheet, '-child') !== false)
			{
				$wp_theme_name = get_option('template');
				$child_theme_path = $wp_theme_path. '-child' .DS. implode(DS, $theme_exploded) . '.php';
				$child_theme_path = str_replace($wp_theme_name. '-child', $wp_stylesheet, $child_theme_path);
				
				if(file_exists($child_theme_path)) $path = $child_theme_path;
			}
		}
	}
	
	if($return_path)
	{
		return $path;
	}
	
	if(file_exists($path)) require_once $path;
}

/** import wordpress files **/
function _wp_import($include, $override = true, $return_path = false)
{
	$original_exploded = explode('.', $include);
	$path = ABSPATH . implode(DS, $original_exploded) . '.php';
	
	if($override)
	{
		$overrided_exploded = explode('.', 'overrides.'.$include);
		$overrided_path = ABSPATH . implode(DS, $overrided_exploded) . '.php';
		if(file_exists($overrided_path)) $path = $overrided_path;
	}
	
	if($return_path)
	{
		return $path;
	}
	
	if(file_exists($path)) require_once $path;
}