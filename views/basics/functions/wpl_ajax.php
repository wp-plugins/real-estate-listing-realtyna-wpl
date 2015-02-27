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
}