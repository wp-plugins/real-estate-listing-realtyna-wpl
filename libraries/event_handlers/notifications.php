<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.notifications.notifications');

/**
 * WPL notifications
 * @author Howard <howard@realtyna.com>
 */
class wpl_events_notifications
{
    /**
     * Contact Agent activity
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $params
     * @return boolean
     */
    public static function contact_agent($params)
    {
        $replacements = $params[0];
        
        $notification = new wpl_notifications('email');
        $notification->prepare(2, $replacements);
        
        $property = wpl_property::get_property_raw_data($params[0]['property_id']);
        $user = wpl_users::get_user($property['user_id']);
        $replacements['listing_id'] = $property['mls_id'];
        
        $notification->replacements = $notification->set_replacements($replacements);
        $notification->rendered_content = $notification->render_notification_content();
        $notification->recipients = $notification->set_recipients(array($user->data->user_email));

        $notification->send();
        
        return true;
    }
}
