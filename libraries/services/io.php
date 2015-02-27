<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.io.global');
_wpl_import('libraries.property');

/**
 * IO service
 * @author Howard <howard@realtyna.com>
 * @date 01/20/2014
 * @package WPL
 */
class wpl_service_io
{
    /**
     * Service runner
     * @author Howard <howard@realtyna.com>
     * @return void
     */
	public function run()
	{
		/** recognizer **/
		$recognizer = wpl_request::getVar('get_realtyna_platform', 0);
		if($recognizer == 1) exit('WPL');
		
		$format = wpl_request::getVar('wplformat', '');
		$view = wpl_request::getVar('wplview', '');
		
		/** if it's not IO request **/
		if($format != 'io' or $view != 'io') return;
		
		$wpl_settings = wpl_global::get_settings();
		
		/** if IO is disabled **/
		if(!$wpl_settings['io_status']) return;
		
		$dapikey = wpl_request::getVar('dapikey', '');
		$dapisecret = wpl_request::getVar('dapisecret', '');
		
		/** if API key or API secret is invalid **/
		if($dapikey != $wpl_settings['api_key'] or $dapisecret != $wpl_settings['api_secret']) exit("ERROR: Signature is invalid.");
		
		$cmd = wpl_request::getVar('cmd', '');
		$io_object = new wpl_io_global();
		$commands = $io_object->get_commands();
		
		if(!in_array($cmd, $commands)) exit("ERROR: Command not found.");
		
		$dformat = wpl_request::getVar('dformat', 'json');
		$dformats = $io_object->get_formats();
		
		if(!in_array($dformat, $dformats)) exit("ERROR: Format not found.");
		
		$username = wpl_request::getVar('user');
		$password = wpl_request::getVar('pass');
		$dlang = wpl_request::getVar('dlang');
		
		$gvars = wpl_request::get('GET');
		$pvars = wpl_request::get('POST');
		$vars = array_merge($pvars, $gvars);
		$settings = array();
		
		$response = $io_object->response($cmd, $username, $password, $vars, $dlang, $settings, $dformat);
		
		/** Error **/
		if(is_string($response))
		{
			echo $response;
			exit;
		}
		
		$rendered = $io_object->render($cmd, $vars, $response, $dformat);
		
		if(is_array($rendered))
		{
			if($rendered['header'] != '') header($rendered['header']);
			echo $rendered['output'];
		}
		else echo $rendered;
		
		exit;
	}
	
    /**
     * Response function
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $response
     */
	private static function response($response)
	{
		echo json_encode($response);
		exit;
	}
}