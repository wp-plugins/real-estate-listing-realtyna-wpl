<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Path Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.0.0
 * @date 03/05/2013
 * @package WPL
 */
class wpl_path
{
    /**
     * Is chmod available
     * @author Howard <howard@realtyna.com>
     * @param string $path
     * @return boolean
     */
	public static function canChmod($path)
	{
		$perms = fileperms($path);
		if ($perms !== false)
		{
			if (@chmod($path, $perms ^ 0001))
			{
				@chmod($path, $perms);
				return true;
			}
		}

		return false;
	}

    /**
     * set permissions for a file or directory
     * @author Howard <howard@realtyna.com>
     * @param string $path
     * @param string $filemode
     * @param string $foldermode
     * @return boolean
     */
	public static function setPermissions($path, $filemode = '0644', $foldermode = '0755')
	{
		// Initialise return value
		$ret = true;

		if (is_dir($path))
		{
			$dh = opendir($path);

			while ($file = readdir($dh))
			{
				if ($file != '.' && $file != '..')
				{
					$fullpath = $path . '/' . $file;
					if (is_dir($fullpath))
					{
						if (!wpl_path::setPermissions($fullpath, $filemode, $foldermode))
						{
							$ret = false;
						}
					}
					else
					{
						if (isset($filemode))
						{
							if (!@ chmod($fullpath, octdec($filemode)))
							{
								$ret = false;
							}
						}
					}
				}
			}
			
			closedir($dh);
			if (isset($foldermode))
			{
				if (!@ chmod($path, octdec($foldermode)))
				{
					$ret = false;
				}
			}
		}
		else
		{
			if (isset($filemode))
			{
				$ret = @ chmod($path, octdec($filemode));
			}
		}

		return $ret;
	}

    /**
     * Get permissions
     * @author Howard <howard@realtyna.com>
     * @param string $path
     * @return string
     */
	public static function getPermissions($path)
	{
		$path = wpl_path::clean($path);
		$mode = @ decoct(@ fileperms($path) & 0777);

		if (strlen($mode) < 3)
		{
			return '---------';
		}

		$parsed_mode = '';
		for ($i = 0; $i < 3; $i++)
		{
			// read
			$parsed_mode .= ($mode{$i} & 04) ? "r" : "-";
			// write
			$parsed_mode .= ($mode{$i} & 02) ? "w" : "-";
			// execute
			$parsed_mode .= ($mode{$i} & 01) ? "x" : "-";
		}

		return $parsed_mode;
	}

    /**
     * Check
     * @author Howard <howard@realtyna.com>
     * @param string $path
     * @param string $ds
     * @return string
     */
	public static function check($path, $ds = DIRECTORY_SEPARATOR)
	{
		$path = wpl_path::clean($path);
		return $path;
	}

    /**
     * Clean path
     * @author Howard <howard@realtyna.com>
     * @param string $path
     * @param string $ds
     * @return string
     */
	public static function clean($path, $ds = DIRECTORY_SEPARATOR)
	{
		$path = trim($path);

		if (empty($path))
		{
			$path = ROOT_ADDR;
		}
		else
		{
			// Remove double slashes and backslashes and convert all slashes and backslashes to DIRECTORY_SEPARATOR
			$path = preg_replace('#[/\\\\]+#', $ds, $path);
		}

		return $path;
	}

    /**
     * Find a file in paths
     * @author Howard <howard@realtyna.com>
     * @param array $paths
     * @param string $file
     * @return boolean
     */
	public static function find($paths, $file)
	{
		settype($paths, 'array'); //force to array

		// Start looping through the path set
		foreach ($paths as $path)
		{
			// Get the path to the file
			$fullname = $path . '/' . $file;

			// Is the path based on a stream?
			if (strpos($path, '://') === false)
			{
				// Not a stream, so do a realpath() to avoid directory
				// traversal attempts on the local file system.
				$path = realpath($path); // needed for substr() later
				$fullname = realpath($fullname);
			}

			// The substr() check added to make sure that the realpath()
			// results in a directory registered so that
			// non-registered directories are not accessible via directory
			// traversal attempts.
			if (file_exists($fullname) && substr($fullname, 0, strlen($path)) == $path)
			{
				return $fullname;
			}
		}

		// Could not find the file in the set of paths
		return false;
	}
}
