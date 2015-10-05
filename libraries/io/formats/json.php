<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_io_format_json extends wpl_io_format_base
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
		return json_encode($this->utf8ize($response));
	}

    function utf8ize($d)
    {
        if(is_array($d))
        {
            foreach($d as $k=>$v)
            {
                $d[$k] = $this->utf8ize($v);
            }
        }
        elseif(is_string($d))
        {
            return utf8_encode($d);
        }
        
        return $d;
    }
}