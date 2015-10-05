<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * WPL Core service
 * @author Howard <howard@realtyna.com>
 * @date 9/28/2015
 * @package WPL
 */
class wpl_service_wpl
{
    /**
     * Service runner
     * @author Howard <howard@realtyna.com>
     * @return void
     */
	public function run()
	{
        // Run WPL delete user function when a user removed from WordPress
        add_action('delete_user', array('wpl_users', 'delete_user'), 10, 1);
	}
}