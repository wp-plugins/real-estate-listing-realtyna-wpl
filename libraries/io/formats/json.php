<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');



class wpl_io_format_json extends wpl_io_format_base
{

    /**
     * @param wpl_io_cmd_base $response
     * @return string
     */
	public function render($response)
	{
		return json_encode($response);
	}


}