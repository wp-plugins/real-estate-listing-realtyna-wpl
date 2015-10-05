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
        
        // If the destination directory doesn't exist we need to create it
		if (!file_exists(dirname($dest)))
		{
			wpl_folder::create(dirname($dest));
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
        
        // If the destination directory doesn't exist we need to create it
		if (!file_exists(dirname($dest)))
		{
			wpl_folder::create(dirname($dest));
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
	public static function write($file, &$buffer, $append = false)
	{
		@set_time_limit(ini_get('max_execution_time'));

		// If the destination directory doesn't exist we need to create it
		if (!file_exists(dirname($file)))
		{
			wpl_folder::create(dirname($file));
		}

		$file = wpl_path::clean($file);
        
        if($append) $ret = is_int(file_put_contents($file, $buffer, FILE_APPEND)) ? true : false;
		else $ret = is_int(file_put_contents($file, $buffer)) ? true : false;

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

/**
 * XML File Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.8.1
 * @date 09/20/2014
 * @package WPL
 */
class wpl_xml
{
    /**
     * Full path of XML file
     * @var string 
     */
    public $path = NULL;
    
    /**
     * Node of single property
     * @var string 
     */
    public $node;
    
    /**
     * XML string
     * @var type 
     */
    public $string = NULL;
    
    /**
     * DOM object
     * @var object 
     */
    private $dom;
    
    /**
     * fieldPath=>[value1][value2] array
     * @var array 
     */
    public $values;
    
    /**
     * Constructor method
     * @author Howard <howard@realtyna.com>
     * @since WPL1.8.1
     * @param string $path
     * @param string $node
     */
    public function __construct($path = NULL, $node = NULL)
    {
        if($path) $this->path = $path;
        if($node) $this->node = $node;
    }
    
    /**
     * Get DOM object childs
     * @author Howard <howard@realtyna.com>
     * @since WPL1.8.1
     * @param DOM object $object
     * @param string $path
     * @return string|array
     */
    public function childs($object, $path = '')
    {
        if($object->hasChildNodes())
        {
            if($object->childNodes->length == 1)
            {
                if($object->nodeType != XML_TEXT_NODE)
                {
                    $childs = '['.$object->nodeValue.']';
                    
                    if(!isset($this->values[$path])) $this->values[$path] = '['.$object->nodeValue.']';
                    else $this->values[$path] .= '['.$object->nodeValue.']';
                }
            }
            else
            {
                foreach($object->childNodes as $childNode)
                {
                    if($childNode->nodeType == XML_TEXT_NODE) continue;
                    $result = $this->childs($childNode, ($path.$childNode->nodeName.'/'));
                    
                    if(!isset($childs[$path.$childNode->nodeName.'/']))
                    {
                        $childs[$path.$childNode->nodeName.'/'] = $result;
                    }
                    else
                    {
                        if(is_string($result)) $childs[$path.$childNode->nodeName.'/'] = $childs[$path.$childNode->nodeName.'/'].$result;
                    }
                }
            }
        }
        elseif($object->nodeType != XML_TEXT_NODE)
        {
            $childs = '['.$object->nodeValue.']';
            
            if(!isset($this->values[$path])) $this->values[$path] = '['.$object->nodeValue.']';
            else $this->values[$path] .= '['.$object->nodeValue.']';
        }
        
        return $childs;
    }
    
    /**
     * 
     * @param type $limit
     * @param string $Path
     * @return type
     */
    public function path($limit = 1, $Path = '')
    {
        /** First Validation **/
        if(!trim($this->node)) return array();
        
        $this->dom = new DOMDocument;
        
        if($this->path) $this->dom->load($this->path);
        elseif($this->string)
        {
            $this->dom = new DOMDocument;
            @$this->dom->loadXML($this->string);
        }
        
        /** reset values array **/
        $this->values = array();
        
        $categories = $this->dom->getElementsByTagName($this->node);
        $tree = array();
        if(!trim($Path)) $Path = '/'.$this->node.'/';
        
        for($i = 0; $i < $categories->length; $i++)
        {
            if($i >= $limit) break;

            $cat = $categories->item($i);
            
            $childs = array();
            foreach($cat->childNodes AS $child)
            {
                if($child->nodeType == XML_TEXT_NODE) continue;
                $childs[$Path.$child->nodeName.'/'] = $this->childs($child, $Path.$child->nodeName.'/');
            }

            $tree[$i] = $childs;
        }
        
        return $tree;
    }
    
    /**
     * Convert Paths array to cleaned Path=>Values
     * @author Howard <howard@realtyna.com>
     * @since WPL1.8.1
     * @param array $paths
     * @param array $keys
     * @return array
     */
    public function keys($paths, $keys = array())
    {
        if(!is_array($paths)) return $keys;
        
        foreach($paths as $key=>$value)
        {
            if(is_array($value)) $keys = $this->keys($value, $keys);
            else $keys[$key] = $value;
        }
        
        return $keys;
    }
}

/**
 * Chunk Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.8.1
 * @date 09/20/2014
 * @package WPL
 */
class wpl_chunk
{
    /**
     * options
     *
     * @var array Contains all major options
     * @access public
     */
    public $options = array(
      'path' => './',       // string The path to check for $file in
      'element' => '',      // string The XML element to return
      'chunkSize' => 512    // integer The amount of bytes to retrieve in each chunk
    );

    /**
     * file
     *
     * @var string The filename being read
     * @access public
     */
    public $file = '';
    
    /**
     * pointer
     *
     * @var integer The current position the file is being read from
     * @access public
     */
    public $pointer = 0;

    /**
     * handle
     *
     * @var resource The fopen() resource
     * @access private
     */
    private $handle = null;
    
    /**
     * reading
     *
     * @var boolean Whether the script is currently reading the file
     * @access private
     */
    private $reading = false;
    
    /**
     * readBuffer
     * 
     * @var string Used to make sure start tags aren't missed
     * @access private
     */
    private $readBuffer = '';

    /**
     * __construct
     * 
     * Builds the Chunk object
     *
     * @param string $file The filename to work with
     * @param array $options The options with which to parse the file
     * @author Dom Hastings
     * @access public
     */
    public function __construct($file, $options = array())
    {
        // merge the options together
        $this->options = array_merge($this->options, (is_array($options) ? $options : array()));

        // check that the path ends with a /
        if(substr($this->options['path'], -1) != '/')
        {
            $this->options['path'] .= '/';
        }

        // normalize the filename
        $file = basename($file);

        // make sure chunkSize is an int
        $this->options['chunkSize'] = intval($this->options['chunkSize']);

        // check it's valid
        if($this->options['chunkSize'] < 64)
        {
            $this->options['chunkSize'] = 512;
        }

        // set the filename
        $this->file = realpath($this->options['path'].$file);

        // check the file exists
        if(!file_exists($this->file))
        {
            throw new Exception('Cannot load file: '.$this->file);
        }

        // open the file
        $this->handle = fopen($this->file, 'r');

        // check the file opened successfully
        if(!$this->handle)
        {
            throw new Exception('Error opening file for reading');
        }
    }

    /**
     * __destruct
     * 
     * Cleans up
     *
     * @return void
     * @author Dom Hastings
     * @access public
     */
    public function __destruct()
    {
        // close the file resource
        fclose($this->handle);
    }

    /**
     * read
     * 
     * Reads the first available occurence of the XML element $this->options['element']
     *
     * @return string The XML string from $this->file
     * @author Dom Hastings
     * @access public
     */
    public function read()
    {
        // check we have an element specified
        if(!empty($this->options['element']))
        {
            // trim it
            $element = trim($this->options['element']);
        }
        else
        {
            $element = '';
        }

        // initialize the buffer
        $buffer = false;

        // if the element is empty
        if(empty($element))
        {
            // let the script know we're reading
            $this->reading = true;

            // read in the whole doc, cos we don't know what's wanted
            while ($this->reading)
            {
                $buffer .= fread($this->handle, $this->options['chunkSize']);
                $this->reading = (!feof($this->handle));
            }

            // return it all
            return $buffer;
        }
        // we must be looking for a specific element
        else
        {
            // set up the strings to find
            $open = '<'.$element.'>';
            $close = '</'.$element.'>';

            // let the script know we're reading
            $this->reading = true;

            // reset the global buffer
            $this->readBuffer = '';

            // this is used to ensure all data is read, and to make sure we don't send the start data again by mistake
            $store = false;

            // seek to the position we need in the file
            fseek($this->handle, $this->pointer);

            // start reading
            while($this->reading && !feof($this->handle))
            {
                // store the chunk in a temporary variable
                $tmp = fread($this->handle, $this->options['chunkSize']);

                // update the global buffer
                $this->readBuffer .= $tmp;

                // check for the open string
                $checkOpen = strpos($tmp, $open);

                // if it wasn't in the new buffer
                if(!$checkOpen && !($store))
                {
                    // check the full buffer (in case it was only half in this buffer)
                    $checkOpen = strpos($this->readBuffer, $open);

                    // if it was in there
                    if($checkOpen)
                    {
                        // set it to the remainder
                        $checkOpen = $checkOpen % $this->options['chunkSize'];
                    }
                }

                // check for the close string
                $checkClose = strpos($tmp, $close);

                // if it wasn't in the new buffer
                if(!$checkClose && ($store))
                {
                    // check the full buffer (in case it was only half in this buffer)
                    $checkClose = strpos($this->readBuffer, $close);

                    // if it was in there
                    if($checkClose)
                    {
                        // set it to the remainder plus the length of the close string itself
                        $checkClose = ($checkClose + strlen($close)) % $this->options['chunkSize'];
                    }
                }
                // if it was
                elseif($checkClose)
                {
                    // add the length of the close string itself
                    $checkClose += strlen($close);
                }

                // if we've found the opening string and we're not already reading another element
                if($checkOpen !== false && !($store))
                {
                    // if we're found the end element too
                    if($checkClose !== false)
                    {
                        // append the string only between the start and end element
                        $buffer .= substr($tmp, $checkOpen, ($checkClose - $checkOpen));

                        // update the pointer
                        $this->pointer += $checkClose;

                        // let the script know we're done
                        $this->reading = false;
                    }
                    else
                    {
                        // append the data we know to be part of this element
                        $buffer .= substr($tmp, $checkOpen);

                        // update the pointer
                        $this->pointer += $this->options['chunkSize'];

                        // let the script know we're gonna be storing all the data until we find the close element
                        $store = true;
                    }
                }
                // if we've found the closing element
                elseif($checkClose !== false)
                {
                    // update the buffer with the data upto and including the close tag
                    $buffer .= substr($tmp, 0, $checkClose);

                    // update the pointer
                    $this->pointer += $checkClose;

                    // let the script know we're done
                    $this->reading = false;
                }
                // if we've found the closing element, but half in the previous chunk
                elseif($store)
                {
                    // update the buffer
                    $buffer .= $tmp;

                    // and the pointer
                    $this->pointer += $this->options['chunkSize'];
                }
            }
        }

        // return the element (or the whole file if we're not looking for elements)
        return $buffer;
    }
}