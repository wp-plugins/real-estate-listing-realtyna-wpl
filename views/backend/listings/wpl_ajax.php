<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.property');

class wpl_listings_controller extends wpl_controller
{
    public function display()
    {
        $function = wpl_request::getVar('wpl_function');
        $pid = wpl_request::getVar('pid');
               
        if($function == 'purge_property')
		{
            $this->purge_property($pid);
		}
        elseif($function == 'update_property')
        {
            $action = wpl_request::getVar('action');
            $value = wpl_request::getVar('value');
            
            $this->update_property($pid, $action, $value);
        }
        elseif($function == 'change_user')
        {
            $this->change_user();
        }
    }
    
    /**
     * author Francis
     * @param int $pid
     * desctiption: purge one property with the condition of property id
     */
    private function purge_property($pid)
    {
		/** property data **/
		$property_data = wpl_property::get_property_raw_data($pid);
		
		/** purge property **/
		if(wpl_users::check_access('delete', $property_data['user_id']))
		{
			$res = (int) wpl_property::purge($pid, true);
			$message = __("Property purged.", WPL_TEXTDOMAIN);
		}
		else
		{
			$res = 0;
			$message = __("You don't have access to this action.", WPL_TEXTDOMAIN);
		}
		
		/** echo response **/
		echo json_encode(array('success'=>$res, 'message'=>$message, 'data'=>NULL));
		exit;
    }
    
    /**
     * author Francis
     * @param int $pid
     * @param string $action
     * @param int $value
     * description: update 'confirmed' and 'deleted' fields of one property
     */
    private function update_property($pid, $action, $value)
    {
		/** property data **/
		$property_data = wpl_property::get_property_raw_data($pid);
		
        if($action == 'confirm')
		{
			if(wpl_users::check_access('confirm', $property_data['user_id']))
			{
				/** confirm property **/
		        $res = wpl_property::confirm($pid, $value, true);
				$message = __("Operation was successful.", WPL_TEXTDOMAIN);
			}
			else
			{
				$res = 0;
				$message = __("You don't have access to this action.", WPL_TEXTDOMAIN);
			}
		}
        elseif($action == 'trash')
        {
			if(wpl_users::check_access('delete', $property_data['user_id']))
			{
				/** delete property **/
		        $res = wpl_property::delete($pid, $value, true);
				$message = __("Operation was successful.", WPL_TEXTDOMAIN);
			}
			else
			{
				$res = 0;
				$message = __("You don't have access to this action.", WPL_TEXTDOMAIN);
			}
		}
		
		/** echo response **/
		$res = (int) $res;
		$data = NULL;
		
		echo json_encode(array('success'=>$res, 'message'=>$message, 'data'=>$data));
        exit;
    }
    
    /**
     * author Howard
     * desctiption: change user of a property
     */
    private function change_user()
    {
        $pid = wpl_request::getVar('pid');
        $uid = wpl_request::getVar('uid');
		
		/** purge property **/
		if(wpl_users::check_access('change_user'))
		{
			$res = (int) wpl_property::change_user($pid, $uid);
			$message = __("User changed.", WPL_TEXTDOMAIN);
		}
		else
		{
			$res = 0;
			$message = __("You don't have access to this action.", WPL_TEXTDOMAIN);
		}
		
		/** echo response **/
		echo json_encode(array('success'=>$res, 'message'=>$message, 'data'=>NULL));
		exit;
    }
}