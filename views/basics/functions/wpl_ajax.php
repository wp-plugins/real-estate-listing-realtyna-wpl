<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import('libraries.flex');
_wpl_import('libraries.property');
_wpl_import('libraries.images');
		
class wpl_functions_controller extends wpl_controller
{
	public $tpl_path = 'views.basics.functions.tmpl';
	public $tpl;
	
	public function display()
	{
		$function = wpl_request::getVar('wpl_function');
		
		if($function == 'infowindow') $this->infowindow();
		elseif($function == 'shortcode_wizard') $this->shortcode_wizard();
        elseif($function == 'report_abuse_form') $this->report_abuse_form();
        elseif($function == 'report_abuse_submit') $this->report_abuse_submit();
        elseif($function == 'send_to_friend_form') $this->send_to_friend_form();
        elseif($function == 'send_to_friend_submit') $this->send_to_friend_submit();
        elseif($function == 'request_a_visit_form') $this->request_a_visit_form();
        elseif($function == 'request_a_visit_submit') $this->request_a_visit_submit();
	}
	
	private function infowindow()
	{
        $wpl_property = new wpl_property();
		$listing_fields = $wpl_property->get_plisting_fields();
		$select = $wpl_property->generate_select($listing_fields, 'p');
		$property_ids = wpl_request::getVar('property_ids', '');
		
		$query = "SELECT ".$select." FROM `#__wpl_properties` AS p WHERE 1 AND p.`deleted`='0' AND p.`finalized`='1' AND p.`confirmed`='1' AND p.`expired`='0' AND p.`id` IN (".$property_ids.")";
		$properties = $wpl_property->search($query);
		
		/** plisting fields **/
		$plisting_fields = $wpl_property->get_plisting_fields();
		
		$this->wpl_properties = array();
		foreach($properties as $property)
		{
			$this->wpl_properties[$property->id] = $wpl_property->full_render($property->id, $plisting_fields, $property);
		}
        
        /** apply filters (This filter must place after all proccess) **/
		_wpl_import('libraries.filters');
		@extract(wpl_filters::apply('property_listing_after_render', array('wpl_properties'=>$this->wpl_properties)));
		
		parent::render($this->tpl_path, 'infowindow');
		exit;
	}
	
	private function shortcode_wizard()
	{
		_wpl_import('libraries.sort_options');
		
		/** global settings **/
		$this->settings = wpl_global::get_settings();
		
		parent::render($this->tpl_path, 'shortcode_wizard');
	}
    
    private function report_abuse_form()
	{
		$this->property_id = wpl_request::getVar('pid', 0);
        $this->form_id = wpl_request::getVar('form_id', 0);
		
		if(!$this->form_id) $HTML = parent::render($this->tpl_path, 'report_abuse_form', false, true);
        else
        {
            /**
             * @todo Generate form via Form Builder addon
             */
        }
        
        echo $HTML;
        exit;
	}
    
    private function report_abuse_submit()
	{
        $parameters = wpl_request::getVar('wplfdata', array());
		$property_id = isset($parameters['property_id']) ? $parameters['property_id'] : 0;
        
        $returnData = array();
        if(!$property_id)
        {
            $returnData['success'] = 0;
            $returnData['message'] = __('Invalid Property!', WPL_TEXTDOMAIN);
        }
        elseif(isset($parameters['email']) and !filter_var($parameters['email'], FILTER_VALIDATE_EMAIL))
        {
            $returnData['success'] = 0;
            $returnData['message'] = __('Your email is not valid!', WPL_TEXTDOMAIN);
        }
        else
        {
            $PRO = new wpl_addon_pro();
            if($PRO->report_abuse_send($parameters))
            {
                $returnData['success'] = 1;
                $returnData['message'] = __('Abuse report sent successfully.', WPL_TEXTDOMAIN);
            }
            else
            {
                $returnData['success'] = 0;
                $returnData['message'] = __('Error sending!', WPL_TEXTDOMAIN);
            }
        }
        
        echo json_encode($returnData);
        exit;
	}

    private function send_to_friend_form()
    {
        $this->property_id = wpl_request::getVar('pid', 0);
        $this->form_id = wpl_request::getVar('form_id', 0);

        if(!$this->form_id) $HTML = parent::render($this->tpl_path, 'send_to_friend_form', false, true);
        else
        {
            /**
             * @todo Generate form via Form Builder addon
             */
        }

        echo $HTML;
        exit;
    }

    private function send_to_friend_submit()
    {
        $parameters = wpl_request::getVar('wplfdata', array());
        $property_id = isset($parameters['property_id']) ? $parameters['property_id'] : 0;

        $returnData = array();
        if(!$property_id)
        {
            $returnData['success'] = 0;
            $returnData['message'] = __('Invalid Property!', WPL_TEXTDOMAIN);
        }
        elseif(isset($parameters['your_email']) == false || !filter_var($parameters['your_email'], FILTER_VALIDATE_EMAIL))
        {
            $returnData['success'] = 0;
            $returnData['message'] = __('Your email is not valid!', WPL_TEXTDOMAIN);
        }
        elseif(isset($parameters['friends_email']) == false || !filter_var($parameters['friends_email'], FILTER_VALIDATE_EMAIL))
        {
            $returnData['success'] = 0;
            $returnData['message'] = __('Friends email is not valid!', WPL_TEXTDOMAIN);
        }
        elseif(isset($parameters['email_subject']) == false || $parameters['email_subject'] == '')
        {
            $returnData['success'] = 0;
            $returnData['message'] = __('Email subject  is not valid!', WPL_TEXTDOMAIN);
        }
        elseif(isset($parameters['your_name']) == false || $parameters['your_name'] == '')
        {
            $returnData['success'] = 0;
            $returnData['message'] = __('Your name is not valid!', WPL_TEXTDOMAIN);
        }
        else
        {
            if(wpl_global::send_to_friend($parameters))
            {
                $returnData['success'] = 1;
                $returnData['message'] = __('Send to friend message sent successfully.', WPL_TEXTDOMAIN);
            }
            else
            {
                $returnData['success'] = 0;
                $returnData['message'] = __('Error sending!', WPL_TEXTDOMAIN);
            }
        }

        echo json_encode($returnData);
        exit;
    }

    private function request_a_visit_form()
    {
        $this->property_id = wpl_request::getVar('pid', 0);
        $this->form_id = wpl_request::getVar('form_id', 0);

        if(!$this->form_id) $HTML = parent::render($this->tpl_path, 'request_a_visit_form', false, true);
        else
        {
            /**
             * @todo Generate form via Form Builder addon
             */
        }

        echo $HTML;
        exit;
    }

    private function request_a_visit_submit()
    {
        $parameters = wpl_request::getVar('wplfdata', array());
        $property_id = isset($parameters['property_id']) ? $parameters['property_id'] : 0;

        $returnData = array();
        if(!$property_id)
        {
            $returnData['success'] = 0;
            $returnData['message'] = __('Invalid Property!', WPL_TEXTDOMAIN);
        }
        elseif(isset($parameters['email']) == false || !filter_var($parameters['email'], FILTER_VALIDATE_EMAIL))
        {
            $returnData['success'] = 0;
            $returnData['message'] = __('Your email is not valid!', WPL_TEXTDOMAIN);
        }
        elseif(isset($parameters['name']) == false || $parameters['name'] == '')
        {
            $returnData['success'] = 0;
            $returnData['message'] = __('Your name is not valid!', WPL_TEXTDOMAIN);
        }
        elseif(isset($parameters['tel']) == false || $parameters['tel'] == '')
        {
            $returnData['success'] = 0;
            $returnData['message'] = __('Contact phone number is not valid!', WPL_TEXTDOMAIN);
        }
        else
        {
            if(wpl_global::request_a_visit_send($parameters))
            {
                $returnData['success'] = 1;
                $returnData['message'] = __('Request a visit sent successfully.', WPL_TEXTDOMAIN);
            }
            else
            {
                $returnData['success'] = 0;
                $returnData['message'] = __('Error sending!', WPL_TEXTDOMAIN);
            }
        }

        echo json_encode($returnData);
        exit;
    }
}