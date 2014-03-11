<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.notifications.notification_manager');
class wpl_notifications_controller extends wpl_controller
{
    var $tpl_path = 'views.backend.notifications.tmpl';

    public function display()
    {
        /** check permission * */
        wpl_global::min_access('administrator');
        $function = wpl_request::getVar('wpl_function');

        if($function == 'set_enabled_notification') $this->set_enabled_notification(wpl_request::getVar('notification_id'), wpl_request::getVar('enabled_status'));
        elseif($function == 'save_notification') $this->save_notification(wpl_request::getVar('info'));
    }

    private function set_enabled_notification($notification_id, $enabled_status)
    {
        $res = wpl_notification_manager::update_one($notification_id, 'enabled', $enabled_status);

        $message = $res ? __('Operation was successful.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
        $response = array('success' => $res, 'message' => $message);
        echo json_encode($response);
        exit;
    }

    private function save_notification($info)
    {
        wpl_notification_manager::save_notification($info);
        exit;
    }
}