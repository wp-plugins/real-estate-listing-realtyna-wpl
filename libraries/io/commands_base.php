<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
_wpl_import('libraries.addon_mobile_application');


/**
 * The base class of all io commands
 * @author Chris <chris@realtyna.com>
 * @since WPL2.5.0
 * @package WPL
 * @date 2015/06/02
 */
abstract class wpl_io_cmd_base extends wpl_io_global
{
    protected $error;
    protected $params;
    protected $username;
    protected $password;
    protected $user_id;
    protected $initialization = true;
    protected $authentication = true;

    /**
     * @param boolean $initialization
     */
    public function setInitialization($initialization)
    {
        $this->initialization = $initialization;
    }

    /**
     * @param boolean $authentication
     */
    public function setAuthentication($authentication)
    {
        $this->authentication = $authentication;
    }


    /**
     * This method is the main method of each commands
     * @return mixed
     */
    public abstract function build();


    /**
     * Data validation
     * @return boolean
     */
    public abstract function validate();



    /**
     * Getting the commands error
     * @author Chris <chris@realtyna.com>
     * @return string return the command errors
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Setting the commands error before finish
     * @param string $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * Initialization the commands before build
     * @param string $username
     * @param string $password
     * @param array $params
     * @return boolean
     */
    public function init($username, $password, $params)
    {
        if($this->initialization == false)
        {
            return;
        }
        $this->username = $username;
        $this->password = base64_decode($password);
        $this->params = $params;

        if($this->authentication)
        {
            if($username != '')
            {
                $authenticate = wpl_users::authenticate($username, $password);
                if($authenticate['status'] != 1)
                {
                    $this->error = "Authentication failed!";
                    return false;
                }

                $this->user_id = $authenticate['uid'];
            }
            else
            {
                $this->user_id = 0;
            }
        }

    }



}