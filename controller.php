<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Main WPL Controller
 * @author Howard <howard@realtyna.com>
 * @since 1.0.0
 * @package WPL
 */
class wpl_controller
{
    /**
     *
     * @var string 
     */
	public $_wpl_client;
    
    /**
     *
     * @var string
     */
	public $_wpl_folder;
    
    /**
     *
     * @var string
     */
	public $_wpl_class;
    
    /**
     *
     * @var string
     */
	public $_wpl_function;
    
    /**
     *
     * @var array
     */
	public $_wpl_clients = array('b'=>'backend', 'f'=>'frontend', 'c'=>'basics');
    
    /**
     *
     * @var int
     */
    public static $_run = 0;
    
    /**
     * Wrapper class for some views
     * @var boolean
     */
    public $wrapper = 0;
    
    /**
     * Overwrite Parameters
     * @var boolean
     */
    public $parameter_overwrite = false;
	
    /**
     * Calls WPL views
     * @author Howard <howard@realtyna.com>
     * @param string $method
     * @param array $args
     * @return mixed
     */
	public function __call($method, $args)
	{
		$ex = explode(':', $method);
		
		$this->_wpl_client = array_search($this->_wpl_clients[$ex[0]], $this->_wpl_clients) ? $this->_wpl_clients[$ex[0]] : 'frontend';
		$this->_wpl_folder = $ex[1];
		$this->_wpl_file = 'wpl_main';
		$this->_wpl_class = 'wpl_'.$ex[1].'_controller';
		$_wpl_function = trim($ex[2]) != '' ? $ex[2] : 'display';
		
		_wpl_import('views.'.$this->_wpl_client.'.'.$this->_wpl_folder.'.'.$this->_wpl_file);
		$_wpl_obj = new $this->_wpl_class();
		
		/** parameter of shortcode (setted by user) **/
		$instance = (array) $args[0];
        
		/** set the parameters **/
		foreach($instance as $key=>$value) wpl_request::setVar($key, $value, 'method', $this->parameter_overwrite);
		
		if($this->_wpl_client == 'frontend')
		{
			/** call the function **/
			return $_wpl_obj->$_wpl_function($instance);
		}
		
        if($this->_wpl_client == 'backend') $_wpl_obj->wrapper = 1;
        
        /** call the function **/
		$_wpl_obj->$_wpl_function($instance);
	}
	
    /**
     * Renders a layout of WPL view
     * @author Howard <howard@realtyna.com>
     * @param string $path
     * @param string $tpl
     * @param boolean $return_path
     * @param boolean $string_output
     * @return mixed
     */
	public function render($path, $tpl = '', $return_path = false, $string_output = false)
	{
		$_wpl_tpl = trim($tpl) != '' ? $tpl : wpl_request::getVar('tpl', '', 'GET');
		if(trim($_wpl_tpl) == '') $_wpl_tpl = 'default';
		
		$path = _wpl_import($path.'.'.$_wpl_tpl, true, true);
		$before_start = str_replace('.php', '_before_start.php', $path);
		
		/** return path **/
		if($return_path) return $path;
		
		if($string_output)
		{
			ob_start();
			
			/** including before start file **/
			if(wpl_file::exists($before_start)) include $before_start;
            
            if($this->wrapper == 1) $this->wrapper($path);
            else include $path;
            
			return $output = ob_get_clean();
		}
		
		if(!wpl_file::exists($path)) exit("tpl not found!");
		
		/** including before start file **/
		if(wpl_file::exists($before_start)) include $before_start;
        
        if($this->wrapper == 1) $this->wrapper($path);
		else include $path;
	}
    
    /**
     * Wrapper file for backend views
     * @author Howard <howard@realtyna.com>
     * @param string $path
     */
    public function wrapper($path)
    {
        $this->wrapper++;
        include _wpl_import('views.basics.wrapper.default', true, true);
    }
	
    /**
     * Adds separator betweens submenus
     * @author Howard <howard@realtyna.com>
     * @return string
     */
	public function wpl_add_separator()
	{
		include _wpl_import('views.basics.separator.default', true, true);
		return $separator_str;
	}
    
    /**
     * For importing internal files in object mode
     * @author Howard <howard@realtyna.com>
     * @param string $include
     * @param boolean $override
     * @param boolean $set_footer
     * @param boolean $once
     * @return void
     */
	protected function _wpl_import($include, $override = true, $set_footer = false, $once = false)
	{
		$path = _wpl_import($include, $override, true);
		
		/** check exists **/
		if(!wpl_file::exists($path)) return;
        
        if(!$set_footer)
        {
            if(!$once) include $path;
            else include_once $path;
        }
        else
        {
            ob_start();
            
            if(!$once) include $path;
            else include_once $path;
            
            wpl_html::set_footer(ob_get_clean());
        }
	}
    
    /**
     * For rendering and returning section contents
     * @author Howard <howard@realtyna.com>
     * @param string $include
     * @param boolean $override
     * @param boolean $once
     * @return string
     */
	protected function _wpl_render($include, $override = true, $once = false)
	{
		$path = _wpl_import($include, $override, true);
        
		/** check exists **/
		if(!wpl_file::exists($path)) return;
        
        ob_start();
        
        if(!$once) include $path;
        else include_once $path;
        
        return ob_get_clean();
	}
	
    /**
     * Loads WPL views
     * @author Howard <howard@realtyna.com>
     * @param string $method
     * @return mixed
     */
	protected function load($method)
	{
		$ex = explode(':', $method);
		
		$this->_wpl_client = array_search($this->_wpl_clients[$ex[0]], $this->_wpl_clients) ? $this->_wpl_clients[$ex[0]] : 'frontend';
		$this->_wpl_folder = $ex[1];
		$this->_wpl_file = 'wpl_main';
		$this->_wpl_class = 'wpl_'.$ex[1].'_controller';
		$_wpl_function = trim($ex[2]) != '' ? $ex[2] : 'display';
		
		_wpl_import('views.'.$this->_wpl_client.'.'.$this->_wpl_folder.'.'.$this->_wpl_file);
		
		$_wpl_obj = new $this->_wpl_class(true);
		return $_wpl_obj->$_wpl_function();
	}
    
    /**
     * For showing data like iframe
     * @author Howard <howard@realtyna.com>
     * @param string $function
     */
	public function _wpl_plugin($function)
	{
		include _wpl_import('views.basics.plugin.iframe', true, true);
		exit;
	}
}