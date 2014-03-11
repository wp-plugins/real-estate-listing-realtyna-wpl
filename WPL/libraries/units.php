<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/**
** units Library
** Developed 05/01/2013
**/

class wpl_units
{
	/**
		return unit types [AREA,VALUME,....]	
	**/
	public function get_unit_types()
	{				
		$query = "SELECT * FROM `#__wpl_unit_types`  ORDER BY `id` ASC";		
		return wpl_db::select($query, 'loadAssocList');
	}
	
	/**
		@input $unit_type
		@param $unit_type: is a unit type id for get all units about it
		@return a unit type
	**/
	public function get_units($type = 4, $enabled = 1, $condition = '')
	{
		if(trim($condition) == '')
		{
			$condition = '';
			
			if(trim($type) != '') $condition .= " AND `type`='$type'";
			if(trim($enabled) != '') $condition .= " AND `enabled`>='$enabled'";
		}
		
		$query = "SELECT * FROM `#__wpl_units` WHERE 1 ".$condition." ORDER BY `index` ASC";
		return wpl_db::select($query, 'loadAssocList');
	}
	
	/**
		wpl_units::get_unit()
		Get unit by ID
	**/
	public function get_unit($id)
	{
		/** first validation **/
		if(trim($id) == '') return array();
		
		$unit = wpl_units::get_units('', '', " AND `id`='".$id."'");
		return $unit[0];
	}	
	
	/**
		@input $sort_ids
	**/
	public function sort_units($sort_ids)
	{
		$query = "SELECT `id`,`index` FROM `#__wpl_units` WHERE `id` IN ($sort_ids) ORDER BY `index` ASC";
		$units = wpl_db::select($query, 'loadAssocList');
		
		$conter = 0;
		$ex_sort_ids = explode(',', $sort_ids);
		
		foreach($ex_sort_ids as $ex_sort_id)
		{
			self::update($ex_sort_id, 'index', ($conter+1));
			$conter++;
		}
	}
	
	/**
		@input {table}, {key}, {unit_id} and [value]
		@return boolean result
	**/
	public function update($unit_id, $key, $value = '')
	{
		/** first validation **/
		if(trim($unit_id) == '' or trim($key) == '') return false;
		return wpl_db::set('wpl_units', $unit_id, $key, $value);
	}	
	
	/**	
		 this function is to update all currencies exchange rates from yahoo server
	**/	
	public function update_exchange_rates()
	{
		$currencies = self::get_units(4);

		foreach ($currencies as $currency)
		{
			$currency_code = $currency['extra'];
			$exchange_rate = self::currency_converter($currency_code, 'USD', 1);
			self::update($currency['id'], 'tosi', $exchange_rate);
		}	
	}
	
	/**
		@input $unit_id
		@input $currency_code
		@param $unit_id 
		@param $currency_code this is a currency code for exchange to USD unit
		@return true or false
	**/
	public function update_a_exchange_rate($unit_id, $currency_code)
	{
		$exchange_rate = self::currency_converter($currency_code, 'USD', 1);
		$result = self::update($unit_id, 'tosi', $exchange_rate);
		
		if($result)	return $exchange_rate;
		else return 0;
	}
	
	/**
		@input $unit_id
		@input $value
		@param $unit_id 
		@param $value this is a currency value that set manual by admin
		@return true or false
	**/
	public function update_exchange_rate($unit_id, $value)
	{
		return self::update($unit_id, 'tosi', $value);
	}
	
	/**
		@input $cur_from currency
		@input $cur_to
	**/
	public function currency_converter($cur_from, $cur_to, $val)
	{
		if(strlen($cur_from) == 0) $cur_from = "USD";
		if(strlen($cur_to) == 0) $cur_to = "CAD";
		if($cur_from == $cur_to) return '1.0000';
		
		$host = "download.finance.yahoo.com";
		$fp = @fsockopen($host, 80, $errno, $errstr, 30);
		
		if(!$fp) 
		{
			$errorstr = "$errstr ($errno)<br />\n";
			return false;
		} 
		else 
		{
			$data = '';
			$file = "/d/quotes.csv";
			$str = "?s=".$cur_from.$cur_to."=X&f=sl1d1t1ba&e=.csv";
			$out = "GET ".$file.$str." HTTP/1.0\r\n";
			$out .= "Host: download.finance.yahoo.com\r\n";
			$out .= "Connection: Close\r\n\r\n";
			@fputs($fp, $out);
			
			while(!@feof($fp))
			{
				$data .= @fgets($fp, 128);
			}
			
			@fclose($fp);
			@preg_match("/^(.*?)\r?\n\r?\n(.*)/s", $data, $match);
			$data = $match[2];
			$search = array ("'<script[^>]*?>.*?</script>'si","'<[\/\!]*?[^<>]*?>'si","'([\r\n])[\s]+'","'&(quot|#34);'i","'&(amp|#38);'i","'&(lt|#60);'i","'&(gt|#62);'i","'&(nbsp|#160);'i","'&(iexcl|#161);'i","'&(cent|#162);'i","'&(pound|#163);'i","'&(copy|#169);'i","'&#(\d+);'e");
			$replace = array ("","","\\1","\"","&","<",">"," ",chr(161),chr(162),chr(163),chr(169),"chr(\\1)");
			$data = @preg_replace($search, $replace, $data);
			$result = @split(",",$data);
			$w  = @ereg_replace("[^0-9\.]", "", $val);
			$w2 = number_format($w, 2, '.', '');
			
			$x  = $result[1];
			$x1 = $x * $w;
			$x2 = number_format($x1, 4, '.', '');
			
			return $x2;
		}		
	}
}
