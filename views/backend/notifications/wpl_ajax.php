<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.notifications.notifications');

class wpl_notifications_controller extends wpl_controller
{
    public $tpl_path = 'views.backend.notifications.tmpl';

    public function display()
    {
        /** check permission **/
        wpl_global::min_access('administrator');
        $function = wpl_request::getVar('wpl_function');

        if($function == 'set_enabled_notification') $this->set_enabled_notification();
        elseif($function == 'save_notification') $this->save_notification();
    }

    private function set_enabled_notification()
    {
        $notification_id = wpl_request::getVar('notification_id');
        $enabled_status = wpl_request::getVar('enabled_status');
        
        $res = wpl_notifications::set($notification_id, 'enabled', $enabled_status);
        $message = $res ? __('Operation was successful.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
        
        $response = array('success'=>$res, 'message'=>$message);
        echo json_encode($response);
        exit;
    }

    private function save_notification()
    {
        $info = wpl_request::getVar('info');
        wpl_notifications::save_notification($info);
        
        $message = __('Operation was successful.', WPL_TEXTDOMAIN);
        
        $response = array('success'=>1, 'message'=>$message);
        echo json_encode($response);
        exit;
    }
}