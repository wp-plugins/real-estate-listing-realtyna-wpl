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
        
        $property_id = $params[0]['property_id'];
        $property = wpl_property::get_property_raw_data($property_id);
        $user = wpl_users::get_user($property['user_id']);
        
        $property_title = wpl_property::update_property_title($property);
        $replacements['listing_id'] = '<a href="'.wpl_property::get_property_link(NULL, $property_id).'">'.$property['mls_id'].' ('.$property_title.')</a>';
        
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
    
    /**
     * Sends welcome email to user after registeration
     * @author Howard <howard@realtyna.com>
     * @static
     * @param array $params
     * @return boolean
     */
    public static function user_registered($params)
    {
        $replacements = $params[0];
        
        $notification = new wpl_notifications('email');
        $notification->prepare(5, $replacements);
        
        /** Disabled **/
        if(!$notification->notification_data['enabled']) return false;
        
        $user = wpl_users::get_user($params[0]['user_id']);
        $replacements['name'] = isset($user->data->wpl_data) ? $user->data->wpl_data->first_name : $user->data->display_name;
        $replacements['password'] = $params[0]['password'];
        $replacements['username'] = $user->data->user_login;
        
        $link = wpl_global::get_wp_site_url();
        $replacements['site_address'] = '<a target="_blank" href="'.$link.'">'.$link.'</a>';
        
        $notification->replacements = $notification->set_replacements($replacements);
        $notification->rendered_content = $notification->render_notification_content();
        $notification->recipients = $notification->set_recipients(array($user->data->user_email));
        $notification->send();
        
        return true;
    }


    public static function send_to_friend($params)
    {
        $replacements = $params[0];
        $notification = new wpl_notifications('email');
        $notification->prepare(6, $replacements);

        /** Disabled **/
        if(!$notification->notification_data['enabled']) return false;

        $property_id = $replacements['property_id'];
        $property = wpl_property::get_property_raw_data($property_id);

        $property_title = wpl_property::update_property_title($property);
        $replacements['listing_id'] = '<a href="'.wpl_property::get_property_link(NULL, $property_id).'">'.$property['mls_id'] .' ('.$property_title.')</a>';

        $details = '';
        foreach($replacements as $key=>$value)
        {
            if(in_array($key, array('property_id', 'listing_id')) or trim($value) == '') continue;
            $details .= '<strong>'.__($key, WPL_TEXTDOMAIN).': </strong><span>'.$value.'</span><br />';
        }

        $replacements['details'] = $details;

        $notification->replacements = $notification->set_replacements($replacements);
        $notification->rendered_content = $notification->render_notification_content();
        $notification->recipients = $notification->set_recipients(array($replacements['friends_email'], wpl_global::get_admin_id()));

        $notification->send();

        return true;
    }

    public static function request_a_visit($params)
    {
        $replacements = $params[0];

        $notification = new wpl_notifications('email');
        $notification->prepare(7, $replacements);

        /** Disabled **/
        if(!$notification->notification_data['enabled']) return false;

        $property_id = $replacements['property_id'];
        $property = wpl_property::get_property_raw_data($property_id);
        $user = wpl_users::get_user($property['user_id']);

        $property_title = wpl_property::update_property_title($property);
        $replacements['listing_id'] = '<a href="'.wpl_property::get_property_link(NULL, $property_id).'">'.$property['mls_id'] .' ('.$property_title.')</a>';

        $details = '';
        foreach($replacements as $key=>$value)
        {
            if(in_array($key, array('property_id', 'listing_id')) or trim($value) == '') continue;
            $details .= '<strong>'.__($key, WPL_TEXTDOMAIN).': </strong><span>'.$value.'</span><br />';
        }

        $replacements['details'] = $details;

        $notification->replacements = $notification->set_replacements($replacements);
        $notification->rendered_content = $notification->render_notification_content();
        $notification->recipients = $notification->set_recipients(array($user->data->user_email, wpl_global::get_admin_id()));

        $notification->send();

        return true;
    }
}