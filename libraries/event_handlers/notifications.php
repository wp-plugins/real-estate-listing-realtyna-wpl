<?php

/**
 * functionally of notification method
 * 
 * @author Kevin J <kevin@realtyna.com>
 */
class wpl_events_notifications
{
    /**
     * send email to agent for contacting
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param array $params
     * @return boolean
     */
    public static function send_favorite_contact_form($params)
    {
        //alway $params[0]['parameters'] array must to have a email member
        
        _wpl_import('libraries.notifications.email');
        
        $emails = array();
        foreach ($params[0]['favorites'] as $fav)
        {
            $id = wpl_property::get_property_user($fav['id']);
            $data = wpl_users::get_user($id);
            $emails[] = $data->user_email;
        }
        $email_notification = new wpl_notification_email('1', $params[0]['parameters']);
        return $email_notification->send($emails);
    }
}
