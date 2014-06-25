<?php
class wpl_io_cmd_authentication extends wpl_io_global
{
	private $password;
	private $username;
	private $method;
	private $email;
	private $settings = array();
	private $vars;
	private $checker = false;
	public function __construct($username,$password,$vars,$settings)
	{
		$this->preparing_authentication($username,$password,$vars,$settings);
	}
	public function preparing_authentication($username,$password,$vars,$settings)
	{
		foreach ($settings as $setting=>$setval)
		{
			if (!empty($setval))
			{
				$this->settings[$setting] = $setval;
			}
		}
		$this->vars = $vars;
		$this->method = $vars['setting_type'];
		$this->username = $username;
		$this->password = $password;
		
		if(empty($this->method))
		{
			die("IO authentication : invalid method!");
		}
		if( (empty($this->username)) || (empty($this->password)) )
		{
			die("IO authentication : Empty username or password!");
		}
		$this->checker = true;
	}
	public function build()
	{
		if(!$this->checker)
		{
			die("IO authentication : invalid arguments!");
		}
		if($this->method == 'login')
		{
			return $this->login();
		}
		elseif($this->method == 'register')
		{
			return $this->register();
		}
		elseif($this->method == 'forget_password')
		{
			return $this->forget_password();
		}
		elseif($this->method == 'log_out')
		{
			return $this->log_out();
		}
		else
		{
			die("Invalid method type!");
		}
	}
	private function login()
	{
		if($this->is_user_logged_in())
		{
			return true;
		}
		$login_data = array();
		$remember = (array_key_exists('remember',$this->vars)) ? $this->vars['remember'] : false;
		$login_data['user_login'] = $this->username;
		$login_data['user_password'] = $this->password;
		$login_data['remember'] = $remember;
		$user_verify = wp_signon( $login_data, false ); 
		if ( is_wp_error($user_verify) ) 
		{
			return false;
		}
		else
		{	
			$user_id = $user_verify->ID;		
			wp_set_current_user( $user_id, $login_data );
			wp_set_auth_cookie( $user_id, true, false );
			return true;
		}
	}
	private function register()
	{
		if( (!array_key_exists('email',$this->vars)) || (empty($this->vars['email'])) )
		{
			die("Registering method need to email address!");
		}
		else
		{
			$this->email = $this->vars['email'];
		}
		$userdata = array(
			'user_login'=> $this->username,
			'user_email' => $this->email,
			'user_pass'=>$this->password,
			'description'=>'Registered With IO'
		);
		$user_id = wp_insert_user( $userdata ) ;
		if(is_wp_error($user_id))
		{
		 	return false;
		}
		else
		{
			return true;
		}
	}
	private function log_out()
	{
		if(wp_logout())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	private function forget_password()
	{	
		global $wpdb, $wp_hasher;
		$user_login = sanitize_text_field($this->username);
		if(empty($user_login))
		{
			return false;
		}
		elseif(strpos( $user_login,'@'))
		{
			$user_data = get_user_by('email',trim($user_login));
			if(empty($user_data))
			{
			   return false;
			}
		}
		else
		{
			$login = trim($user_login);
			$user_data = get_user_by('login', $login);
		}
	
		do_action('lostpassword_post');
		if(!$user_data)
		{
			return false;
		}
		$user_login = $user_data->user_login;
		$user_email = $user_data->user_email;
		do_action('retreive_password', $user_login); 
		do_action('retrieve_password', $user_login);
		$allow = apply_filters('allow_password_reset', true, $user_data->ID);
		if(!$allow)
		{
			return false;
		}
		elseif(is_wp_error($allow))
		{
			return false;
		}
		$key = wp_generate_password(20,false);
		do_action( 'retrieve_password_key', $user_login, $key );
		if(empty($wp_hasher))
		{
			require_once ABSPATH . 'wp-includes/class-phpass.php';
			$wp_hasher = new PasswordHash( 8, true );
		}
		$hashed = $wp_hasher->HashPassword( $key );
		$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );
		$message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
		$message .= network_home_url( '/' ) . "\r\n\r\n";
		$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
		$message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
		$message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
		$message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";
		if (is_multisite() )
		{
			$blogname = $GLOBALS['current_site']->site_name;
		}
		else
		{
			$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
		}
		$title = sprintf( __('[%s] Password Reset'), $blogname );
		$title = apply_filters('retrieve_password_title', $title);
		$message = apply_filters('retrieve_password_message', $message, $key);
		if(($message) && (!wp_mail($user_email, $title, $message)))
		{
			file_put_contents("C:\a.txt", $message);
			die("Email functions disabled in your host provider!");
		}
		return true;
	}
	public function is_user_logged_in()
	{
		if(is_user_logged_in())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}