<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** JSON Format
** Developed 01/24/2014
**/

class wpl_io_format_json extends wpl_io_global
{
	var $error;
	
	public function __construct($cmd, $vars)
	{
	}
	
	public function render($response)
	{
		return json_encode($response);
	}
}