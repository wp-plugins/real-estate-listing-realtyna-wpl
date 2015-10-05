<?php defined('_WPLEXEC') or die('Restricted access'); // no direct access

/**
 * WPL Email Parser class
 * @author Steve A. <steve@realtyna.com>
 * @since WPL2.8.0
 * @package WPL 
 */
class wpl_email_parser
{
    /**
     * Retry times
     */
	const RETRY_TIMES = 1; 

	/**
	 * This array is resultant array which further contains an associative array as an element.
	 * And the associative array contain attachments, html_body, plain_body as its keys.
	 * @var array
	 */
	private $result = array();

	/**
	 * This associative array contains attached file name as a key and corresponding binary file as a value.
	 * @var array
	 */
	private $attachments = array();

	/**
	 * This contains standard object of header information
	 * @var object
	 */
	private $headers;

	/**
	 * This variable conatains the html part of a message.
	 * @var string
	 */
	private $html_body = '';

	/**
	 * This variable conatains the plain text part of a message.
	 * @var string
	 */
	private $plain_body = "";

	/**
	 * It consists a mail server name
	 * @var string
	 */
	private $host = '';
    
	/**
	 * User name for the mail account
	 * @var string
	 */
	private $username = '';

	/**
	 * Password for the mail account
	 * @var string
	 */
	private $password = '';
    
    /**
	 * Connection link object for the mail account
	 * @var string
	 */
	private $link = '';
    
    /**
     *  Whether fetch type is POP3 or not
     *  @var boolean
     */
    private $is_pop3 = false;
    
    /**
     * Errors Array
     * @var array
     */
    private $error = array();

    /**
     * Status
     * @var string
     */
    private $status;

    /**
     * Fetch Type
     * @var string
     */
    private $fetch_type;
	
	/**
	 * Constructor method
	 * @author Steve A. <steve@realtyna.com>
	 * @param string  $host        Host address
	 * @param string  $username    Username
	 * @param string  $password    Password
	 * @param string  $fetch_type  Fetch Type
	 */
	public function __construct($host, $port, $username, $password, $fetch_type = '')
    {
    	switch($fetch_type)
        {
            case "FETCH_POP3":
                $this->fetch_type = 'pop3/notls';
                $this->is_pop3 = true;
            break;

            case "FETCH_POP3SSL":
                $this->fetch_type = 'pop3/ssl/novalidate-cert/notls';
                $this->is_pop3 =   true;
            break;
        
            case "FETCH_POP3TLS":
                $this->fetch_type = 'pop3/ssl/novalidate-cert';
                $this->is_pop3 = true;
            break;

            case "FETCH_IMAPSSL":
                $this->fetch_type = 'imap/ssl/novalidate-cert/notls';
            break;

            case "FETCH_IMAPTLS":
                $this->fetch_type = 'imap/ssl/novalidate-cert';
            break;

            case "FETCH_IMAP":
            default: 
                $this->fetch_type = 'imap/notls';
                $this->is_pop3 = true;
            break;
        }

        $this->host = '{' . $host . ':' . $port . '/' . $this->fetch_type . '}INBOX';
        $this->username = $username;
        $this->password = $password;
	}

	/**
     * Get list of fetch type options
     * @author Steve A. <steve@realtyna.com>
     * @return array 		Fetch Type Options
     */
    public function get_fetch_type_options()
    {
        return array(
                        "FETCH_IMAP"    =>  "IMAP",
                        "FETCH_IMAPSSL" =>  "IMAP/SSL",
                        "FETCH_IMAPTLS" =>  "IMAP/TLS",
                        "FETCH_POP3"    =>  "POP3",
                        "FETCH_POP3SSL" =>  "POP3/SSL",
                        "FETCH_POP3TLS" =>  "POP3/TLS"
                    );
    }

	/**
	 * This is the funtion for setting a connection to the given mail server.
	 * @author Steve A. <steve@realtyna.com>
	 * @return object 		IMAP stream connection
	 */
	public function set_connection()
    {
        if (!extension_loaded('imap')) 
        {
			echo 'Error: Your PHP was not compiled with IMAP support.' . "\n" .
				 '*nix users should recompile their PHP with the \'--with-imap\' flag;' . "\n" .
                 'Windows users can simply uncomment the extension=\'php_imap.dll\' line in their php.ini';

			return false;
		}
        
        // If connection is not exist then make a connection
		if(!$this->link) 
        {
        	// Try to connect again
			for($i = 0; $i < self::RETRY_TIMES; $i++) 
            {
				$this->link = imap_open($this->host, $this->username, $this->password);

                if($this->link) 
                {
                    $this->status = 'Connected';
                    return $this->link;
                } 
                else 
                {
                    $this->error[] = imap_last_error();
                    $this->status = 'Not connected';
                }
            }
            
            return false;
		}
        
		return $this->link;
	}

    /**
     * Method for checking the connection status
     * @author Steve A. <steve@realtyna.com>
     * @return boolean 		Connection status
     */
    public function check_connection()
    {
        if($this->set_connection())
            return true;
        
        return false;
        
    }

	/**
	 * Get unread messages
	 * @author Steve A. <steve@realtyna.com>
	 * @return null
	 */
    public function get_unread_messages() 
    {
        $emails     =   imap_search($this->link, 'UNSEEN', SE_UID);
        $count_msg  =   count($emails);
        if($count_msg > 0 && is_array($emails))
        {
            // Attempt to set time limit
            @set_time_limit(0);
        
            $emails = array_reverse($emails);
            
            // Iteration on mailbox messages
            foreach($emails as $key => $messageID)
            {
                $message_uid    =   $messageID;
                $message_number =   imap_msgno($this->link, $message_uid);

                // Get Headers
                $this->headers  =   @imap_headerinfo($this->link, $message_number, 0);
                
                // Get Message Paths
                $this->parse_message($message_uid);
            }
        }
        
        imap_expunge($this->link);
		imap_close($this->link);
	}
    
    
    /**
	 * This is the funtion for parsing a message
	 * @author Steve A. <steve@realtyna.com>	
	 * @param integer 	$msg_uid 	Message ID
	 */
	public function parse_message($msg_uid) 
    {
		$this->get_message($msg_uid);
		$this->make_result();
        
        // If pop3 is used then delete email from inbox
        if ($this->is_pop3 == '1')
        {
            imap_delete($this->link, $msg_uid, SE_UID);
        }
        else
        {
            imap_setflag_full($this->link, $msg_uid, '\\Seen', SE_UID);
        }
	}

    /**
	 * This is the funtion for getting a message
	 * @author Steve A. <steve@realtyna.com>	
	 * @param integer 	$msg_uid 	Message ID
	 */
	public function get_message($msg_uid) 
    {
		$structure = imap_fetchstructure($this->link, $msg_uid, FT_UID);

		// If message is not multipart
		if (!$structure->parts)
        {
			$this->get_message_part($msg_uid, $structure, 0);
		}
		else 
        {
			foreach ($structure->parts as $partno => $part) 
            {
				$this->get_message_part($msg_uid, $part, $partno+1);
			}
        }
        
	}
    
    /**
	 * This is the funtion for parsing messge parts
	 * @author Steve A. <steve@realtyna.com>	
	 * @param integer 	$msg_uid 	Message ID
	 * @param object 	$part_obj 	Object Part
	 * @param integer 	$partno 		Part Number
	 */
	public function get_message_part($msg_uid, $part_obj, $partno) 
    {   
        // If partno is 0 then fetch body as a single part message
        if($partno)
        {
            $data   =   imap_fetchbody($this->link, $msg_uid, $partno, FT_UID);
        }
        else
        {
            $data   =   imap_body($this->link, $msg_uid, FT_UID);
        }
        
		// Any part may be encoded, even plain text messages, so decoding it
		if ($part_obj->encoding == 4) 
        {
			$data = quoted_printable_decode($data);
		}
		elseif ($part_obj->encoding == 3) 
        {
			$data = base64_decode($data);
		}

		// Collection all parameters, like name, filenames of attachments, etc.
		$params = array();
		
        if ($part_obj->parameters) 
        {
			foreach ((array) $part_obj->parameters as $x) 
            {
				$params[strtolower($x->attribute)] = $x->value;
			}
		}

        if ($part_obj->dparameters)
        {
			foreach ((array) $part_obj->dparameters as $x) 
            {
				$params[strtolower($x->attribute)] = $x->value;
			}
		}

		// Any part with a filename is an attachment
		if ($params['filename'] || $params['name']) 
        {
			// Filename may be given as 'Filename' or 'Name' or both
			$filename = ($params['filename'])? $params['filename'] : $params['name'];
			$this->attachments[$filename] = $data;
		}
        else
        {
            // Processing plain text message
            if ($part_obj->type == 0 && $data)
            {
                // Messages may be split in different parts because of inline attachments,
                // so append parts together with blank row.
                if (strtolower($part_obj->subtype) == 'plain') 
                {
                    $this->plain_body .= trim($data);
                }
                else 
                {
                    $this->html_body .= $data;
                }
            }
            
            // Some times it happens that one message embeded in another.
            // This is used to appends the raw source to the main message.
            elseif ($part_obj->type == 2 && $data) 
            {
                $this->plain_body .= $data;
            }
        }
		

		// Here is recursive call for subpart of the message
		if ($part_obj->parts) 
        {
			foreach ((array) $part_obj->parts as $partno2 => $part2) 
            {
				$this->get_message_part($msg_uid, $part2, $partno.'.'.($partno2+1));
			}
		}
	}

	/**
     * Method for processing email queue
     * @author Steve A. <steve@realtyna.com>
     * @return mixed 		Result
     */
    public function process_email_queue()
    {
        if($this->link)
        {
            $this->get_unread_messages();
            return $this->get_result();
        }
        
        return false;
    }
	
    /**
	 * Used for preparing resultant array
	 * @author Steve A. <steve@realtyna.com>
     * @return null
	 */
	private function make_result() 
    {
		$temp = array();
		$temp['attachments']  = $this->attachments;
		$temp['html_body']	  = $this->html_body;
		$temp['plain_body']   = $this->plain_body;
		$temp['headers']	  = $this->headers;

		$this->result[] = $temp;

		// Unsetting the variables for next message
		unset($this->attachments);
		unset($this->html_body);
		unset($this->plain_body);
		unset($this->headers);
	}

    /**
     * This method returns the resultant array.
	 * @author Steve A. <steve@realtyna.com>	
     * @return array 		Result
     */
	public function get_result() 
    {
		return $this->result;
	}
    
    /**
     * Number of Total Emails
     * @author Steve A. <steve@realtyna.com>	
     * @return integer 		Number of emails
     */
    public function num_message(){
        return imap_num_msg($this->link);
    }

    /**
     * Number of Recent Emails
     * @author Steve A. <steve@realtyna.com>	
     * @return integer 		Number of recent emails
     */
    public function num_recent(){
        return imap_num_recent($this->link);
    }


    /**
     * Get mailbox info
     * @author Steve A. <steve@realtyna.com>	
     * @return array 		Mailbox Info
     */
    public function get_mailbox_info()
    {
        //$mailbox = imap_mailboxmsginfo($this->link); #It's wery slow
        $mailbox = imap_check($this->link);
  
        if ($mailbox) 
        {
            $mbox["Date"]    = $mailbox->Date;
            $mbox["Driver"]  = $mailbox->Driver;
            $mbox["Mailbox"] = $mailbox->Mailbox;
            $mbox["Messages"]= $this->num_message();
            $mbox["Recent"]  = $this->num_recent();
            $mbox["Unread"]  = $mailbox->Unread;
            $mbox["Deleted"] = $mailbox->Deleted;
            $mbox["Size"]    = $mailbox->Size;
        } 
        else 
        {
            $this->error[] = imap_last_error();
        }
    
        return $mbox;
    }
    
    /**
     * Set flags of email
	 * @author Steve A. <steve@realtyna.com>	
     * @return object 		Result
     */ 
    public function email_setflag()
    {
        return imap_setflag_full($this->link, "2,5","\\Seen \\Flagged"); 
    }
}