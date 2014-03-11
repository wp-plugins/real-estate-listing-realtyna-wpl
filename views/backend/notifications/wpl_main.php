<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.notifications.notification_manager');
class wpl_notifications_controller extends wpl_controller
{
    var $tpl_path = 'views.backend.notifications.tmpl';
    var $tpl;

    public function home()
    {
        /** check permission * */
        wpl_global::min_access('administrator');
        $this->tpl = wpl_request::getVar('tpl', 'default');

        if($this->tpl == 'modify' && wpl_request::getVar('id', 0))
        {
            $notification_id = wpl_request::getVar('id', 0);
            $this->notification = wpl_notification_manager::get_notifications("AND `id`='".wpl_db::escape($notification_id)."'", 'loadObject');
			
            $include_user = explode(',', $this->notification->include_user);
            $this->emails = array();
			
            $users = wpl_users::get_wpl_users();
            foreach($users as $user)
            {
                if(!in_array($user->user_email, $include_user)) $this->emails[] = $user->user_email;
            }
			
            $this->template = wpl_notification_manager::get_template($this->notification->template);
            $cachePath = _wpl_import(wpl_notification_manager::get_template_path() . 'cache', true, true);
            $cachePath = substr($cachePath, 0, strlen($cachePath) - 4);
			
            foreach(explode(',', $this->notification->params) as $param)
            {
                $path = $cachePath . DS . $param . '.'.wpl_notification_manager::PARAMETER_IMAGE_TYPE;
				
                //check for cached parameters image
                if(!wpl_file::exists($path))
                {
                    //create image for parameters
                    wpl_images::create_text_image(ucfirst($param), 20, wpl_notification_manager::PARAMETER_IMAGE_TYPE, $path);
                }
				
                $this->template = str_replace('##' . $param . '##', '<img title="##' . $param . '##" src="' . wpl_notification_manager::get_param_image_url($param) . '" />', $this->template);
            }
			
            $this->template = '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'.$this->template;
        }
        else
        {
            $this->notifications = wpl_notification_manager::get_notifications();
        }
        
        parent::display($this->tpl_path, $this->tpl);
    }
    
    protected function generate_basic_option()
    {
        parent::display($this->tpl_path, 'internal_basic');
    }
    protected function generate_advanced_option()
    {
        parent::display($this->tpl_path, 'internal_advanced');
    }
}
