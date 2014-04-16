<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** Print_r Format
** Developed 01/24/2014
**/

class wpl_io_format_print_r extends wpl_io_global
{
	var $error;
	
	public function __construct($cmd, $vars)
	{
	}
	
	public function render($response)
	{
		return '<pre>'.print_r($response, true).'</pre>';
	}
}