<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_io_format_print_r extends wpl_io_format_base
{
    public function __construct($cmd, $params)
	{
        $this->init($cmd, $params);
	}
    
    /**
     * @param wpl_io_cmd_base $response
     * @return string
     */
	public function render($response)
	{
		return '<pre>'.print_r($response, true).'</pre>';
	}
}