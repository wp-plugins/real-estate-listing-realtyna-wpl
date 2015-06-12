<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * The base class of all io formats
 * @author Chris <chris@realtyna.com>
 * @since WPL2.5.0
 * @package WPL
 * @date 2015/06/02
 */
abstract class wpl_io_format_base extends wpl_io_global
{
    protected $error;
    protected $params;
    protected $cmd;

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @param wpl_io_cmd_base $response
     * @return mixed
     */
    public abstract function render($response);


    public function init($cmd, $params)
    {
        $this->params = $params;
        $this->cmd = $cmd;

    }
}