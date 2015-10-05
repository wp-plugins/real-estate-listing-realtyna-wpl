<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * WPL main ajax controller
 * @author Howard <howard@realtyna.com>
 * @since 1.0.0
 * @package WPL
 */
class wpl_request_controller
{
    /**
     *
     * @var string
     */
	public $_wpl_request_format;
    
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
     * @var string
     */
	public $_wpl_plugin;
    
    /**
     *
     * @var array
     */
	public $_wpl_clients = array('b'=>'backend', 'f'=>'frontend', 'c'=>'basics', 'a'=>'activities');
	
    /**
     * Constructor method
     * @author Howard <howard@realtyna.com>
     * @param string $format
     */
	public function __construct($format)
	{
		$this->_wpl_request_format = $format;
		$ex = explode(':', $this->_wpl_request_format);
		
		$this->_wpl_client = array_search($this->_wpl_clients[$ex[0]], $this->_wpl_clients) ? $this->_wpl_clients[$ex[0]] : 'frontend';
		$this->_wpl_folder = $ex[1];
		$this->_wpl_file = $this->get_file_name($ex[2]);
		$this->_wpl_plugin = isset($ex[3]) ? $ex[3] : '';
		$this->_wpl_class = $this->get_class_name($ex);
		
		/** import file **/
		_wpl_import($this->get_class_path());
	}
	
    /**
     * Runs Controller
     * @author Howard <howard@realtyna.com>
     */
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
	
    /**
     * Returns file name
     * @author Howard <howard@realtyna.com>
     * @param string $file
     * @return string
     */
    private function get_file_name($file)
	{
		if($this->_wpl_client == 'activities') return $file;
		else return 'wpl_'.$file;
	}
    
    /**
     * Returns controller name
     * @author Howard <howard@realtyna.com>
     * @param array $exploded_str
     * @return string
     */
	private function get_class_name($exploded_str)
	{
		if($this->_wpl_client == 'activities') return 'wpl_activity_'.$exploded_str[2].'_'.$exploded_str[1];
		else return 'wpl_'.$exploded_str[1].'_controller';
	}
	
    /**
     * Returns WPL path of controller
     * @author Howard <howard@realtyna.com>
     * @return string
     */
	private function get_class_path()
	{
		return 'views.'.$this->_wpl_client.'.'.$this->_wpl_folder.'.'.$this->_wpl_file;
	}
    
    public function start_process_service()
    {
        /** Run WPL Proccess service **/
        _wpl_import('libraries.services.process');
        
        $wpl_service_process = new wpl_service_process();
        $wpl_service_process->run();
    }
}

$wpl_format = wpl_request::getVar('wpl_format');

if(trim($wpl_format) != '')
{
    _wpl_import('libraries.activities');
	$wpl_request_controller = new wpl_request_controller($wpl_format);
	
	/** actiob fur triggering request **/
    $client = wpl_global::get_client();
    
    if($client == 1) $hook = 'wp_loaded'; # WordPress Backend
    elseif($client == 0) $hook = 'wp'; # WordPress Frontend
    
    add_action($hook, array($wpl_request_controller, 'start_process_service'), 1);
	add_action($hook, array($wpl_request_controller, 'run'), 2);
}