<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_controller
{
	public $_wpl_client;
	public $_wpl_folder;
	public $_wpl_class;
	public $_wpl_function;
	public $_wpl_clients = array('b'=>'backend', 'f'=>'frontend', 'c'=>'basics');
	
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
		foreach($instance as $key=>$value) wpl_request::setVar($key, $value, 'method', false);
		
		if($this->_wpl_client == 'frontend')
		{
			/** call the function **/
			$result = $_wpl_obj->$_wpl_function($instance);
			return $result;
		}
		
		/** call the function **/
		$result = $_wpl_obj->$_wpl_function($instance);
	}
	
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
			
			include $path;
			return $output = ob_get_clean();
		}
		
		if(!wpl_file::exists($path)) exit("tpl not found!");
		
		/** including before start file **/
		if(wpl_file::exists($before_start)) include $before_start;
		include $path;
	}
	
	/** add separator betweens submenus **/
	public function wpl_add_separator()
	{
		include _wpl_import('views.basics.separator.default', true, true);
		return $separator_str;
	}
	
	/** for importing internal files in object mode **/
	protected function _wpl_import($include, $override = true)
	{
		$path = _wpl_import($include, $override, true);
		
		/** check existS **/
		if(wpl_file::exists($path)) include $path;
	}
	
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
	
	/** for showing data like iframe **/
	public function _wpl_plugin($function)
	{
		include _wpl_import('views.basics.plugin.iframe', true, true);
		exit;
	}
}