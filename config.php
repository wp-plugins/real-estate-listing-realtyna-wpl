<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * WPL import function. It used for importing overrided files automatically
 * @author Howard <howard@realtyna.com>
 * @param string $include
 * @param boolean $override
 * @param boolean $return_path
 * @return mixed
 */
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
			if(is_child_theme())
			{
                $wp_stylesheet_path = get_stylesheet_directory();
				$child_theme_path = $wp_stylesheet_path .DS. implode(DS, $theme_exploded) . '.php';
				
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

/**
 * Imports wordpress files
 * @author Howard <howard@realtyna.com>
 * @param string $include
 * @param boolean $override
 * @param boolean $return_path
 * @return string
 */
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