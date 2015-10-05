<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * Get translation keywords command
 * @author Chris <chris@realtyna.com>
 * @since WPL2.7.0
 * @package WPL
 * @date 2015/08/31
 */
class wpl_io_cmd_get_keywords extends wpl_io_cmd_base
{
    protected $built;

    /**
     * This method is the main method of each commands
     * @author Chris <chris@realtyna.com>
     * @return mixed
     */
    public function build()
    {
        $translates = wpl_addon_mobile_application::get_translates();
        $output = array();
        
        foreach($translates as $row)
        {
            $output[$row['name']][$row['keyword']] = $row['value'];
        }
        
        $this->built['keywords'] = $output;
        return $this->built;
    }

    /**
     * Data validation
     * @author Chris <chris@realtyna.com>
     * @return boolean
     */
    public function validate()
    {
        if(wpl_global::check_addon('mobile_application') == false)
        {
            $this->error = "Addon mobile application is not installed";
            return false;
        }
        
        return true;
    }
}