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
		
		$public_key = wpl_request::getVar('public_key', '');
		$private_key = wpl_request::getVar('private_key', '');
		
		/** if API key or API secret is invalid **/
		if($public_key != $wpl_settings['io_public_key'] || $private_key != $wpl_settings['io_private_key'])
        {
            die("ERROR: Signature is invalid.");
        }
		
		$cmd = wpl_request::getVar('cmd', '');
		$io_object = new wpl_io_global();
		$commands = $io_object->get_commands();
		
		if(!in_array($cmd, $commands))
        {
            exit("ERROR: Command not found.");
        }
		
		$dformat = wpl_request::getVar('dformat', 'json');
		$dformats = $io_object->get_formats();
		
		if(!in_array($dformat, $dformats)) exit("ERROR: Format not found.");
		
		$username = base64_decode(wpl_request::getVar('user'));
		$password = base64_decode(wpl_request::getVar('pass'));

		$vars = array_merge(wpl_request::get('GET'), wpl_request::get('POST'));

		$response = $io_object->response($cmd, $username, $password, $vars, $dformat);
		

		$rendered = $io_object->render_format($cmd, $vars, $response, $dformat);

		if(is_array($rendered))
		{
			if($rendered['header'] != '')
            {
                header($rendered['header']);
            }
			echo $rendered['output'];
		}
		else
        {
            echo $rendered;
        }
		exit;
	}
}
