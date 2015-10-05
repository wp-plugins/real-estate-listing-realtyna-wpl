<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
 * contact agent command
 * @author Chris <chris@realtyna.com>
 * @since WPL2.7.0
 * @package WPL
 * @date 2015/08/31
 */
class wpl_io_cmd_contact_agent extends wpl_io_cmd_base
{
    protected $built = array();

    /**
     * This method is the main method of each commands
     * @author Chris <chris@realtyna.com>
     * @return mixed
     */
    public function build()
    {
        $first_name      = $this->params['first_name'];
        $last_name       = $this->params['last_name'];
        $email           = $this->params['email'];
        $phone           = $this->params['phone'];
        $get_preapproved = $this->params['get_preapproved'];
        $message         = $this->params['message'];
        $id              = $this->params['id'];
        $user_id         = $this->params['user_id'];

        $parameters = array(
            'first_name'=>$first_name,
            'last_name'=>$last_name,
            'phone'=>$phone,
            'email'=>$email,
            'message'=>$message,
            'get_preapproved'=>$get_preapproved,
            'id'=>$id,
            'user_id'=>$user_id
        );
        
        wpl_events::trigger('contact_profile', $parameters);
        return $this->built['contact_agent'] = array('status'=>'success');
    }

    /**
     * Data validation
     * @author Chris <chris@realtyna.com>
     * @return boolean
     */
    public function validate()
    {
        if(isset($this->params['first_name']) == false ||  $this->params['first_name'] == '')
        {
            return false;
        }

        if(isset($this->params['last_name']) == false ||  $this->params['last_name'] == '')
        {
            return false;
        }

        if(isset($this->params['phone']) == false ||  $this->params['phone'] == '')
        {
            return false;
        }

        if(isset($this->params['email']) == false ||  $this->params['email'] == '')
        {
            return false;
        }

        if(isset($this->params['message']) == false ||  $this->params['message'] == '')
        {
            return false;
        }

        if(isset($this->params['get_preapproved']) == false ||  $this->params['get_preapproved'] == '')
        {
            return false;
        }

        if(isset($this->params['id']) == false ||  $this->params['id'] == '')
        {
            return false;
        }

        if(isset($this->params['user_id']) == false ||  $this->params['user_id'] == '')
        {
            return false;
        }
        
        return true;
    }
}