<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_request_controller
{
	public $_wpl_request_format;
	public $_wpl_client;
	public $_wpl_folder;
	public $_wpl_class;
	public $_wpl_function;
	public $_wpl_plugin;
	public $_wpl_clients = array('b'=>'backend', 'f'=>'frontend', 'c'=>'basics', 'a'=>'activities');
	
	function __construct($format)
	{
		$this->_wpl_request_format = $format;
		$ex = explode(':', $this->_wpl_request_format);
		
		$this->_wpl_client = array_search($this->_wpl_clients[$ex[0]], $this->_wpl_clients) ? $this->_wpl_clients[$ex[0]] : 'frontend';
		$this->_wpl_folder = $ex[1];
		$this->_wpl_file = 'wpl_'.$ex[2];
		$this->_wpl_plugin = isset($ex[3]) ? $ex[3] : '';
		$this->_wpl_class = self::get_class_name($ex);
		
		/** import file **/
		_wpl_import(self::get_class_path($ex));
	}
	
	public function run()
	{
		$_wpl_request_obj = new $this->_wpl_class();
		
		/** render just wpl plugin output (like tmpl=component in Joomla) **/
		if($this->_wpl_plugin == '1')
		{
			wpl_extensions::import_styles_scripts();
			$_wpl_request_obj->_wpl_plugin('display');
			exit;
		}
		
		$_wpl_request_obj->display();
		
		/** exit **/
		exit;
	}
	
	private function get_class_name($exploded_str)
	{
		if($this->_wpl_client == 'activities') return 'wpl_activity_'.$exploded_str[2].'_'.$exploded_str[1];
		else return 'wpl_'.$exploded_str[1].'_controller';
	}
	
	private function get_class_path($exploded_str)
	{
		if($this->_wpl_client == 'activities') return 'views.'.$this->_wpl_client.'.'.$this->_wpl_folder.'.'.$exploded_str[2];
		else return 'views.'.$this->_wpl_client.'.'.$this->_wpl_folder.'.'.$this->_wpl_file;
	}
}

$wpl_format = wpl_request::getVar('wpl_format');

if(trim($wpl_format) != '')
{
	_wpl_import('libraries.activities');
	$wpl_request_controller = new wpl_request_controller($wpl_format);
	
	/** actiob fur triggering request **/
    $client = wpl_global::get_client();
    
    if($client == 1) $hook = 'init'; # WordPress Backend
    elseif($client == 0) $hook = 'init'; # WordPress Frontend
    
	add_action($hook, array($wpl_request_controller, 'run'), 1);
}