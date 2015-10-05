<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Cache Library
 * @author Howard <howard@realtyna.com>
 * @since WPL2.4.0
 * @date 04/19/2015
 * @package WPL
 */
class wpl_cache
{
    const PATH = 'wp-content.cache.WPL';
    protected $path;
    
    /**
     * Returns cache instance
     * @author Howard <howard@realtyna.com>
     * @staticvar Singleton $instance The *Singleton* instances of this class.
     * @return Singleton instance
     */
    public static function getInstance()
    {
        static $instance = null;
        if(null === $instance) $instance = new static();
        
        return $instance;
    }
    
    /**
     * Protected constructor to prevent creating a new instance of the Singleton via the `new` operator from outside of this class.
     * @author Howard <howard@realtyna.com>
     */
    protected function __construct()
    {
        $this->path = str_replace('.php', '', _wp_import(self::PATH, true, true));
        
        /** Check for child websites **/
        $blog_id = wpl_global::get_current_blog_id();
        if($blog_id and $blog_id != 1) $this->path = rtrim($this->path, DS).$blog_id;
        
        /** Create WPL Cache Directory **/
        if(!wpl_folder::exists($this->path)) wpl_folder::create($this->path);
    }

    /**
     * Private clone method to prevent cloning of the instance of the Singleton instance.
     * @author Howard <howard@realtyna.com>
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the Singleton instance.
     * @author Howard <howard@realtyna.com>
     * @return void
     */
    private function __wakeup()
    {
    }
    
    public function write($file, $buffer)
    {
        return wpl_file::write($file, $buffer);
    }
    
    public function read($file)
    {
        return wpl_file::read($file);
    }
    
    public function delete($file)
    {
        return wpl_file::delete($file);
    }
    
    public function valid($file, $expiry = 86400, $delete = true)
    {
        if(!wpl_file::exists($file)) return false;
        
        $mtime = filemtime($file);
        
        if(($mtime+$expiry) > time()) return true;
        else
        {
            if($delete) $this->delete($file);
            return false;
        }
    }
    
    public function path($file)
    {
        return $this->path.DS.ltrim($file, DS);
    }
    
    public function get_path()
    {
        return $this->path;
    }
}