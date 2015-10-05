<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.images');

/**
 * Notifications Library
 * @author Howard <howard@realtyna.com>
 * @since WPL1.7.0
 * @date 20/04/2014
 * @package WPL
 */
class wpl_notifications
{
    /**
     * Available modes for sending notifications
     * @var array
     */
    public $valid_modes = array('email', 'sms');
    
    /**
     * 
     * @var object
     */
    public $handler = NULL;
    
    /**
     *
     * @var int
     */
    public $notification_id = NULL;
    
    /**
     *
     * @var atring
     */
    public $template_path = NULL;
    
    /**
     *
     * @var atring
     */
    public $template_content = NULL;
    
    /**
     *
     * @var array
     */
    public $recipients = NULL;
    
    /**
     *
     * @var array
     */
    public $replacements = NULL;
    
    /**
     *
     * @var string
     */
    public $rendered_content = NULL;
    
    /**
     * settings up notification with desired mode (e.g. email or sms)
     * @author Howard R <howard@realtyna.com>
     * @param string $mode (email or sms)
     */
    public function __construct($mode = 'email')
    {
        $mode = strtolower($mode);
        if(!in_array($mode, $this->valid_modes)) $mode = 'email';
        
        if($mode == 'email')
        {
            $this->handler = $this->get_mailer();
        }
        
        #TODO
        if($mode == 'sms')
        {
            exit('WPL SMS feature is under developing!');
        }
    }
    
    /**
     * Returns email instance
     * @author Howard R <howard@realtyna.com>
     * @static
     * @return Object
     */
    public function get_mailer()
    {
        $mailer = new stdClass();
        $mailer->ContentType = 'text/html';
        $mailer->Charset = 'UTF-8';

        $sender = self::get_sender();
        $mailer->Sender = $sender;

        return $mailer;
    }
    
    /**
     * Get notification sender
     * @author Howard R <howard@realtyna.com>
     * @static
     * @return array|string
     */
    public static function get_sender()
    {
        $wpl_sender_email = wpl_global::get_setting('wpl_sender_email');
        $wpl_sender_name = wpl_global::get_setting('wpl_sender_name');
        
        if(trim($wpl_sender_email) == '')
        {
            $domain = wpl_global::domain(wpl_global::get_full_url());
            $wpl_sender_email = 'info@'.$domain;
        }
        
        if(trim($wpl_sender_name) == '') return $wpl_sender_email;
        else return array($wpl_sender_name, $wpl_sender_email);
    }
    
    /**
     * Prepare for notification
     * @author Howard R <howard@realtyna.com>
     * @param int $notification_id
     * @param array $replacements
     * @param array $recipients
     */
    public function prepare($notification_id, $replacements = NULL, $recipients = NULL)
    {
        $this->notification_id = $notification_id;
        $this->notification_data = $this->get_notification($notification_id);
        $this->template_path = $this->get_template_path($this->notification_data['template']);
        $this->template_content = $this->get_template_content($this->template_path, false);
        
        if($replacements) $this->replacements = $this->set_replacements($replacements);
        if($recipients) $this->recipients = $this->set_recipients($recipients);
        
        $this->rendered_content = $this->render_notification_content();
    }
    
    /**
     * Sends notification
     * @author Howard R <howard@realtyna.com>
     */
    public function send()
    {
        $mail_subject = $this->notification_data['subject'];
        $mail_message = $this->rendered_content;
        $mail_headers = $this->get_mail_headers();
        
        foreach($this->recipients as $recipient)
        {
            if(is_array($recipient))
            {
                $mail_to = $recipient[1];
                $user_id = $recipient[0];
            }
            else
            {
                $mail_to = $recipient;
                $user_id = wpl_users::get_id_by_email($mail_to);
            }
			
            // Check receive notification access level
            if($user_id > 0 and !wpl_users::check_access('receive_notifications', 0, $user_id)) continue;
            
            $this->wp_mail($mail_to, $mail_subject, $mail_message, $mail_headers);
        }
    }
    
    /**
     * Get notification data
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param int $id
     * @return boolean
     */
    public static function get_notification($id)
    {
        /** first validation **/
        if(!$id) return false;
        
        $query = "SELECT * FROM `#__wpl_notifications` WHERE `id`='$id'";
        return wpl_db::select($query, 'loadAssoc');
    }
    
    /**
     * Returns notification template path
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $path
     * @return string
     */
    public static function get_template_path($path)
    {
        /** first validation **/
        if(!trim($path)) return false;
        
        $path = str_replace('/', DS, $path);
        $tpl = wpl_global::get_wpl_root_path().'libraries'.DS.'notifications'.DS.'templates'.DS.$path.'.html';
        
        // Make WPL notification templates multisite support
        $current_blog_id = wpl_global::get_current_blog_id();
        if($current_blog_id and $current_blog_id != 1)
        {
            $blog_tpl = wpl_global::get_wpl_root_path().'libraries'.DS.'notifications'.DS.'templates'.$current_blog_id.DS.$path.'.html';
            wpl_file::copy($tpl, $blog_tpl);
            
            $tpl = $blog_tpl;
        }
        
        return $tpl;
    }
    
    /**
     * Returns notification template content
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $path
     * @param boolean $images_convert
     * @return boolean|string
     */
    public static function get_template_content($path, $images_convert = true)
    {
        /** first validation **/
        if(!trim($path)) return false;
        
        $content = wpl_file::read($path);
        if(!$images_convert) return $content;
        
        preg_match_all('/##([^#]*)##/', $content, $matches);
        
        foreach($matches[1] as $var_name)
        {
            $image_url = self::get_images_url($var_name);
            $content = str_replace('##'.$var_name.'##', '<img src="'.$image_url.'" data-wpl-var="'.$var_name.'" />', $content);
        }
        
        return $content;
    }
    
    /**
     * Returns image URL
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $image
     * @return string|boolean
     */
    public static function get_images_url($image = '')
    {
        /** first validation **/
        if(trim($image) == '') return false;
        
        $path = wpl_global::get_wpl_root_path().'libraries'.DS.'notifications'.DS.'templates'.DS.'cache'.DS.$image.'.png';
        $url = wpl_global::get_wpl_url().'libraries/notifications/templates/cache/'.$image.'.png';
        
        // Make WPL notification templates multisite support
        $current_blog_id = wpl_global::get_current_blog_id();
        if($current_blog_id and $current_blog_id != 1)
        {
            $path = wpl_global::get_wpl_root_path().'libraries'.DS.'notifications'.DS.'templates'.$current_blog_id.DS.'cache'.DS.$image.'.png';
            $url = wpl_global::get_wpl_url().'libraries/notifications/templates'.$current_blog_id.'/cache/'.$image.'.png';
            
            // If the destination directory doesn't exist we need to create it
            if(!wpl_file::exists(dirname($path)))
            {
                wpl_folder::create(dirname($path));
            }
        }
        
        if(!wpl_file::exists($path)) wpl_images::text_to_image($image, '000000', $path);
        
        return $url;
    }
    
    /**
     * Sets replacements
     * @author Howard R <howard@realtyna.com>
     * @param array $replacements
     * @return array
     */
    public function set_replacements($replacements)
    {
        $this->recipients = $replacements;
        return $replacements;
    }
    
    /**
     * Sets recipients
     * @author Howard R <howard@realtyna.com>
     * @param array $recipients
     * @return array
     */
    public function set_recipients($recipients)
    {
        if(!is_array($recipients)) $recipients = array($recipients);
        
        $ex = trim($this->notification_data['additional_memberships']) != '' ? explode(',', $this->notification_data['additional_memberships']) : array();
        if(is_array($ex) and count($ex) >= 1) foreach($ex as $value) array_push($recipients, $value);
        
        $ex = trim($this->notification_data['additional_users']) != '' ? explode(',', $this->notification_data['additional_users']) : array();
        if(is_array($ex) and count($ex) >= 1) foreach($ex as $value) array_push($recipients, $value);
        
        $ex = trim($this->notification_data['additional_emails']) != '' ? explode(',', $this->notification_data['additional_emails']) : array();
        if(is_array($ex) and count($ex) >= 1) foreach($ex as $value) array_push($recipients, $value);
        
        $emails = array();
        foreach($recipients as $recipient)
        {
            /** user **/
            if(is_numeric($recipient) and $recipient >= 0)
            {
                $user_data = wpl_users::get_user($recipient);
                array_push($emails, array($user_data->ID, $user_data->user_email));
            }
            /** group **/
            elseif(is_numeric($recipient) and $recipient < 0)
            {
                $users = wpl_users::get_wpl_users("AND `membership_id`='$recipient'");
                
                foreach($users as $user)
                {
                    array_push($emails, array($user->ID, $user->user_email));
                }
            }
            /** email **/
            elseif(is_string($recipient))
            {
                $user_id = wpl_users::get_id_by_email($recipient);
                if(!$user_id) $user_id = 0;
                
                array_push($emails, array($user_id, $recipient));
            }
        }
        
        return $emails;
    }
    
    /**
     * Renders notification content
     * @author Howard R <howard@realtyna.com>
     * @return string
     */
    public function render_notification_content()
    {
        $content = $this->template_content;
        
        if(is_array($this->replacements)) foreach($this->replacements as $key=>$value) $content = str_replace('##'.$key.'##', $value, $content);
        return $content;
    }
    
    /**
     * Returns notifications
     * @author Howard R <howard@realtyna.com>
     * @static
     * @param string $where
     * @param string $result
     * @return mixed
     */
    public static function get_notifications($where = '', $result = 'loadObjectList')
    {
        $query = "SELECT * FROM `#__wpl_notifications` WHERE 1 " . $where;
        return wpl_db::select($query, $result);
    }
    
    /**
     * update notification
     * @author Kevin J <kevin@realtyna.com> 
     * @static
     * @param integer $id ID of Notification to Update
     * @param string $key field Key must to change
     * @param string $value new Value to set this
     * @return boolean
     */
    public static function set($id, $key, $value = '')
    {
        /** first validation **/
        if(trim($id) == '' or trim($key) == '') return false;
        
        return wpl_db::set('wpl_notifications', $id, $key, $value);
    }
    
    /**
     * save notification data
     * @author Kevin J <kevin@realtyna.com> 
     * @static
     * @param array $data notification data to save reperesantion in arrray
     * @return boolean
     */
    public static function save_notification($data)
    {
        wpl_file::write(self::get_template_path($data['template_path'], true), $data['template']);

        $data = wpl_db::escape($data);
        $query = "UPDATE #__wpl_notifications SET `description` = '{$data['description']}',`template` = '{$data['template_path']}',";
        $query .= "`additional_emails` = '{$data['include_email']}',`additional_memberships` = '{$data['include_membership']}',`additional_users` = '{$data['include_user']}',`subject` = '{$data['subject']}' ";
        $query .= "WHERE id = {$data['id']}";
        return wpl_db::q($query);
    }
    
    /**
     * extract parameter from html with marked by ##test##
     * @author Kevin J <kevin@realtyna.com> 
     * @static
     * @param string $template
     * @return array $data[0] containt name of parameter with out ## and $data[1] with ##
     */
    public static function extract_params($template)
    {
        $matches = NULL;
        preg_match_all('/##([^#]*)##/', $template, $matches);
        return $matches;
    }

    /**
     * Generate mail headers string
     * @author Peter P <peter@realtyna.com>
     * @return string
     */
    public function get_mail_headers()
    {
        $headers = '';

        if(is_string($this->handler->Sender))
        {
            $headers .= 'From: '.$this->handler->Sender."\n";
        }
        elseif(is_array($this->handler->Sender)) 
        {
            $headers .= 'From: '.$this->handler->Sender[0].' <'.$this->handler->Sender[1] ."> \n";
        }

        $headers .= 'Content-Type: '.$this->handler->ContentType.'; charset='.$this->handler->Charset."\n";
		
        return $headers;
    }
    
    /**
     * Wrapper for wp_mail function
     * @author Howard <howard@realtyna.com>
     * @param string $mail_to
     * @param string $mail_subject
     * @param string $mail_message
     * @param mixed $mail_headers
     * @return mixed
     */
    public function wp_mail($mail_to, $mail_subject, $mail_message, $mail_headers)
    {
        return wp_mail($mail_to, wp_specialchars_decode($mail_subject), $mail_message, $mail_headers);
    }
}