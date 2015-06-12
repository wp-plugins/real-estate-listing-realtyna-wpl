<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.path');

/**
 * File Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 03/05/2013
 * @package WPL
 */
class wpl_folder
{
    /**
     * Copy a folder.
     * @author Howard <howard@realtyna.com>
     * @param string $src
     * @param string $dest
     * @param string $path
     * @param boolean $force
     * @return boolean
     */
	public static function copy($src, $dest, $path = '', $force = false)
	{
		@set_time_limit(ini_get('max_execution_time'));

		if ($path)
		{
			$src = wpl_path::clean($path . '/' . $src);
			$dest = wpl_path::clean($path . '/' . $dest);
		}

		// Eliminate trailing directory separators, if any
		$src = rtrim($src, DIRECTORY_SEPARATOR);
		$dest = rtrim($dest, DIRECTORY_SEPARATOR);

		if (!self::exists($src)) return false;
		if (self::exists($dest) && !$force) return false;

		// Make sure the destination exists
		if (!self::create($dest)) return false;
		if (!($dh = @opendir($src))) return false;
		
		// Walk through the directory copying files and recursing into folders.
		while (($file = readdir($dh)) !== false)
		{
			$sfid = $src . '/' . $file;
			$dfid = $dest . '/' . $file;
			
			switch (filetype($sfid))
			{
				case 'dir':
				
					if ($file != '.' && $file != '..')
					{
						$ret = self::copy($sfid, $dfid, null, $force);
						if ($ret !== true)
						{
							return $ret;
						}
					}
					break;

				case 'file':
				
					if (!@copy($sfid, $dfid))
					{
						return false;
					}
					break;
			}
		}
		
		return true;
	}

    /**
     * Create a folder -- and all necessary parent folders.
     * @author Howard <howard@realtyna.com>
     * @staticvar int $nested
     * @param string $path
     * @param int $mode
     * @return boolean
     */
	public static function create($path = '', $mode = 0755)
	{
		// Initialise variables.
		static $nested = 0;

		// Check to make sure the path valid and clean
		$path = wpl_path::clean($path);

		// Check if parent dir exists
		$parent = dirname($path);
		
		if (!self::exists($parent))
		{
			// Prevent infinite loops!
			$nested++;
			if (($nested > 20) || ($parent == $path))
			{
				$nested--;
				return false;
			}

			// Create the parent directory
			if (self::create($parent, $mode) !== true)
			{
				// wpl_folder::create throws an error
				$nested--;
				return false;
			}

			// OK, parent directory has been created
			$nested--;
		}

		// Check if dir already exists
		if (self::exists($path))
		{
			return true;
		}

		// First set umask
		$origmask = @umask(0);

		// Create the path
		if (!$ret = @mkdir($path, $mode))
		{
			@umask($origmask);
			return false;
		}

		// Reset umask
		@umask($origmask);
		
		return $ret;
	}

    /**
     * Delete a folder.
     * @author Howard <howard@realtyna.com>
     * @param string $path
     * @return boolean
     */
	public static function delete($path)
	{
		@set_time_limit(ini_get('max_execution_time'));

		// Sanity check
		if (!$path)
		{
			return false;
		}

		// Check to make sure the path valid and clean
		$path = wpl_path::clean($path);

		// Is this really a folder?
		if (!is_dir($path))
		{
			return false;
		}

		// Remove all the files in folder if they exist; disable all filtering
		$files = self::files($path, '.', false, true, array(), array());
		if (!empty($files))
		{
			if (wpl_file::delete($files) !== true)
			{
				return false;
			}
		}

		// Remove sub-folders of folder; disable all filtering
		$folders = self::folders($path, '.', false, true, array(), array());
		foreach ($folders as $folder)
		{
			if (is_link($folder))
			{
				if (wpl_file::delete($folder) !== true)
				{
					return false;
				}
			}
			elseif (self::delete($folder) !== true)
			{
				return false;
			}
		}

		// In case of restricted permissions we zap it one way or the other
		// as long as the owner is either the webserver or the ftp.
		if (@rmdir($path))
		{
			$ret = true;
		}
		else
		{
			$ret = false;
		}
		
		return $ret;
	}

    /**
     * Moves a folder.
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

		if (!self::exists($src)) return false;
		if (self::exists($dest)) return false;

		if (!@rename($src, $dest))
		{
			return false;
		}
		
		return true;
	}

    /**
     * Wrapper for the standard file_exists function
     * @author Howard <howard@realtyna.com>
     * @param string $path
     * @return boolean
     */
	public static function exists($path)
	{
		return is_dir(wpl_path::clean($path));
	}

    /**
     * Utility function to read the files in a folder.
     * @author Howard <howard@realtyna.com>
     * @param string $path
     * @param string $filter
     * @param boolean $recurse
     * @param boolean $full
     * @param array $exclude
     * @param array $excludefilter
     * @return boolean
     */
	public static function files($path, $filter = '.', $recurse = false, $full = false, $exclude = array('.svn', 'CVS', '.DS_Store', '__MACOSX'), $excludefilter = array('^\..*', '.*~'))
	{
		// Check to make sure the path valid and clean
		$path = wpl_path::clean($path);

		// Is the path a folder?
		if (!is_dir($path))
		{
			return false;
		}

		// Compute the excludefilter string
		if (count($excludefilter))
		{
			$excludefilter_string = '/(' . implode('|', $excludefilter) . ')/';
		}
		else
		{
			$excludefilter_string = '';
		}

		// Get the files
		$arr = self::_items($path, $filter, $recurse, $full, $exclude, $excludefilter_string, true);

		// Sort the files
		asort($arr);
		return array_values($arr);
	}

    /**
     * Utility function to read the folders in a folder.
     * @author Howard <howard@realtyna.com>
     * @param string $path
     * @param string $filter
     * @param int $recurse
     * @param boolean $full
     * @param array $exclude
     * @param array $excludefilter
     * @return mixed
     */
	public static function folders($path, $filter = '.', $recurse = false, $full = false, $exclude = array('.svn', 'CVS', '.DS_Store', '__MACOSX'), $excludefilter = array('^\..*'))
	{
		// Check to make sure the path valid and clean
		$path = wpl_path::clean($path);

		// Is the path a folder?
		if (!is_dir($path))
		{
			return false;
		}

		// Compute the excludefilter string
		if (count($excludefilter))
		{
			$excludefilter_string = '/(' . implode('|', $excludefilter) . ')/';
		}
		else
		{
			$excludefilter_string = '';
		}

		// Get the folders
		$arr = self::_items($path, $filter, $recurse, $full, $exclude, $excludefilter_string, false);

		// Sort the folders
		asort($arr);
		return array_values($arr);
	}

    /**
     * Function to read the files/folders in a folder.
     * @author Howard <howard@realtyna.com>
     * @param string $path
     * @param string $filter
     * @param int $recurse
     * @param boolean $full
     * @param array $exclude
     * @param string $excludefilter_string
     * @param mixed $findfiles
     * @return array
     */
	protected static function _items($path, $filter, $recurse, $full, $exclude, $excludefilter_string, $findfiles)
	{
		@set_time_limit(ini_get('max_execution_time'));

		// Initialise variables.
		$arr = array();

		// Read the source directory
		if (!($handle = @opendir($path)))
		{
			return $arr;
		}

		while (($file = readdir($handle)) !== false)
		{
			if ($file != '.' && $file != '..' && !in_array($file, $exclude)
				&& (empty($excludefilter_string) || !preg_match($excludefilter_string, $file)))
			{
				// Compute the fullpath
				$fullpath = $path . '/' . $file;

				// Compute the isDir flag
				$isDir = is_dir($fullpath);

				if (($isDir xor $findfiles) && preg_match("/$filter/", $file))
				{
					// (fullpath is dir and folders are searched or fullpath is not dir and files are searched) and file matches the filter
					if ($full)
					{
						// Full path is requested
						$arr[] = $fullpath;
					}
					else
					{
						// Filename is requested
						$arr[] = $file;
					}
				}
				
				if ($isDir && $recurse)
				{
					// Search recursively
					if (is_integer($recurse))
					{
						// Until depth 0 is reached
						$arr = array_merge($arr, self::_items($fullpath, $filter, $recurse - 1, $full, $exclude, $excludefilter_string, $findfiles));
					}
					else
					{
						$arr = array_merge($arr, self::_items($fullpath, $filter, $recurse, $full, $exclude, $excludefilter_string, $findfiles));
					}
				}
			}
		}
		
		closedir($handle);
		return $arr;
	}

    /**
     * Makes path name safe to use.
     * @author Howard <howard@realtyna.com>
     * @param string $path
     * @return string
     */
	public static function makeSafe($path)
	{
		$regex = array('#[^A-Za-z0-9:_\\\/-]#');
		return preg_replace($regex, '', $path);
	}
}
