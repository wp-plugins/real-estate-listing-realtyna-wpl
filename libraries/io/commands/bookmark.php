<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * The bookmark command
 * @author Chris <chris@realtyna.com>
 * @since WPL2.7.0
 * @package WPL
 * @date 2015/08/31
 */
class wpl_io_cmd_bookmark extends wpl_io_cmd_base
{
    protected $built = array();

    /**
     * This method is the main method of each commands
     * @author Chris <chris@realtyna.com>
     * @return mixed
     */
    public function build()
    {
        if(wpl_global::check_addon('pro') == false)
        {
            return $this->built['favorite'] = array('status'=>false);
        }

        if($this->params['function'] == 'remove_favorite')
        {
            $this->remove_favorite();
        }
        elseif($this->params['function'] == 'add_favorite')
        {
            $this->add_favorite();
        }
        
        return $this->built['favorite'] = array('status'=>true);
    }

    /**
     * Data validation
     * @author Chris <chris@realtyna.com>
     * @return boolean
     */
    public function validate()
    {
        if(trim($this->params['uid']) == '')
        {
            return false;
        }
        
        if(trim($this->params['pid']) == '')
        {
            return false;
        }
        
        if(trim($this->params['function']) == '')
        {
            return false;
        }
        
        return true;
    }

    /**
     * Remove property from favorites
     * @author Chris <chris@realtyna.com>
     */
    public function remove_favorite()
    {
        if(wpl_global::check_addon("addon_pro"))
        {
            wpl_addon_pro::favorite_add_remove($this->params['pid'], 'remove', $this->params['uid']);
        }
    }

    /**
     * add property to favorites
     * @author Chris <chris@realtyna.com>
     */
    public function add_favorite()
    {
        if(wpl_global::check_addon("addon_pro"))
        {
            wpl_addon_pro::favorite_add_remove($this->params['pid'], 'add', $this->params['uid']);
        }
    }
}