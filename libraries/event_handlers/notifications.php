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
     * Listing Contact activity. It's for contacting to a listing agent.
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
        
        /** Disabled **/
        if(!$notification->notification_data['enabled']) return false;
        
        $property = wpl_property::get_property_raw_data($params[0]['property_id']);
        $user = wpl_users::get_user($property['user_id']);
        $replacements['listing_id'] = $property['mls_id'];
        
        $notification->replacements = $notification->set_replacements($replacements);
        $notification->rendered_content = $notification->render_notification_content();
        $notification->recipients = $notification->set_recipients(array($user->data->user_email));
        $notification->send();
        
        return true;
    }
    
    /**
     * User Contact activity. It's for contacting to user directly from profile show page
     * @author Howard <howard@realtyna.com>
     * @static
     * @param type $params
     * @return boolean
     */
    public static function contact_profile($params)
    {
        $replacements = $params[0];
        
        $notification = new wpl_notifications('email');
        $notification->prepare(3, $replacements);
        
        /** Disabled **/
        if(!$notification->notification_data['enabled']) return false;
        
        $user = wpl_users::get_user($params[0]['user_id']);
        
        $notification->replacements = $notification->set_replacements($replacements);
        $notification->rendered_content = $notification->render_notification_content();
        $notification->recipients = $notification->set_recipients(array($user->data->user_email));
        $notification->send();
        
        return true;
    }
}
