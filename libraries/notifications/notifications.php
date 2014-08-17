<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.images');

/**
** Notifications Library
** Developed 20/04/2014
**/

class wpl_notifications
{
    public $valid_modes = array('email', 'sms');
    public $handler = NULL;
    public $notification_id = NULL;
    public $template_path = NULL;
    public $template_content = NULL;
    public $recipients = NULL;
    public $replacements = NULL;
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
    
    public static function get_mailer()
    {
        _wp_import('wp-includes.class-phpmailer');
        
        $handler = new PHPMailer();
        $handler->IsHTML();
        $handler->CharSet = 'UTF-8';
        
        $sender = self::get_sender();
        if(is_string($sender)) $handler->setFrom($sender);
        elseif(is_array($sender)) $handler->setFrom($sender[0], $sender[1]);
        
        return $handler;
    }
    
    public static function get_sender()
    {
        $wpl_sender_email = wpl_global::get_setting('wpl_sender_email');
        $wpl_sender_name = wpl_global::get_setting('wpl_sender_name');
        
        if(trim($wpl_sender_email) != '')
        {
            if(trim($wpl_sender_name) == '') return $wpl_sender_email;
            else return array($wpl_sender_email, $wpl_sender_name);
        }
        
        $domain = wpl_global::domain(wpl_global::get_full_url());
        return 'info@'.$domain;
    }
    
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
    
    public function send()
    {
        $this->handler->Subject = $this->notification_data['subject'];
        $this->handler->MsgHTML($this->rendered_content);
        
        foreach($this->recipients as $recipient)
        {
            if(is_array($recipient)) $email = $recipient[1];
            else $email = $recipient;
            
            $this->handler->clearAllRecipients();
            $this->handler->AddAddress($email);
            $this->handler->Send();
        }
    }
    
    public static function get_notification($id)
    {
        /** first validation **/
        if(!$id) return false;
        
        $query = "SELECT * FROM `#__wpl_notifications` WHERE `id`='$id'";
        return wpl_db::select($query, 'loadAssoc');
    }
    
    public static function get_template_path($path)
    {
        /** first validation **/
        if(!trim($path)) return false;
        
        $path = str_replace('/', DS, $path);
        return wpl_global::get_wpl_root_path().'libraries'.DS.'notifications'.DS.'templates'.DS.$path.'.html';
    }
    
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
    
    public static function get_images_url($image = '')
    {
        /** first validation **/
        if(trim($image) == '') return false;
        
        $path = wpl_global::get_wpl_root_path().'libraries'.DS.'notifications'.DS.'templates'.DS.'cache'.DS.$image.'.png';
        $url = wpl_global::get_wpl_url().'libraries/notifications/templates/cache/'.$image.'.png';
        
        if(!wpl_file::exists($path)) wpl_images::text_to_image($image, '000000', $path);
        
        return $url;
    }
    
    public function set_replacements($replacements)
    {
        $this->recipients = $replacements;
        return $replacements;
    }
    
    public function set_recipients($recipients)
    {
        if(!is_array($recipients)) $recipients = array($recipients);
        
        /** additional recipients from DB **/
        $ex = explode(',', $this->notification_data['additional_memberships']);
        if(is_array($ex) and count($ex) > 1) foreach($ex as $value) array_push($recipients, $value);
        
        $ex = explode(',', $this->notification_data['additional_users']);
        if(is_array($ex) and count($ex) > 1) foreach($ex as $value) array_push($recipients, $value);
        
        $ex = explode(',', $this->notification_data['additional_emails']);
        if(is_array($ex) and count($ex) > 1) foreach($ex as $value) array_push($recipients, $value);
        
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
    
    public function render_notification_content()
    {
        $content = $this->template_content;
        
        foreach($this->replacements as $key=>$value)
        {
            $content = str_replace('##'.$key.'##', $value, $content);
        }
        
        return $content;
    }
    
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
        $data['template'] = self::extract_reserved_images($data['template']);
        
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
     * find image title and check for parameter, after replace parameter with that image
     * @author Kevin J <kevin@realtyna.com>
     * @static
     * @param string $content
     * @return string
     */
    private static function extract_reserved_images($content)
    {
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($content);
        $tags = $dom->getElementsByTagName('img');
        $length = $tags->length;
        
        for($i = $length - 1; $i >= 0; $i--)
        {
            $tag = $tags->item($i);
            $title = $tag->getAttribute('title');
            if(preg_match('/##\w+?##/', $title))
            {
                $textNode = $dom->createTextNode($title);
                $tag->parentNode->replaceChild($textNode, $tag);
            }
        }
        
        return preg_replace('~<(?:!DOCTYPE|/?(?:html|head|body|meta|title))[^>]*>\s*~i', '', $dom->saveHTML());
    }
	
}