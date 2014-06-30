<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.users');
_wpl_import('libraries.db');

/**
** Authentication Command
** Developed 06/30/2014
**/

class wpl_io_cmd_authentication extends wpl_io_global
{
	private $password;
	private $username;
	private $method;
	private $email;
	private $settings = array();
	private $vars;
	private $success_array;
	private $failed_array;
    protected $error;
    
	public function __construct($username,$password,$vars,$settings)
	{
		$this->failed_array = array('status'=>'2');
		$this->success_array = array('status'=>'1');
        
        /** smart set of settings **/
		foreach($settings as $setting=>$setval) if($setval != '') $this->settings[$setting] = $setval;
        
		$this->vars = $vars;
		$this->method = $vars['setting_type'];
		$this->username = $username;
		$this->password = $password;
		
		if(trim($this->method) == '')
		{
			$this->error = "ERROR: invalid method!";
			return false;
		}
        
		if((trim($this->username) == '') || (trim($this->password) == ''))
		{
			$this->error = "ERROR: Empty username or password!";
			return false;
		}
	}
	
	public function build()
	{
		if($this->method == 'login') return $this->login();
		elseif($this->method == 'register') return $this->register();
		elseif($this->method == 'forget_password') return $this->forget_password();
		elseif($this->method == 'log_out') return $this->log_out();
		else
		{
			$this->error = "ERROR: Invalid method type!" ;
			return false;
		}
	}
    
	private function login()
	{
		if(wpl_users::check_user_login())
		{
			return $this->success_array;
		}
        
		$remember = (array_key_exists('remember', $this->vars)) ? $this->vars['remember'] : false;
		$login_data = array(
			'user_login'=>$this->username,
			'user_password'=>$this->password,
			'remember'=>$remember
		);
        
		$user_verify = wpl_users::login_user($login_data); 
		if(is_wp_error($user_verify)) 
		{
			$this->error = $user_verify->get_error_message();
			return false;
		}
		else
		{	
			$user_id = $user_verify->ID;		
			wp_set_current_user($user_id, $login_data);
			wp_set_auth_cookie($user_id, true, false);
			return $this->success_array;
		}
	}
    
	private function register()
	{
		if((!array_key_exists('email', $this->vars)) || (trim($this->vars['email']) == ''))
		{
			$this->error = "ERROR: Registering method need to email address!";
			return false;
		}
		else
		{
			$this->email = $this->vars['email'];
		}
        
		$user_data = array(
			'user_login'=>$this->username,
			'user_email'=>$this->email,
			'user_pass'=>$this->password,
			'description'=>'Registered With IO'
		);
        
		$insert_user = wpl_users::insert_user($user_data);
		if(is_wp_error($insert_user))
		{
			$this->error = $insert_user->get_error_message();
		 	return false;
		}
		else
		{
			return $this->success_array;
		}
	}
	
	private function forget_password()
	{	
		$db = wpl_db::get_DBO();
		$user_login = wpl_db::sanitize($this->username);
        
		if(trim($user_login) == '')
		{
			$this->error = "ERROR: Username cannot be empty!";
			return false;
		}
		elseif(strpos($user_login, '@'))
		{
			$user_data = wpl_users::get_user_by('email',$user_login);
			if(trim($user_data) == '') return $this->failed_array;
		}
		else
		{
			$login = trim($user_login);
			$user_data = wpl_users::get_user_by('login', $login);
		}
        
		do_action('lostpassword_post');
		if(!$user_data) return $this->failed_array;
        
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;
        
		do_action('retreive_password', $user_login); 
		do_action('retrieve_password', $user_login);
        
		$allow = apply_filters('allow_password_reset', true, $user_data->ID);
        
		if(is_wp_error($allow))
		{
			$this->error = $allow->get_error_message();
			return false;
		}
        
		$key = wpl_global::generate_password(20, false);
		do_action('retrieve_password_key', $user_login, $key);
		$hashed = wpl_global::wpl_hasher(8, $key);
        
		wpl_db::update('users', array('user_activation_key'=>$hashed), 'user_login', $user_login);
        
		$message = __('Someone requested that the password be reset for the following account:', WPL_TEXTDOMAIN)."\r\n";
		$message .= network_home_url('/')."\r\n";
		$message .= sprintf(__('Username: %s', WPL_TEXTDOMAIN), $user_login)."\r\n";
		$message .= __('If this was a mistake, just ignore this email and nothing will happen.', WPL_TEXTDOMAIN)."\r\n";
		$message .= __('To reset your password, visit the following address:', WPL_TEXTDOMAIN)."\r\n";
		$message .= '<'.network_site_url("wp-login.php?action=rp&key=$key&login=".rawurlencode($user_login), 'login').">\r\n";
        
		if(is_multisite())
		{
			$blogname = $GLOBALS['current_site']->site_name;
		}
		else
		{
			$blogname = wp_specialchars_decode(get_option('blogname'),ENT_QUOTES);
		}
        
		$title = sprintf(__('[%s] Password Reset', WPL_TEXTDOMAIN), $blogname);
		$title = apply_filters('retrieve_password_title', $title);
		$message = apply_filters('retrieve_password_message', $message, $key);
        
		if(($message) && (!wp_mail($user_email, $title, $message)))
		{
			$this->error = "ERROR: Email functions disabled in your host provider!";
			return false;
		}
        
		return $this->success_array;
	}
}