<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_data_structure_controller extends wpl_controller
{
	public $tpl_path = 'views.backend.data_structure.tmpl';
	public $tpl;
	
	public function display()
	{
		/** check permission **/
		wpl_global::min_access('administrator');
		
		$function = wpl_request::getVar('wpl_function');
		
		if($function == 'generate_new_page')
		{
			$type = wpl_request::getVar('type');
			$this->generate_new_page($type);
		}
		elseif($function == 'sort_units')
		{
			$sort_ids = wpl_request::getVar('sort_ids');
			$this->sort_units($sort_ids);
		}
		elseif($function == 'unit_enabled_state_change')
		{
			$unit_id = wpl_request::getVar('unit_id');
			$enabled_status = wpl_request::getVar('enabled_status');
			$this->update($unit_id, 'enabled', $enabled_status);
		}
		elseif($function == 'after_before_change_state')
		{
			$unit_id = wpl_request::getVar('unit_id');
			$after_before_status = wpl_request::getVar('after_before_status');			
			$this->update($unit_id, 'after_before', $after_before_status);
		}
		elseif($function == 'unit_3digit_seperator_change')
		{
			$unit_id = wpl_request::getVar('unit_id');
			$seperator = wpl_request::getVar('seperator');			
			$this->update($unit_id, "seperator", $seperator);
		}
		elseif($function == 'unit_decimal_seperator_change')
		{
			$unit_id = wpl_request::getVar('unit_id');
			$d_seperator = wpl_request::getVar('d_seperator');			
			$this->update($unit_id, 'd_seperator', $d_seperator);
		}
		elseif($function == 'update_exchange_rates')
		{			
			$this->update_exchange_rates();
		}
		elseif($function == 'update_a_exchange_rate')
		{			
			$unit_id = wpl_request::getVar('unit_id');			
			$currency_code = wpl_request::getVar('currency_code');			
			$this->update_a_exchange_rate($unit_id, $currency_code);
		}
		elseif($function == 'exchange_rate_manual')
		{			
			$unit_id = wpl_request::getVar('unit_id');
			$tosi = wpl_request::getVar('tosi');			
			$this->update($unit_id, 'tosi', $tosi);
		}
		elseif($function == 'change_currnecy_name')
		{			
			$unit_id = wpl_request::getVar('unit_id');
			$name = wpl_request::getVar('name');			
			$this->update($unit_id, 'name', $name);
		}		
	}

	/**
	*	{$type} 
	*	$type is a unit type for filtering
	**/
	private function generate_new_page($type)
	{		
		$this->units = wpl_units::get_units($type,"","");	
		
		if($type == 4)
			parent::render($this->tpl_path, 'internal_unit_manager_currency') ;
		else
			parent::render($this->tpl_path, 'internal_unit_manager_general') ;
		
		exit;
	}
	
	/**
	*{tablename,unit_id,key,value of key}
	* this function call update function in units library and change value of a field
	**/
	private function update($unit_id, $key, $value = '')
	{
		$res = wpl_units::update($unit_id, $key, $value);
		
		$res = (int) $res;
		$message = $res ? __('Operation was successful.', WPL_TEXTDOMAIN) : __('Error Occured.', WPL_TEXTDOMAIN);
		$data = NULL;
		
		$response = array('success'=>$res, 'message'=>$message, 'data'=>$data);		
		echo json_encode($response);
		exit;
	}
	
	private function sort_units($sort_ids)
	{
		if(trim($sort_ids) == '') $sort_ids = wpl_request::getVar('sort_ids');
		wpl_units::sort_units($sort_ids);		
		exit;
	}	
	
	/**
	*	call wpl_units::update_exchange_rates for connect to yahoo
	*	server and exchange currency rates
	**/
	private function update_exchange_rates()
	{
		wpl_units::update_exchange_rates();			
	}
	
	/**
	*	get a currency id and exchange rate it by unit library
	**/
	private function update_a_exchange_rate($unit_id, $currency_code)
	{
		$res = wpl_units::update_a_exchange_rate($unit_id, $currency_code);
		
		$success = $res ? true : false;
		$response = array('success'=>$success, 'res'=>$res);
		
		echo json_encode($response);
		exit;
	}
}
