<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.path');

/**
 * File Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 03/05/2013
 */
class wpl_file
{
    /**
     * Get extension of a file
     * @author Howard <howard@realtyna.com>
     * @param string $file
     * @return string
     */
	public static function getExt($file)
	{
        $ex = explode('.', $file);
		return end($ex);
	}

    /**
     * Strips extension
     * @author Howard <howard@realtyna.com>
     * @param string $file
     * @return string extension
     */
	public static function stripExt($file)
	{
		return preg_replace('#\.[^.]*$#', '', $file);
	}

    /**
     * Makes safe
     * @author Howard <howard@realtyna.com>
     * @param string $file
     * @return string
     */
	public static function makeSafe($file)
	{
		$regex = array('#(\.){2,}#', '#[^A-Za-z0-9\.\_\- ]#', '#^\.#');
		return preg_replace($regex, '', $file);
	}

    /**
     * Copies a file
     * @author Howard <howard@realtyna.com>
     * @param string $src
     * @param string $dest
     * @param string $path
     * @return boolean
     */
	public static function copy($src, $dest, $path = null)
	{
		// Prepend a base path if it exists
		if ($path)
		{
			$src = wpl_path::clean($path . '/' . $src);
			$dest = wpl_path::clean($path . '/' . $dest);
		}

		// Check src path
		if (!is_readable($src))
		{
			return false;
		}

		if (!@ copy($src, $dest))
		{
			return false;
		}
		
		return true;
	}

    /**
     * Delete a file or array of files
     * @author Howard <howard@realtyna.com>
     * @param string $file
     * @return boolean
     */
	public static function delete($file)
	{
		if (is_array($file))
		{
			$files = $file;
		}
		else
		{
			$files[] = $file;
		}

		foreach ($files as $file)
		{
			$file = wpl_path::clean($file);
			
			@chmod($file, 0777);
			@unlink($file);
		}

		return true;
	}
    
    /**
     * Moves a file
     * @author Howard <howard@realtyna.com>
     * @param string $src
     * @param string $dest
     * @param string $path
     * @return boolean
     */
	public static function move($src, $dest, $path = '')
	{
		if ($path)
		{
			$src = wpl_path::clean($path . '/' . $src);
			$dest = wpl_path::clean($path . '/' . $dest);
		}

		// Check src path
		if (!is_readable($src)) return false;
		if (!@rename($src, $dest)) return false;
		
		return true;
	}
    
    /**
     * Read the contents of a file
     * @author Howard <howard@realtyna.com>
     * @param string $filename
     * @return mixed data of file
     */
	public static function read($filename)
	{
		// Initialise variables.
		$data = '';
		$fh = fopen($filename, 'rb');
		
		if (false === $fh) return false;

		clearstatcache();

		if ($fsize = @filesize($filename))
		{
			$data = fread($fh, $fsize);
			
			fclose($fh);
			return $data;
		}
		else
		{
			fclose($fh);
			return false;
		}
	}
    
    /**
     * Write contents to a file
     * @author Howard <howard@realtyna.com>
     * @param string $file
     * @param string $buffer
     * @return boolean
     */
	public static function write($file, &$buffer)
	{
		@set_time_limit(ini_get('max_execution_time'));

		// If the destination directory doesn't exist we need to create it
		if (!file_exists(dirname($file)))
		{
			wpl_folder::create(dirname($file));
		}

		$file = wpl_path::clean($file);
		$ret = is_int(file_put_contents($file, $buffer)) ? true : false;

		return $ret;
	}

    /**
     * Moves an uploaded file to a destination folder
     * @author Howard <howard@realtyna.com>
     * @param string $src
     * @param string $dest
     * @return boolean
     */
	public static function upload($src, $dest)
	{
		// Ensure that the path is valid and clean
		$dest = wpl_path::clean($dest);
		$baseDir = dirname($dest);

		if (!file_exists($baseDir))
		{
			wpl_folder::create($baseDir);
		}

		if (is_writable($baseDir) && move_uploaded_file($src, $dest))
		{
			// Short circuit to prevent file permission errors
			if (wpl_path::setPermissions($dest)) $ret = true;
			else $ret = false;
		}
		else $ret = false;

		return $ret;
	}
    
    /**
     * Wrapper for the standard file_exists function
     * @author Howard <howard@realtyna.com>
     * @param string $file
     * @return boolean
     */
	public static function exists($file)
	{
		return is_file(wpl_path::clean($file));
	}
    
    /**
     * Returns the name, without any path.
     * @author Howard <howard@realtyna.com>
     * @param string $file
     * @return string
     */
	public static function getName($file)
	{
		// Convert back slashes to forward slashes
		$file = str_replace('\\', '/', $file);
		$slash = strrpos($file, '/');
		
		if ($slash !== false)
		{
			return substr($file, $slash + 1);
		}
		else
		{
			return $file;
		}
	}
}